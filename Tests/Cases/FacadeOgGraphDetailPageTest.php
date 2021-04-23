<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use Prokl\BitrixOgGraphBundle\Services\DetailPageProcessor;
use Prokl\BitrixOgGraphBundle\Services\Facades\FacadeOgGraphDetailPage;
use Prokl\BitrixOgGraphBundle\Services\InjectGraph;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Mockery;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;
use Psr\Cache\InvalidArgumentException;

/**
 * Class FacadeOgGraphDetailPageTest
 * @package Prokl\BitrixOgGraphBundle\Tests\Cases
 *
 * @since 20.02.2021
 */
class FacadeOgGraphDetailPageTest extends BitrixableTestCase
{
    /**
     * @var FacadeOgGraphDetailPage $obTestObject
     */
    protected $obTestObject;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $dtoOpenGraph = new ogDTO([]);
        $this->obTestObject = new FacadeOgGraphDetailPage(
            $this->getMockDetailPageProcessor(),
            $this->getMockInjectGraph(),
            $dtoOpenGraph
        );
    }

    /**
     * Проверка на количество вызываемых методов.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testMake() : void
    {
        $this->obTestObject->make(1, 1);

        $this->assertTrue(true);
    }

    /**
     * Мок DetailPageProcessor.
     *
     * @return mixed
     */
    private function getMockDetailPageProcessor()
    {
        $mock = Mockery::mock(DetailPageProcessor::class)->makePartial();

        $mock->shouldReceive('setIblockId')->once()->andReturnSelf();
        $mock->shouldReceive('setIdElement')->once()->andReturnSelf();
        $mock = $mock->shouldReceive('go')->once()->andReturn(['test']);

        return $mock->getMock();
    }

    /**
     * Мок InjectGraph.
     *
     * @return mixed
     */
    private function getMockInjectGraph()
    {
        $mock = Mockery::mock(InjectGraph::class);
        $mock = $mock->shouldReceive('inject')->once();

        return $mock->getMock();
    }
}
