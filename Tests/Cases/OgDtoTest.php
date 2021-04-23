<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use Faker\Factory;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;

/**
 * Class OgDtoTest
 * @package Prokl\BitrixOgGraphBundle\Tests
 *
 * @since 21.02.2021
 */
class OgDtoTest extends BitrixableTestCase
{
    /**
     * @var ogDTO $obTestObject
     */
    protected $obTestObject;

    /**
     * @var array $fixture
     */
    private $fixture;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();
        $this->fixture = [
            'url' => $faker->url,
            'title' => $faker->sentence,
            'img' => $faker->url,
            'description' => $faker->sentence(22),
            'site_name' => $faker->sentence(2),
            'type' => 'website',
            'timePublished' => $faker->date(),
            'fb_admins' => $faker->numerify(),
            'article_publisher' => $faker->name(),
        ];

        $this->obTestObject = new ogDTO([]);
    }

    /**
     * update().
     *
     * @return void
     */
    public function testUpdate() : void
    {
        $this->obTestObject->update($this->fixture);

        $result = $this->obTestObject->toArray();
        foreach ($this->fixture as $key => $item) {
            $this->assertSame(
                $item,
                $result[$key],
                'Ключ ' . $key . ' обработан неправильно.'
            );
        }
    }
}
