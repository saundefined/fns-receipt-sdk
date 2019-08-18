<?php

namespace FNS\Receipt\Model;

use DateTime;
use FNS\Receipt\Exception\InvalidFiscalNumberFormatException;
use FNS\Receipt\Exception\InvalidFiscalTypeException;

class Receipt
{
    public const FNS_RECEIPT_TYPE_INCOMING = 1;

    public const FNS_RECEIPT_TYPE_REVERT = 2;

    /** @var string Фискальный Номер */
    private $number;

    /** @var string Фискальный документ */
    private $document;

    /** @var string Фискальный Признак Документа */
    private $tag;

    /** @var int Вид кассового чека */
    private $type;

    /** @var DateTime Дата с чека */
    private $date;

    /** @var float Сумма */
    private $price;

    /**
     * @return string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @throws InvalidFiscalNumberFormatException
     */
    public function setNumber(string $number): void
    {
        if (strlen($number) !== 16) {
            throw new InvalidFiscalNumberFormatException('Invalid format');
        }

        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getDocument(): ?string
    {
        return $this->document;
    }

    /**
     * @param string $document
     */
    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @throws InvalidFiscalTypeException
     */
    public function setType(int $type): void
    {
        if (!in_array($type, [self::FNS_RECEIPT_TYPE_INCOMING, self::FNS_RECEIPT_TYPE_REVERT], true)) {
            throw new InvalidFiscalTypeException('Invalid type');
        }

        $this->type = $type;
    }

    /**
     * @return DateTime
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

}
