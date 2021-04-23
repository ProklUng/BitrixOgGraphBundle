<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use Bitrix\Main\Page\Asset;
use Faker\Factory;
use Prokl\BitrixOgGraphBundle\Services\InjectGraph;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Prokl\BitrixOgGraphBundle\Services\OpenGraphManager;
use Mockery;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;

/**
 * Class InjectGraphTest
 * @package Prokl\BitrixOgGraphBundle\Tests
 * @coversDefaultClass InjectGraph
 *
 * @since 19.02.20201
 */
class InjectGraphTest extends BitrixableTestCase
{
    /**
     * @var InjectGraph $obTestObject
     */
    protected $obTestObject;

    /**
     * @var OgDTO $dtoOpenGraph DTO для теста.
     */
    private $dtoOpenGraph;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->dtoOpenGraph = new ogDTO([]);
        $this->obTestObject = new InjectGraph(
            $this->getMockOpenGraphManager(),
            Asset::getInstance()
        );
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
