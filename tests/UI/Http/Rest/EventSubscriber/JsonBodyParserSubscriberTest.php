<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\EventSubscriber;


use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use UI\Http\Rest\EventSubscriber\JsonBodyParserSubscriber;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class JsonBodyParserSubscriberTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     */
    public function given_invalid_request_should_return_bad_request_error()
    {
        $jsonBodyParserSubscriber = new JsonBodyParserSubscriber();
        $request = new Request([], [], [], [], [], [], '{"test":}');
        $request->headers->set('Content-Type', 'application/json');

        $requestEvent = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $jsonBodyParserSubscriber->onKernelRequest($requestEvent);

        $response = $requestEvent->getResponse();
        self::assertEquals('Unable to parse json request.', $response->getContent());
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}