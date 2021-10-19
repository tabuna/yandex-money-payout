<?php

namespace YandexMoney;

final class Settings
{
    public $host = 'https://payouts.yookassa.ru:9094';

    public $privateKey;
    public $cert;
    public $certPassword;
    public $yaCert;
    public $currency = 643;
    public $agentId;
    public $synonimUrl = 'https://paymentcard.yoomoney.ru/gates/card/storeCard';
}
