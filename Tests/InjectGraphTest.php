<?php

namespace Prokl\BitrixOgGraphBundle\Tests;

use Bitrix\Main\Page\Asset;
use Faker\Factory;
use Faker\Generator;
use Prokl\BitrixOgGraphBundle\Services\InjectGraph;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Prokl\BitrixOgGraphBundle\Services\OpenGraphManager;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class InjectGraphTest
 * @package Prokl\BitrixOgGraphBundle\Tests
 * @coversDefaultClass InjectGraph
 *
 * @since 19.02.20201
 */
class InjectGraphTest extends TestCase
{
    /**
     * @var InjectGraph $obTestObject
     */
    private $obTestObject;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var OgDTO $dtoOpenGraph DTO для теста.
     */
    private $dtoOpenGraph;

    protected function setUp(): void
    {
        Mockery::resetContainer();
        parent::setUp();
        $this->faker = Factory::create();
        $this->dtoOpenGraph = new ogDTO([]);
        $this->obTestObject = new InjectGraph(
            $this->getMockOpenGraphManager(),
            Asset::getInstance()
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * inject().
     *
     * @return void
     */
    public function testInject(): void
    {
        $this->obTestObject->inject($this->dtoOpenGraph);

        $this->assertTrue(true);
    }

    /**
     * Мок OpenGraphManager.
     *
     * @return mixed
     */
    private function getMockOpenGraphManager()
    {
        $mock = Mockery::mock(OpenGraphManager::class);
        $mock->shouldReceive('setDto')->once()->andReturn($mock);

        return $mock->shouldReceive('go')->once()->getMock();
    }
}
