<?php

namespace YandexPayout\Contracts;

interface GeneratorClientOrderId
{
    /**
     * Get new or made before id
     * @return string
     */
    public function getId(): string;

    /**
     * Produce new id
     * @return string
     */
    public function generateNextId(): string;
}
