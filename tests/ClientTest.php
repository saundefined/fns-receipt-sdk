<?php

use FNS\Receipt\Client;
use FNS\Receipt\Exception\InvalidPhoneFormatException;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    /** @test */
    public function it_should_be_init_contructor(): void
    {
        $client = new Client('79991234567');

        $this->assertInstanceOf(Client::class, $client);
    }

    /** @test */
    public function it_should_be_failed_constructor(): void
    {
        $this->expectException(InvalidPhoneFormatException::class);

        $client = new Client('799912345670');
    }

    /** @test */
    public function it_should_be_return_phone_number(): void
    {
        $client = new Client('79991234567');
        $this->assertEquals('+79991234567', $client->getPhone());
    }

    /** @test */
    public function it_should_be_return_phone_number_plus(): void
    {
        $client = new Client('+79991234567');
        $this->assertEquals('+79991234567', $client->getPhone());
    }

    /** @test */
    public function it_should_be_return_credentials(): void
    {
        $client = new Client('79991234567');
        $client->setCredentials(123456);
        $this->assertEquals(123456, $client->getCredentials());
    }
}
