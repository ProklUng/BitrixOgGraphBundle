<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use Bitrix\Main\Application;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Prokl\BitrixOgGraphBundle\Services\StaticPageProcessor;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;
use Psr\Cache\InvalidArgumentException;
use WebArch\BitrixCache\AntiStampedeCacheAdapter;

/**
 * Class StaticPageProcessorTest
 * @package Prokl\BitrixOgGraphBundle\Tests\Cases
 * @coversDefaultClass StaticPageProcessor
 *
 * @since 20.02.20201
 */
class StaticPageProcessorTest extends BitrixableTestCase
{
    /**
     * @var StaticPageProcessor $obTestObject
     */
    protected $obTestObject;

    /**
     * @var OgDTO $dtoOpenGraph DTO для теста.
     */
    private $dtoOpenGraph;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dtoOpenGraph = new ogDTO([]);
        $this->obTestObject = new StaticPageProcessor(
            $_SERVER['DOCUMENT_ROOT'],
            Application::getInstance(),
            new AntiStampedeCacheAdapter(
                '/', 0, '/cache/s1/test/'
            )
        );
    }

    /**
     * go().
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGo(): void
    {
        $GLOBALS['APPLICATION']->SetPageProperty('title', 'test title');
        $GLOBALS['APPLICATION']->SetPageProperty('description', 'test description');

        $result = $this->obTestObject->go();

        $GLOBALS['APPLICATION']->RestartBuffer();

        $this->assertEmpty(
            $result['timePublished'],
            'Левый timePublished.'
        );

        $this->assertSame(
            'test title',
            $result['title'],
            'Не обработан title.'
        );

        $this->assertSame(
            'test description',
            $result['description'],
            'Не обработан description.'
        );

        $this->assertSame(
            'website',
            $result['type'],
            'Не обработан type.'
        );
    }

    /**
     * go(). Проверка обработки пустых значений.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGoEmptyValues(): void
    {
        $GLOBALS['APPLICATION']->SetPageProperty('title', '');
        $GLOBALS['APPLICATION']->SetPageProperty('description', '');

        $result = $this->obTestObject->go();

        $this->assertSame(
            '',
            $result['title'],
            'Обработан пустой title.'
        );

        $this->assertSame(
            '',
            $result['description'],
            'Обработан пустой description.'
        );

        $this->assertSame(
            'website',
            $result['type'],
            'Обнулился type.'
        );
    }
}
