<?php

use FNS\Receipt\Client;
use FNS\Receipt\Exception\InvalidFiscalNumberFormatException;
use FNS\Receipt\Exception\InvalidFiscalTypeException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ReceiptTest extends TestCase
{
    /** @test */
    public function it_should_be_exists_from_client_with_success(): void
    {
        $mock = new MockHandler([
            new Response(204, [], '')
        ]);
        $handler = HandlerStack::create($mock);

        $receipt = new FNS\Receipt\Model\Receipt();
        $receipt->setNumber('8710000100518392');
        $receipt->setDocument('54812');
        $receipt->setTag('3522207165');
        $receipt->setType(FNS\Receipt\Model\Receipt::FNS_RECEIPT_TYPE_INCOMING);
        $receipt->setDate(new DateTime());
        $receipt->setPrice(100.00);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->receipt($receipt)->exists();

        $this->assertTrue($data->isSuccess());
    }

    /** @test */
    public function it_should_be_exists_from_constructor_with_error(): void
    {
        $mock = new MockHandler([
            new Response(400, [], 'Missing required property: sum')
        ]);
        $handler = HandlerStack::create($mock);

        $receipt = new FNS\Receipt\Model\Receipt();
        $receipt->setNumber('8710000100518392');
        $receipt->setDocument('54812');
        $receipt->setTag('3522207165');
        $receipt->setType(FNS\Receipt\Model\Receipt::FNS_RECEIPT_TYPE_INCOMING);
        $receipt->setDate(new DateTime());

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->receipt($receipt)->exists();

        $this->assertFalse($data->isSuccess());
        $this->assertEquals('Missing required property: sum', $data->getErrors()[0]);
    }

    /** @test */
    public function it_should_be_return_detail(): void
    {
        $mock = new MockHandler([
            new Response(204, [], json_encode([
                'document' => [
                    'receipt' => [
                        'operationType' => 1,
                        'fiscalSign' => '3522207165',
                        'dateTime' => (new DateTime())->format('Y-m-d\TH:i:s'),
                        'rawData' => 'QBAAE=',
                        'totalSum' => 10000,
                        'nds10' => 364,
                        'userInn' => '7000000000',
                        'taxationType' => 1,
                        'operator' => 'John Doe',
                        'fiscalDocumentNumber' => 54812,
                        'properties' => [
                            'value' => 'G637',
                            'key' => 'Код',
                        ],
                        'receiptCode' => 3,
                        'requestNumber' => 162,
                        'user' => 'ООО Ромашка',
                        'kktRegId' => '1000000000000000',
                        'fiscalDriveNumber' => '1000000000000000',
                        'items' => [
                            [
                                'sum' => 10000,
                                'price' => 10000,
                                'name' => 'test item',
                                'quantity' => 1,
                                'nds10' => 364
                            ]
                        ],
                        'ecashTotalSum' => 0,
                        'retailPlaceAddress' => 'Moscow',
                        'cashTotalSum' => 10000,
                        'shiftNumber' => 278
                    ]
                ]
            ]))
        ]);
        $handler = HandlerStack::create($mock);

        $receipt = new FNS\Receipt\Model\Receipt();
        $receipt->setNumber('8710000100518392');
        $receipt->setDocument('54812');
        $receipt->setTag('3522207165');
        $receipt->setType(FNS\Receipt\Model\Receipt::FNS_RECEIPT_TYPE_INCOMING);
        $receipt->setDate(new DateTime());
        $receipt->setPrice(100.00);

        $client = new Client('79991234567');
        $client->setClient(new GuzzleHttp\Client(['handler' => $handler]));
        $data = $client->receipt($receipt)->detail();

        $this->assertTrue($data->isSuccess());
    }

    /** @test */
    public function it_should_be_return_invalid_fiscal_number_format_exception(): void
    {
        $this->expectException(InvalidFiscalNumberFormatException::class);

        $receipt = new FNS\Receipt\Model\Receipt();
        $receipt->setNumber('87100001005183920');
    }

    /** @test */
    public function it_should_be_return_invalid_fiscal_type_exception(): void
    {
        $this->expectException(InvalidFiscalTypeException::class);

        $receipt = new FNS\Receipt\Model\Receipt();
        $receipt->setType(3);
    }
}
