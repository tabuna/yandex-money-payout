<?php

namespace YandexPayout\Contracts;

interface TestDeposition
{
    public function canSend(): bool;
}
