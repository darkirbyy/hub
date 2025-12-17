<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class FakeUnitTest extends TestCase
{
    public function testFake(): void
    {
        $test = 1;

        $this->assertSame($test, 1);
    }
}
