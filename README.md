<h1 align="center">Библиотека для интеграции с Массовыми выплатами от Яндекс</h1>

- [Описание](#description)
- [Установка](#Installation)
- [Как пользоваться?](#howuseit)
    - [Настройка](#settings)
    - [Генерация clientOrderId](#generatorId)
    - [Начисление на телефон](#phone)
    - [Начисление на яндекс кошелек](#yandex-purse)
    - [Структура ответа](#report)
    - [Интеграция с Laravel](#laravel)

- [Дополнительный материалы](#extra)
- [Угостить чаем 😌](#donate)

<a name="description"></a>

## Описание

Библиотека предоставляет функционал по начислению денег в яндекс выплаты.

### Существует две версии библиотеки:

#### v1 (Старая версия)

Виды выплат и возможности

     ✅ PHP 5

     ✅ На телефон
    
     ✅ На яндекс кошелек
    
     ✅ На банковскую карту
     
     ❌ Интеграция с Laravel

     ❌ Автоинкрементирование clientOrderId

     ❌ Сложный API

#### v2 (Новая версия)

Виды выплат и возможности

     ✅ PHP >=7.3

     ✅ На телефон
    
     ✅ На яндекс кошелек
    
     ❌ На банковскую карту
     
     ✅ Интеграция с Laravel

     ✅ Автоинкрементирование clientOrderId

     ✅ ClientOrderId через модель Eloquent

     ✅ ClientOrderId в формате UUID

     ✅ Легкий API

<a name="Installation"></a>

## Установка

```shell
composer require agoalofalife/yandex-money-payout
```

<a name="howuseit"></a>

## Как пользоваться?

Для работы с пакетом вам надо закончить все юридические и технические моменты с
Яндекс и получить сертификаты для взаимодействия с серверами ЮKassa.

<a name="settings"></a>

## Настройка сертификатов и данных

```php
use YandexMoney\Settings;

$settings = new Settings();

// Получите у менеджера agentId — идентификатор вашего шлюза в ЮKassa.
$settings->agentId = '';

// абсолютный путь до файла - сертификат который
// присылает яндекс в конце по почте -
// обычно такое название 201111.cer => 201111.pem надо изменить на pem
$settings->cert = '201111.pem';

// пароль от сертификата privateKey
$settings->certPassword = '';


// абсолютный путь до файла - приватный ключ - который создается на 
// вашей стороне,
// вот ссылка как
// https://yookassa.ru/docs/payouts/api/using-api/security#creating-private-key
// private.pem => ожидается в расширении .pem
$settings->privateKey = '';

// абсолютный путь до файла -
// сертификакт который отправляется в яндекс вместе с заявкой
//request.cer => нужен в расширении .cer ссылка на него
// https://yookassa.ru/docs/payouts/api/using-api/security#creating-csr
$settings->yaCert = '';
```

<a name="generatorId"></a>

## Генерация clientOrderId

Далее необходимо выбрать способ генерации `clientOrderId`:

ℹ️ UUID

`YandexPayout\Generators\ClientOrderUuid`

    Генерация clientOrderId через uuid version 4,
    случайным образом генерируется уникальный id

 ```php
$generator = new \YandexPayout\Generators\ClientOrderUuid();
 ```

ℹ️ Eloquent Id

`YandexPayout\Generators\ClientOrderEloquent`

    Генерация номера по порядковому номеру id в базе данных через eloquent 
    модель laravel

 ```php
$generator = new \YandexPayout\Generators\ClientOrderEloquent(new \App\Models\YandexPayout());
 ```

ℹ️ Свой вариант

Вы можете реализовать свой способ через интерфейс

`YandexPayout\Contracts\GeneratorClientOrderId`

    - public function getId(): string;
       Получение текущего id, например для id из базы - это следующий номер 
       после крайнего.  
      
    - public function generateNextId(): string;
      Реализация следуеющего номера - это может быть просто порядковый номер 
      или как в случае uuid уникальный - зависит от вас. Метод нужен - если 
      под текущим id - уже есть запись в яндекс и надо повторить запрос с 
      новым clientOrderId

<a name="phone"></a>

## Начисление на телефон

✅ Проверка возможности осуществлении
платежа [(testDeposition)](https://yookassa.ru/docs/payouts/api/make-deposition/basics#test-deposition)

```php
// Передаем настройки
$settings = new Settings();
$settings->agentId = '';
$settings->cert = '';
$settings->certPassword = '';
$settings->privateKey = '';
$settings->yaCert = '';

// Выбираем генератор
$generator = new \YandexPayout\Generators\ClientOrderEloquent(new \App\Models\Reward\MoneyReward\Drivers\YandexPayout());

$phone = new \YandexPayout\Accounts\Phone($settings, $generator);
$phone->setDstAccount('79052075556'); // передаем номер строго так
$phone->setAmount(1);// сумма - ожидает float
$phone->setContract('Тестовый платеж');

// Далее несколько стратегий отправки
$phone->canSend(); // разовый запрос можно ли отправить деньги
$phone->send(); // сразу попытаться отправить или после информации от метода выше

$phone->sendIncrementId(); // будут произвоидится попытки начисления 
// денег - с последующей генерации следующего clientOrderId, пока 
// никакаих ограничений нет - будет до победного

$phone->getReport(); // получение отчета о запросе - где данные об 
// ответе сервиса и данные из запроса, для получения данных - 
// предоставлены get методы
```

<a name="yandex-purse"></a>

## Начисление на яндекс кошелек

✅ Проверка возможности осуществлении
платежа [(testDeposition)](https://yookassa.ru/docs/payouts/api/make-deposition/basics#test-deposition)

```php
// Передаем настройки
$settings = new Settings();
$settings->agentId = '';
$settings->cert = '';
$settings->certPassword = '';
$settings->privateKey = '';
$settings->yaCert = '';

// Выбираем генератор
$generator = new \YandexPayout\Generators\ClientOrderUuid();

$phone = new \YandexPayout\Accounts\YandexPurse($settings, $generator);
$phone->setDstAccount('4100116075156746'); // передаем номер строго так
$phone->setAmount(1);// сумма - ожидает float
$phone->setContract('Тестовый платеж');

// Далее несколько стратегий отправки
$phone->canSend(); // разовый запрос можно ли отправить деньги
$phone->send(); // сразу попытаться отправить или после информации 
//        от метода выше
$phone->sendIncrementId(); // будут произвоидится попытки начисления 
//        денег - с последующей генерации следующего clientOrderId, пока 
//        никакаих ограничений нет - будет до победного

$phone->getReport(); // получение отчета о запросе - где данные об 
//        ответе сервиса и данные из запроса, для получения данных - 
//        предоставлены get методы
```

<a name="report"></a>

## Структура ответа

Структура ответа `$phone->getReport()`

```php
// Примерная структура такая
YandexPayout\ReportOfRequest {#1637 ▼
  -clientOrderId: "1"
  -amount: 1.0
  -dstAccount: "79052075556"
  -contract: "Тестовый платеж"
  -agentId: "201111"
  -currency: 643
  -response: YandexPayout\Response\Response {#1652 ▼ // Объект 
// response можно получить через метод $phone->getReport()->response()
    -balance: "200.36"
    -processedDT: "2021-10-20T21:29:50.747+03:00"
    -identification: "reviewed"
    -techMessage: null
    -status: "0"
    -error: null
  }
}
```

<a name="laravel"></a>

## Интеграция с Laravel

Пакет имеет некоторое упрощении в использовании - через контейнер laravel

Скопируем файл конфигурации в папку config

```shell
php artisan vendor:publish --tag= yandex-payouts
```

Далее надо передать все настройки и выбрать генератор по-умолчанию

```php
'cardSynonimUrl' => '',
'agentId'        => env('YANDEX_MONEY_PAYOUT_AGENT_ID', ''),
'certPassword'   => env('YANDEX_MONEY_PAYOUT_CERT_PASSWORD', ''),
'cert' => env('YANDEX_MONEY_PAYOUT_CERT', ''),
 // абсолютный путь 200000.pem
'privateKey' => env('YANDEX_MONEY_PAYOUT_CERT_PRIVATE', ''),
// абсолютный путь private.pem
'yaCert' => env('YANDEX_MONEY_PAYOUT_CERT_REQUEST', ''), 
// абсолютный путь request.cer

'generator' => [
    'type' => \YandexPayout\Generators\ClientOrderEloquent::class,
    'model' => \App\Models\YandexPayout::class
]
```

Далее использование - сводится к получению объектов из контейнера. Вы можете
получать эти объекты в других местах(контроллеры, очереди) - везде где есть авто
resolve

```php
$phone = new \YandexPayout\Accounts\YandexPurse(app(Settings::class), app(GeneratorClientOrderId::class));
$phone->setDstAccount('4100116075156746');
$phone->setAmount(1);
$phone->setContract('Тестовый платеж');

dd($phone->sendIncrementId(), $phone->getReport());
```

<a name="extra"></a>

## Дополнительный материалы

* Документация яндекса [здесь](https://yookassa.ru/docs/payouts)

<a name="donate"></a>

## Угостить чаем или кофем 😌

Этот пакет был создан с целью экономии времени для коллег разработчиков. Если он
вам помог сэкономить время - то я буду рад вашей поддержки в виде звезды или
скромного доната.

Этот простой знак внимания - даст мне понять - что труды не напрасны.

https://money.yandex.ru/to/410019109036855
