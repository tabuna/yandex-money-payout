<?php

declare(strict_types=1);

namespace YandexPayout\Generators;

use App\Models\Reward\MoneyReward\Drivers\YandexPayout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $statement = DB::select("SHOW TABLE STATUS LIKE '{$this->model->getTable()}'");
        return $statement[0]->Auto_increment;
    }
}
