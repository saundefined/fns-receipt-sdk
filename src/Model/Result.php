<?php

namespace FNS\Receipt\Model;

class Result
{
    private $errors = [];

    private $body;

    public function addError($error): void
    {
        $this->errors[] = $error;
    }

    public function isSuccess(): bool
    {
        return empty($this->errors);
    }

    public function addBody($body): void
    {
        $this->body = $body;
    }
}
