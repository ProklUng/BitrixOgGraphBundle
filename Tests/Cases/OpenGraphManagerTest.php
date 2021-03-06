<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use Astrotomic\OpenGraph\OpenGraph;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Prokl\BitrixOgGraphBundle\Services\OpenGraphManager;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;

/**
 * Class OpenGraphManagerTest
 * @package Prokl\BitrixOgGraphBundle\Tests
 * @coversDefaultClass OpenGraphManager
 *
 * @since 13.10.2020
 */
class OpenGraphManagerTest extends BitrixableTestCase
{
    /**
     * @var OpenGraphManager $obTestObject
     */
    protected $obTestObject;

    /**
     * @var OgDTO $dtoOpenGraph DTO для теста.
     */
    private $dtoOpenGraph;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->dtoOpenGraph = new ogDTO([]);
    }

    /**
     * Тест движухи.
     *
     * @return void
     */
    public function testGo(): void
    {
        $description = $this->faker->text(400);
        $title = $this->faker->text(100);
        $url = $this->faker->url;
        $img = $this->faker->url;
        $date = date('Y-m-d', $this->faker->unixTime);
        $publisher = $this->faker->sentence;
        $fb_admin = (string)$this->faker->numberBetween(10000, 20000);

        $dtoOpenGraph = new OgDTO(
            [
                'type' => 'article',
                'url' => $url,
                'title' => htmlspecialchars($title),
                'description' => $description,
                'mainDescription' => $description,
                'img' => $img,
                'timePublished' => $date,
                'article_publisher' => $publisher,
                'fb_admins' => $fb_admin,
            ]
        );

        $this->obTestObject = new OpenGraphManager(
            new OpenGraph()
        );

        $this->obTestObject->setDto($dtoOpenGraph);
        $result = $this->obTestObject->go();

        $this->assertNotEmpty(
            $result,
            'Пришел пустой ответ.'
        );

        foreach ([
                     'article',
                     $url,
                     htmlspecialchars($title),
                     $description,
                     $img,
                     $date,
                     $publisher,
                     $fb_admin
                 ] as $item) {
            $this->assertStringContainsString(
                $item,
                $result
            );
        }
    }

    /**
     * Тест движухи. Обработка пустых параметров.
     *
     * @return void
     */
    public function testGoEmptyParams(): void
    {
        $dtoOpenGraph = new OgDTO(
            [
                'type' => 'article',
                'url' => '',
                'title' => '',
                'description' => '',
                'mainDescription' => '',
                'img' => '',
            ]
        );

        $this->obTestObject = new OpenGraphManager(
            new OpenGraph()
        );

        $this->obTestObject->setDto($dtoOpenGraph);
        $result = $this->obTestObject->go();

        $this->checkNotContainsInArray(
        [
            'og:image',
            'og:site_name',
            'og:description',
            'og:title',
            'og:url',
        ],
        $result
    );
    }

    /**
     * Конструктор и результат на параметры по умолчанию.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->obTestObject = new OpenGraphManager(
            new OpenGraph()
        );

        $this->obTestObject->setDto($this->dtoOpenGraph);
        $result = $this->obTestObject->go();

        $this->assertNotEmpty(
            $result,
            'Пришел пустой ответ.'
        );

        $this->checkDefaultFields($this->dtoOpenGraph, $result);
    }

    /**
     * Проверка полей по умолчанию.
     *
     * @param OgDTO $ogDTO
     * @param mixed $result
     *
     * @return void
     */
    private function checkDefaultFields(OgDTO $ogDTO, $result): void
    {
        $this->assertStringContainsString(
            $ogDTO->fb_admins,
            $result,
            'Неправильный fb:admins'
        );

        $this->assertStringContainsString(
            $ogDTO->article_publisher,
            $result,
            'Неправильный article_publisher'
        );

        $this->assertStringContainsString(
            $ogDTO->type,
            $result,
            'Неправильный og:type'
        );

        $this->checkNotContainsInArray(
            [
                'og:image',
                'og:site_name',
            ],
            $result
        );
    }

    /**
     * @param array $array
     * @param mixed $value
     *
     * @return void
     */
    private function checkNotContainsInArray(array $array, $value): void
    {
        foreach ($array as $item) {
            $this->assertStringNotContainsString(
                $item,
                $value,
                'Присутствует, а не должен: '.$item
            );
        }
    }
}
