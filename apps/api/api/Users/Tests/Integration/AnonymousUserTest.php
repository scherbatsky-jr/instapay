<?php

namespace Api\Users\Tests\Integration;

class AnonymousUserTest extends TestCase
{
    public const USER_ROLE = null;

    protected function assertGetAll($response)
    {
        $errorExtension = $this->getErrorExtension(
            static::UNAUTHENTICATED_ERROR_MESSAGE,
            static::UNAUTHENTICATED_GUARD
        );

        $this->assertErrorExtensionEqual($response, $errorExtension);
    }

    protected function assertMe($response, $data)
    {
        $errorExtension = $this->getErrorExtension(
            static::UNAUTHENTICATED_ERROR_MESSAGE,
            static::UNAUTHENTICATED_GUARD
        );

        $this->assertErrorExtensionEqual($response, $errorExtension);
    }

    protected function assertDailyOperatorStats($response)
    {
        
        $errorExtension = $this->getErrorExtension(
            static::UNAUTHENTICATED_ERROR_MESSAGE,
            static::UNAUTHENTICATED_GUARD
        );

        $this->assertErrorExtensionEqual($response, $errorExtension);
    }


    protected function assertUpdate($response, $data)
    {
        $errorExtension = $this->getErrorExtension(
            static::UNAUTHENTICATED_ERROR_MESSAGE,
            static::UNAUTHENTICATED_GUARD
        );

        $this->assertErrorExtensionEqual($response, $errorExtension);
    }
}
