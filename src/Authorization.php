<?php

namespace FNS\Receipt;

use FNS\Receipt\Model\User;

class Authorization
{
    private $client;

    /**
     * Operation constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function signUp(User $user): Model\Result
    {
        return $this->client->query('mobile/users/signup', [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'phone' => $this->client->getPhone()
        ], 'POST');
    }

    public function restore(): Model\Result
    {
        return $this->client->query('mobile/users/restore', [
            'phone' => $this->client->getPhone()
        ], 'POST');
    }

    public function withCode($code): Model\Result
    {
        $this->client->setCredentials($code);

        return $this->client->query('mobile/users/login');
    }
}
