<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use YandexPayout\Generators\ClientOrderUuid;

class ClientOrderUuidTest extends TestCase
{
    /**
     * @test
     */
    public function getIdReturnSameUuid(): void
    {
        $generator = new ClientOrderUuid();
        $this->assertSame($generator->getId(), $generator->getId());
    }

    /**
     * @test
     */
    public function generateNextIdAlwaysNewId(): void
    {
        $generator = new ClientOrderUuid();
        $this->assertNotSame($generator->generateNextId(),
            $generator->generateNextId());
    }
}
