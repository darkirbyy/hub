<?php

declare(strict_types=1);

namespace App\Tests\Inte;

use App\Repository\AppliRepository;
use PHPUnit\Framework\Attributes as PU;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeInteTest extends KernelTestCase
{
    #[PU\Test]
    public function fake(): void
    {
        self::bootKernel();
        $container = static::getContainer()->get(AppliRepository::class);
        $this->assertInstanceOf(AppliRepository::class, $container);
    }
}
