<?php

declare(strict_types=1);

namespace YandexPayout\Accounts;

use YandexMoney\DepositionRequestParams;
use YandexPayout\Contracts\TestDeposition as TestDepositionContract;
use YandexPayout\TestDeposition;
use YandexPayout\YandexPayout;

class YandexPurse extends YandexPayout implements TestDepositionContract
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

        $this->response = $this->service->{$typeRequest}($depositionParams);
    }
}
