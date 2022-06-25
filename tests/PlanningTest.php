<?php

namespace App\Tests;

use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\AssertEquals;

class PlanningTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testfindAllAtelier():void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $repo = $container->get(PlanningRepository::class);
        $atelier = $repo->testfindAllAtelier();
        assertEquals(0, count($atelier->getLibelle()));
    }
}
