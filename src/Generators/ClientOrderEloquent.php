<?php

declare(strict_types=1);

namespace YandexPayout\Generators;

use Illuminate\Database\Eloquent\Model;
use YandexPayout\Contracts\GeneratorClientOrderId;

class ClientOrderEloquent implements GeneratorClientOrderId
{
    /**
     * @var Model
     */
    private $model;

    private $id;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getId(): string
    {
        if (is_null($this->id)) {
            $this->id = $this->incrementId();
        }

        return (string) $this->id;
    }

    public function generateNextId(): string
    {
        return (string) $this->id = $this->id + 1;
    }

    private function incrementId(): int
    {
        $lastRow = $this->model->newQuery()->orderByDesc('id')->limit(1)->first();

        if (is_null($lastRow)) {
            return 1;
        } else {
            return $lastRow->id + 1;
        }
    }
}
