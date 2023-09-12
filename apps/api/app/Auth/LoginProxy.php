<?php

namespace App\Auth;

use Api\Users\Events\LoginEvent;
use Api\Users\Models\User;
use App\Exceptions\Http\InvalidCredentialsException;
use App\Exceptions\UserDisabledException;
use App\Exceptions\UserNotFoundException;
use App\GraphQL\Exceptions\Exception as GraphQLException;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class LoginProxy
{
    public const CLIENT_NAME = 'client_proxy';

    public const REFRESH_TOKEN = 'refreshToken';

    protected $client;

    private $auth;

    private $cookie;

    private $db;

    private $dispatcher;

    private $request;

    public function __construct(
        Application $app,
        Dispatcher $dispatcher
    ) {
        $this->auth = $app->make('auth');

        $this->cookie = $app->make('cookie');

        $this->db = $app->make('db');

        $this->dispatcher = $dispatcher;

        $this->request = $app->make('request');
    }

    /**
     * Attempt to create an access token using user credentials.
     *
     * @param string $username
     * @param string $password
     */
    public function attemptLogin($username, $password, $withRoles = null)
    {
        try {
            $user = (new User())->findForAuth($username, $withRoles);

            if ($user) {
                return [
                    'user' => $user,
                    'auth_tokens' => $this->proxy(
                        'password',
                        [
                            'username' => $username,
                            'password' => $password,
                        ],
                        $user
                    ),
                ];
            }
        } catch (UserNotFoundException $exception) {
            throw new GraphQLException('Invalid credentials', 'error.invalid-credentials');
        } catch (UserDisabledException $exception) {
            throw new GraphQLException('User Disabled', 'error.user-disabled');
        }
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie.
     */
    public function attemptRefresh($refreshToken = null)
    {
        if (!$refreshToken) {
            $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);

            if (!$refreshToken) {
                $refreshToken = $this->request->input(self::REFRESH_TOKEN);
            }
        }

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param string $grantType what type of grant type should be proxied
     * @param array  $data      the data to send to the server
     *
     * @throws \Exception
     */
    public function proxy($grantType, array $data = [], $user = null)
    {
        $client = $this->getPasswordClient();

        if (!$client) {
            throw new \Exception('Password client not set.');
        }

        $data = array_merge($data, [
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => $grantType,
        ]);

        $this->request->request->add($data);

        $request = Request::create('/oauth/token', 'POST');

        $response = Route::dispatch($request);

        if (!$response->isSuccessful()) {
            throw new InvalidCredentialsException();
        }

        $data = json_decode($response->getContent());

        if ($user) {
            $this->dispatcher->dispatch(new LoginEvent($user));
        }

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000
        );

        return [
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in,
            'refresh_token' => $data->refresh_token,
        ];
    }

    public function revokeOtherTokens()
    {
        $access_token = $this->auth->user()->token();

        $user_id = $this->auth->user()->id;

        $this->db
            ->table('oauth_access_tokens')
            ->where('user_id', '=', $user_id)
            ->where('id', '<>', $access_token->id)
            ->update([
                'revoked' => true,
            ]);

        $access_tokens = $this->db
            ->table('oauth_access_tokens')
            ->where('user_id', '=', $user_id)
            ->where('id', '<>', $access_token->id)
            ->get()->toArray();

        $this->db
            ->table('oauth_refresh_tokens')
            ->whereIn('access_token_id', array_column($access_tokens, 'id'))
            ->update([
                'revoked' => true,
            ]);
    }

    /**
     * @param $user_id
     * Revoke all access tokens and refresh tokens.
     * Also instruct the client to forget the refresh cookie.
     */
    public function revokeTokens($user_id)
    {
        $this->db
            ->table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->update([
                'revoked' => true,
            ]);

        $access_tokens = $this->db
            ->table('oauth_access_tokens')
            ->where('user_id', '=', $user_id)
            ->get()->toArray();

        $this->db
            ->table('oauth_refresh_tokens')
            ->whereIn('access_token_id', array_column($access_tokens, 'id'))
            ->update([
                'revoked' => true,
            ]);

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }

    protected function findFirstPasswordClient()
    {
        return $this->db
            ->table('oauth_clients')
            ->where('password_client', '=', 1)
            ->where('revoked', '=', 0)
            ->limit(1)
            ->first();
    }

    protected function getDispatcher()
    {
        return $this->dispatcher;
    }

    protected function getPasswordClient()
    {
        $client = $this->findFirstPasswordClient();

        if (!$client) {
            Artisan::call('passport:client', [
                '-n' => true,
                '--name' => static::CLIENT_NAME,
                '--password' => true,
            ]);

            $client = $this->findFirstPasswordClient();
        }

        return $client;
    }

    protected function getUserService()
    {
        return $this->user_service;
    }
}
