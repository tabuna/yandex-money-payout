<?php

declare(strict_types=1);

namespace YandexPayout\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use YandexMoney\Settings;
use YandexPayout\Contracts\GeneratorClientOrderId;
use YandexPayout\Generators\ClientOrderEloquent;
use YandexPayout\Generators\ClientOrderUuid;

class YandexPayoutProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/yandex-payouts.php' => config_path('yandex-payouts.php')
        ], 'yandex-payouts');

        $this->app->bind(Settings::class, function () {
            $settings = new Settings();
            $settings->agentId = config('yandex-payouts.agentId');
            $settings->cert = config('yandex-payouts.cert');
            $settings->certPassword = config('yandex-payouts.certPassword');
            $settings->privateKey = config('yandex-payouts.privateKey');
            $settings->yaCert = config('yandex-payouts.yaCert');
            return $settings;
        });
        $this->app->bind(GeneratorClientOrderId::class, function () {
            $generator = config('yandex-payouts.generator.type');
            if ($generator === ClientOrderEloquent::class) {
                $model = config('yandex-payouts.generator.model');
                if (!class_exists($model)) {
                    throw new ClassNotFoundException('eloquent model not found');
                }
                return new $generator(new $model());
            }
            return new ClientOrderUuid();
        });
    }
}
