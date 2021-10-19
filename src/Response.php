<?php

declare(strict_types=1);

namespace YandexPayout;

final class Response
{
    private $balance;
    private $processedDT;
    private $identification;
    private $techMessage;
    private $status;
    private $error;

    public function __construct(array $rawResponse)
    {
        $this->status = $rawResponse['status'];
        $this->error = $rawResponse['error'] ?? null;
        $this->balance = $rawResponse['balance'];
        $this->processedDT = $rawResponse['processedDT'];
        $this->identification = $rawResponse['identification'] ?? null;
        $this->techMessage = $rawResponse['techMessage'] ?? null;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @return null
     */
    public function getIdentification(): ?string
    {
        return $this->identification;
    }

    public function getTechMessage(): ?string
    {
        return $this->techMessage;
    }

    public function getProcessedDT()
    {
        return $this->processedDT;
    }

    public function getStatus(): int
    {
        return (int) $this->status;
    }

    public function getError(): int
    {
        return (int) $this->error;
    }
}
