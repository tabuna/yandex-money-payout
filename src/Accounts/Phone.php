<?php

declare(strict_types=1);

namespace YandexPayout\Accounts;

use YandexMoney\DepositionRequestParams;
use YandexMoney\MobilePaymentParams;
use YandexPayout\Contracts\TestDeposition as TestDepositionContract;
use YandexPayout\TestDeposition;
use YandexPayout\YandexPayout;

class Phone extends YandexPayout implements TestDepositionContract
{
    use TestDeposition;

    protected function makeRequest(string $typeRequest)
    {
        $depositionParams = new DepositionRequestParams(
            $this->settings->agentId,
            $this->generator->getId(),
            $typeRequest
        );

        $depositionParams->amount = $this->amount;
        $depositionParams->currency = $this->settings->currency;
        $depositionParams->contract = $this->contract;
        $depositionParams->dstAccount = $this->dstAccount;

        $paymentParams = new MobilePaymentParams();

        $paymentParams->operatorCode = substr($this->dstAccount, 1, 3);
        $paymentParams->phoneNumber = substr($this->dstAccount, 4);
        $depositionParams->setPaymentParams($paymentParams);
        $this->rawResponse = $this->service->{$typeRequest}($depositionParams);
    }
}
