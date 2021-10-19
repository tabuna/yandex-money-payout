<?php

declare(strict_types=1);

namespace YandexPayout;

use App\Services\Reward\RewardMoney\Drivers\YandexPayout\Config;
use YandexMoney\PayoutAPI;
use YandexMoney\PKCS7RequestProvider;
use YandexMoney\Settings;
use YandexPayout\Contracts\GeneratorClientOrderId;
use YandexPayout\Contracts\Reportable;

abstract class YandexPayout extends ResponseAssertions implements Reportable
{
    /**
     * @var Config
     */
    protected $settings;
    /**
     * @var string
     */
    protected $contract;

    protected $service;

    /**
     * @var string
     */
    protected $dstAccount;
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var GeneratorClientOrderId
     */
    protected $generator;

//    private $customError = '';

    public function __construct(
        Settings $settings,
        GeneratorClientOrderId $generator
    ) {
        $this->settings = $settings;
        $this->service = new PayoutAPI(new PKCS7RequestProvider($settings),
            $this->settings->synonimUrl);
        $this->generator = $generator;
    }

    public function setContract(string $text)
    {
        $this->contract = $text;
    }

    public function setDstAccount(string $account)
    {
        $this->dstAccount = $account;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function toReport(): array
    {
        return [
            'clientOrderId' => $this->generator->getId(),
            'amount'        => $this->amount,
            'dstAccount'    => $this->dstAccount,
            'contract'      => $this->contract,
            'agentId'       => $this->settings->agentId,
            'currency'      => $this->settings->currency,
        ];
    }

    public function getReport(): ReportOfRequest
    {
        return new ReportOfRequest(new Response($this->rawResponse), $this);
    }

    public function send(): bool
    {
        $this->makeRequest('makeDeposition');
        return $this->isSuccessRequest();
    }

    /**
     * Реализация выполнения запроса для начисления
     * @param  string  $typeRequest
     * @return mixed
     */
    abstract protected function makeRequest(string $typeRequest);
//    public function getError(): string
//    {
//        return empty($this->customError) ? PayoutAPI::translateError($this->response['error']) : $this->customError;
//    }
//
//    public function setCustomError(string $error)
//    {
//        $this->customError = $error;
//    }
}
