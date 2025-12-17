<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FakeFuncTest extends WebTestCase
{
    public function testFake(): void
    {
        $client = static::createClient();
        $client->request('GET', '');
        $this->assertResponseIsSuccessful();
    }
}
