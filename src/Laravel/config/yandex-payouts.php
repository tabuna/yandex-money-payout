<?php

return [
    'cardSynonimUrl' => '',
    'agentId'        => env('YANDEX_MONEY_PAYOUT_AGENT_ID', ''),
    'certPassword'   => env('YANDEX_MONEY_PAYOUT_CERT_PASSWORD', ''),
    'cert'           => env('YANDEX_MONEY_PAYOUT_CERT', ''), // 200000.pem
    'privateKey'     => env('YANDEX_MONEY_PAYOUT_CERT_PRIVATE', ''),
    // private.pem
    'yaCert'         => env('YANDEX_MONEY_PAYOUT_CERT_REQUEST', ''),
    // request.cer

    'generator' => [
        'type'  => \YandexPayout\Generators\ClientOrderEloquent::class,
        'model' => ''
    ]
];
