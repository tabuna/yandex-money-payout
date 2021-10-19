<?php

namespace YandexPayout\Contracts;

interface Reportable
{
    public function toReport(): array;
}
