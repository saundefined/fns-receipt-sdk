<?php

namespace FNS\Receipt;

class Receipt
{
    private $client;

    private $receipt;

    /**
     * Operation constructor.
     * @param Client $client
     * @param Model\Receipt $receipt
     */
    public function __construct(Client $client, Model\Receipt $receipt)
    {
        $this->client = $client;

        $this->receipt = $receipt;
    }

    public function exists(): Model\Result
    {
        return $this->client->query('ofds/*/inns/*/fss/' . $this->receipt->getNumber() .
            '/operations/' . $this->receipt->getType() . '/tickets/' . $this->receipt->getDocument(),
            [
                'fiscalSign' => $this->receipt->getTag(),
                'date' => $this->receipt->getDate()->format('d.m.Y H:i:s'),
                'sum' => $this->receipt->getPrice() * 100
            ]);
    }

    public function detail($sentToEmail = false): Model\Result
    {
        return $this->client->query('inns/*/kkts/*/fss/' . $this->receipt->getNumber() .
            '/tickets/' . $this->receipt->getDocument(),
            [
                'fiscalSign' => $this->receipt->getTag(),
                'sendToEmail' => $sentToEmail ? 'yes' : 'no'
            ]);
    }
}
