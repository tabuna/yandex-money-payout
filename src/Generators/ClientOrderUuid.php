<?php

declare(strict_types=1);

namespace YandexPayout\Generators;

use Ramsey\Uuid\Uuid;
use YandexPayout\Contracts\GeneratorClientOrderId;

class ClientOrderUuid implements GeneratorClientOrderId
{
    private $uuid;

    public function getId(): string
    {
        if (is_null($this->uuid)) {
            $this->uuid = $this->generateUuid();
        }
        return $this->uuid;
    }

    public function generateNextId(): string
    {
        return $this->uuid = $this->generateUuid();
    }

    private function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
