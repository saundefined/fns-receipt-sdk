<?php

namespace FNS\Receipt;

use Exception;
use FNS\Receipt\Exception\InvalidPhoneFormatException;
use FNS\Receipt\Model\Result;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    private $client;

    private $phone;

    private $credentials;

    /**
     * Client constructor.
     * @param string $phone Номер телефона в международном формате
     *
     * @throws InvalidPhoneFormatException
     */
    public function __construct($phone)
    {
        $phone = preg_replace('/[^\\d]/', '', $phone);

        if (strlen($phone) !== 11) {
            throw new InvalidPhoneFormatException('Invalid phone format');
        }

        $this->phone = '+' . $phone;

        $this->client = new GuzzleClient([
            'base_uri' => 'https://proverkacheka.nalog.ru:9999/v1/',
        ]);
    }

    /**
     * Запрос к API
     *
     * @param string $command
     * @param array $options
     * @param string $method
     *
     * @return Result
     */
    public function query($command, array $options = [], $method = 'GET'): Result
    {
        $data = [
            'headers' => [
                'Content-Type' => 'application/json;',
                'device-id' => '',
                'device-os' => '',
            ],
            'http_errors' => false,
        ];

        if (($phone = $this->getPhone()) && ($code = $this->getCredentials())) {
            $data['auth'] = [$phone, $code];
        }

        if ($method === 'GET') {
            $data['query'] = $options;
            $response = $this->client->get($command, $data);
        } else {
            $data['body'] = json_encode($options);
            $response = $this->client->post($command, $data);
        }

        $result = new Result();

        if (!in_array($response->getStatusCode(), [200, 204], true)) {
            $result->addError($response->getBody()->getContents());
        }

        try {
            $content = json_decode($response->getBody()->getContents(), true);
            $result->addBody($content);
        } catch (Exception $e) {

        }

        return $result;
    }

    public function authorization(): Authorization
    {
        return new Authorization($this);
    }

    public function receipt(Model\Receipt $receipt): Receipt
    {
        return new Receipt($this, $receipt);
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * @param int $credentials
     */
    public function setCredentials(int $credentials): void
    {
        $this->credentials = $credentials;
    }

}
