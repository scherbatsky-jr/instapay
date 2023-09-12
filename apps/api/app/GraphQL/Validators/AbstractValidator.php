<?php

namespace App\GraphQL\Validators;

use Api\Users\Models\User;
use App\GraphQL\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class AbstractValidator
{
    protected $attributes;
    protected $data;
    protected $messages;
    protected $rules;
    protected $user;
    protected $validator;

    public function __construct(
        array $data,
        array $rules = [],
        array $messages = [],
        array $attributes = [],
        User $user = null
    ) {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->attributes = $attributes;
        $this->user = $user;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getValidator()
    {
        if (!$this->validator) {
            $this->validator = ValidatorFacade::make(
                $this->getData(),
                $this->getRules(),
                $this->getMessages(),
                $this->getAttributes()
            );
        }

        return $this->validator;
    }

    /**
     * @return array
     *
     * @throws ValidationException
     */
    public function validate()
    {
        if (!$this->authorize()) {
            if ($this->getUser()) {
                throw new AccessDeniedHttpException('not authenticated');
            } else {
                throw new UnauthorizedHttpException('not authorized');
            }
        }

        $validator = $this->getValidator();

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = collect($this->getData());

        return $data->only(
            collect($this->getRules())->keys()->map(
                function ($rule) {
                    return explode('.', $rule)[0];
                }
            )->unique()
        )->toArray();
    }

    protected function authorize()
    {
        return true;
    }

    protected function getData()
    {
        return $this->data;
    }

    protected function getUser()
    {
        return $this->user;
    }
}
