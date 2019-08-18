<?php

use FNS\Receipt\Authorization;
use FNS\Receipt\Client;
use FNS\Receipt\Model\User;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AuthorizationTest extends TestCase
{
    /** @test */
    public function it_should_be_restore_from_client_with_success(): void
    {
        $mock = new MockHandler([
            new Response(204, [], '')
        ]);
        $handler = HandlerStack::create($mock);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->restore();

        $this->assertTrue($data->isSuccess());
    }

    /** @test */
    public function it_should_be_restore_from_constructor_with_error(): void
    {
        $mock = new MockHandler([
            new Response(404, [], '404 Not Found')
        ]);
        $handler = HandlerStack::create($mock);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $authorization = new Authorization($client);
        $data = $authorization->restore();

        $this->assertFalse($data->isSuccess());
    }

    /** @test */
    public function it_should_be_return_user_info(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'email' => 'email@example.com',
                'name' => 'John Doe'
            ]))
        ]);
        $handler = HandlerStack::create($mock);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->withCode(111111);

        $this->assertTrue($data->isSuccess());
    }

    /** @test */
    public function it_should_be_return_user_signup(): void
    {
        $mock = new MockHandler([
            new Response(204, [], '')
        ]);
        $handler = HandlerStack::create($mock);

        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('email@example.com');

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->signUp($user);

        $this->assertTrue($data->isSuccess());
    }

    /** @test */
    public function it_should_be_return_user_signup_error(): void
    {
        $mock = new MockHandler([
            new Response(409, [], 'user exists')
        ]);
        $handler = HandlerStack::create($mock);

        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('email@example.com');

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->signUp($user);

        $this->assertFalse($data->isSuccess());
        $this->assertEquals('user exists', $data->getErrors()[0]);
    }

    /** @test */
    public function it_should_be_return_error_from_json(): void
    {
        $mock = new MockHandler([
            new Response(400, [], json_encode(['Missing required property: phone']))
        ]);
        $handler = HandlerStack::create($mock);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->restore();

        $this->assertFalse($data->isSuccess());
        $this->assertEquals('Missing required property: phone', $data->getErrors()[0]);
    }

    /** @test */
    public function it_should_be_return_error_from_plain_text(): void
    {
        $mock = new MockHandler([
            new Response(400, [], 'Missing required property: phone')
        ]);
        $handler = HandlerStack::create($mock);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->authorization()->restore();

        $this->assertFalse($data->isSuccess());
        $this->assertEquals('Missing required property: phone', $data->getErrors()[0]);
    }
}
