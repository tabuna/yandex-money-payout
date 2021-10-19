<?php

declare(strict_types=1);

namespace YandexPayout;

trait TestDeposition
{
    public function canSend(): bool
    {
        $this->makeRequest('testDeposition');
        return $this->isSuccessRequest();
    }

    private function isAccessibleId(): bool
    {
        $this->makeRequest('testDeposition');

        if ($this->isClientOrderIdDublicate()) {
            return false;
        }
        return true;
    }

    /**
     * В процессе отправки - увеличивается client order id
     * усли уже был ранее
     * @return bool
     */
    public function sendIncrementId(): bool
    {
        while ($this->isAccessibleId() === false) {
            $this->generator->generateNextId();
        }

        $this->send();
        return $this->isSuccessRequest();
    }
}
