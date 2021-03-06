<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use CFile;
use CIBlockSection;
use Faker\Factory;
use Faker\Generator;
use Prokl\BitrixOgGraphBundle\Services\SectionsProcessor;
use Prokl\BitrixOgGraphBundle\Services\Utils\CFileWrapper;
use Prokl\BitrixTestingTools\Mockers\MockerBitrixBlocks;
use Prokl\BitrixTestingTools\Mockers\MockerBitrixSeo;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use WebArch\BitrixCache\AntiStampedeCacheAdapter;

/**
 * Class SectionsProcessorTest
 * @package Prokl\BitrixOgGraphBundle\Tests\Cases
 * @coversDefaultClass SectionsProcessor
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @since 20.02.20201
 */
class SectionsProcessorTest extends TestCase
{
    /**
     * @var SectionsProcessor $obTestObject
     */
    private $obTestObject;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        Mockery::resetContainer();
        parent::setUp();
        $this->faker = Factory::create();

        $mockCIBlockSection = new MockerBitrixBlocks(CIBlockSection::class);
        $mockCIBlockSection->setFixture([
            'ID' => 22,
            'NAME' => 'test name',
            'DESCRIPTION' => 'test description',
            'PICTURE' => null,
            'TIMESTAMP_X' => '',
            'SECTION_PAGE_URL' => '/test/url/',
        ]);

        $this->obTestObject = new SectionsProcessor(
            $mockCIBlockSection->mockSection(),
            new CFileWrapper(new CFile()),
            new AntiStampedeCacheAdapter(
                '/', 0, '/cache/s1/test/'
            )
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
     * go(). Default values.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGo(): void
    {
        $result = $this->obTestObject->go();

        $this->assertSame('test name', $result['title'], 'Title ???? ??????????????????.');
        $this->assertSame('test description', $result['description'], 'Description ???? ??????????????????.');
        $this->assertSame('website', $result['type'], 'Type ???? ??????????????????.');
        $this->assertEmpty($result['img'], '????????????-???? ???????????????????????? ????????????????.');

        $this->assertStringContainsString(
            '/test/url/',
            $result['url'],
            'URL ???? ??????????????????.'
        );

    }

    /**
     * go(). SEO properties.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGoFromSeoProperty(): void
    {
        $mockerBitrixSeo = new MockerBitrixSeo(
            [
                'SECTION_META_TITLE' => [
                    'VALUE' => 'test SEO title',
                ],
                'SECTION_META_DESCRIPTION' => [
                    'VALUE' => 'test SEO description',
                ],
            ]
        );

        $mockerBitrixSeo->mockSectionValues();

        $result = $this->obTestObject->go();

        $this->assertSame('test SEO title', $result['title'], 'Title ???? ??????????????????.');
        $this->assertSame('test SEO description', $result['description'], 'Description ???? ??????????????????.');
        $this->assertSame('website', $result['type'], 'Type ???? ??????????????????.');
        $this->assertEmpty($result['img'], '????????????-???? ???????????????????????? ????????????????.');

        $this->assertStringContainsString(
            '/test/url/',
            $result['url'],
            'URL ???? ??????????????????.'
        );
    }

    /**
     * ?????? ???????????????????????????? ?????????? description.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGoCutMaxLength(): void
    {
        $mockerBitrixSeo = new MockerBitrixSeo(
            [
                'SECTION_META_DESCRIPTION' => [
                    'VALUE' => $this->faker->text(400),
                ],
            ]
        );

        $mockerBitrixSeo->mockSectionValues();

        $result = $this->obTestObject->go();

        $this->assertSame(
            200,
            strlen($result['description']),
            '?????????????? ???????????? description ???? ????????????????????.'
        );
    }
}
