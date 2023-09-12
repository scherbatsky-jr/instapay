<?php

namespace Api\Users\Services;

use Api\Users\Models\PasswordReset;
use Api\Users\Repositories\PasswordResetRepository;
use App\AbstractEntityService;
use App\Mail\PasswordResetMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetService extends AbstractEntityService
{
    protected $userService;

    public function __construct(PasswordResetRepository $repository, UserService $userService)
    {
        $this->repository = $repository;
        $this->userService = $userService;
    }

    public function deleteByToken($token)
    {
        return $this->getRepository()->deleteByToken($token);
    }

    public function getByToken($token)
    {
        return $this->getRepository()->getByToken($token);
    }

    public function getUserByEmail($email)
    {
        return $this->getUserService()->getUserByEmail($email);
    }

    public function sendPasswordResetMail($email, $url = null)
    {
        $user = $this->getUserByEmail($email);

        if (!$user) {
            return true;
        }

        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $data = [
            'email' => $user->email,
            'token' => $token,
        ];

        $passwordReset = new PasswordReset();

        if ($passwordResetByEmail = $this->getRepository()->getByEmail($data['email'])) {
            $passwordReset = $passwordResetByEmail;
        }

        $passwordReset->fill($data);

        $passwordReset->save();

        $message = new PasswordResetMessage($user, $url ? $url : $this->getAppUrl(), $token, $this->getLocale());

        try {
            $message->onQueue('emails');

            Mail::to($message->getRecipient())
                ->queue($message);
        } catch (\Exception $exception) {
            Log::error($exception);

            throw new \Exception('Error while sending password reset mail.');
        }

        return true;
    }

    protected function getAppUrl()
    {
        return config('app.url');
    }

    protected function getLocale()
    {
        return App::getLocale();
    }

    protected function getUserService(): UserService
    {
        return $this->userService;
    }
}
