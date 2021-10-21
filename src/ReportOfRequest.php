<?php

declare(strict_types=1);

namespace YandexPayout;

use YandexPayout\Contracts\Reportable;
use YandexPayout\Response\Response;

class ReportOfRequest
{
    private $clientOrderId;
    private $amount;
    private $dstAccount;
    private $contract;
    private $agentId;
    private $currency;

    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response, Reportable $reportable)
    {
        $this->response = $response;
        $this->clientOrderId = $reportable->toReport()['clientOrderId'];
        $this->amount = $reportable->toReport()['amount'];
        $this->dstAccount = $reportable->toReport()['dstAccount'];
        $this->contract = $reportable->toReport()['contract'];
        $this->agentId = $reportable->toReport()['agentId'];
        $this->currency = $reportable->toReport()['currency'];
    }

    public function response(): Response
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getClientOrderId(): int
    {
        return $this->clientOrderId;
    }

    /**
     * @return mixed
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getDstAccount(): string
    {
        return $this->dstAccount;
    }

    /**
     * @return mixed
     */
    public function getContract(): string
    {
        return $this->contract;
    }

    /**
     * @return mixed
     */
    public function getAgentId(): int
    {
        return $this->agentId;
    }

    /**
     * @return mixed
     */
    public function getCurrency(): int
    {
        return $this->currency;
    }
}
