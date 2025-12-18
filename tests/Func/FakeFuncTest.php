<?php

declare(strict_types=1);

namespace App\Tests\Func;

use PHPUnit\Framework\Attributes as PU;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[PU\RequiresFunction('databaseAvailable')]
final class FakeFuncTest extends WebTestCase
{
    public function testFake(): void
    {
        $client = static::createClient();
        $client->request('GET', '');
        $this->assertResponseIsSuccessful();
    }
}
