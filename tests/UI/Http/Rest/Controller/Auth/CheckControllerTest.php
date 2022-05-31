<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\Auth;


use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class CheckControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function bad_credentials_must_fail_with_401_error(): void
    {
        $this->post('/api/auth_check', [
            '_username' => self::DEFAULT_EMAIL,
            '_password' => 'pass'
        ]);

        self::assertSame(Response::HTTP_UNAUTHORIZED, $this->cli->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function invalid_email_must_fail_with_400_error(): void
    {
        $this->post('/api/auth_check', [
            '_username' => 'user@',
            '_password' => self::DEFAULT_PASS
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());
    }
}