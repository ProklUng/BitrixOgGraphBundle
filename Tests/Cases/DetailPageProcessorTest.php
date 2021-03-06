<?php

namespace Prokl\BitrixOgGraphBundle\Tests\Cases;

use CFile;
use CIBlockElement;
use Mockery;
use Prokl\BitrixOgGraphBundle\Services\DetailPageProcessor;
use Prokl\BitrixOgGraphBundle\Services\Utils\CFileWrapper;
use Prokl\BitrixTestingTools\Base\BitrixableTestCase;
use Prokl\BitrixTestingTools\Mockers\MockerBitrixBlocks;
use Prokl\BitrixTestingTools\Mockers\MockerBitrixSeo;
use Prokl\BitrixTestingTools\Traits\CustomDumpTrait;
use Prokl\BitrixTestingTools\Traits\ResetDatabaseTrait;
use Prokl\BitrixTestingTools\Traits\SprintMigrationsTrait;
use Prokl\BitrixTestingTools\Traits\UseMigrationsTrait;
use Psr\Cache\InvalidArgumentException;
use WebArch\BitrixCache\AntiStampedeCacheAdapter;

/**
 * Class DetailPageProcessorTest
 * @package Prokl\BitrixOgGraphBundle\Tests
 * @coversDefaultClass DetailPageProcessor
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @since 20.02.20201
 */
class DetailPageProcessorTest extends BitrixableTestCase
{
   // use ResetDatabaseTrait;
   // use CustomDumpTrait;
   // use UseMigrationsTrait;
   use SprintMigrationsTrait;

    /**
     * @var DetailPageProcessor $obTestObject
     */
    protected $obTestObject;

    protected function getDumpPath() : string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/Tests/dump/loc.sql';
    }

    protected function getMigrationsDir() : string
    {
        return __DIR__ . '/../migrations';
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $mockCIBlockElement = new MockerBitrixBlocks(CIBlockElement::class);
        $mockCIBlockElement->setFixture([
            [
                'ID' => 22,
                'NAME' => 'test name',
                'PREVIEW_TEXT' => 'test description',
                'PREVIEW_PICTURE' => null,
                'TIMESTAMP_X' => '',
                'DETAIL_PAGE_URL' => '/test/url/',
            ],
        ]);

        $this->obTestObject = new DetailPageProcessor(
            $mockCIBlockElement->mock(),
            new CFileWrapper(new CFile()),
            new AntiStampedeCacheAdapter(
                '/', 0, '/cache/s1/test/'
            )
        );
    }

    /**
     * go(). Default values.
     *
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGo(): void
    {
        $result = $this->obTestObject->go();

        $this->assertSame('test name', $result['title'], 'Title ???? ??????????????????.');
        $this->assertSame('test description', $result['description'], 'Description ???? ??????????????????.');
        $this->assertSame('article', $result['type'], 'Type ???? ??????????????????.');
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
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGoFromSeoProperty(): void
    {
        $mockerBitrixSeo = new MockerBitrixSeo(
            [
                'ELEMENT_META_TITLE' => [
                    'VALUE' => 'test SEO title',
                ],
                'ELEMENT_META_DESCRIPTION' => [
                    'VALUE' => 'test SEO description',
                ],
            ]
        );

        $mockerBitrixSeo->mockElementValues();

        $result = $this->obTestObject->go();

        $this->assertSame('test SEO title', $result['title'], 'Title ???? ??????????????????.');
        $this->assertSame('test SEO description', $result['description'], 'Description ???? ??????????????????.');
        $this->assertSame('article', $result['type'], 'Type ???? ??????????????????.');
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
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function testGoCutMaxLength(): void
    {
        $mockerBitrixSeo = new MockerBitrixSeo(
            [
                'ELEMENT_META_DESCRIPTION' => [
                    'VALUE' => $this->faker->text(400),
                ],
            ]
        );

        $mockerBitrixSeo->mockElementValues();

        $result = $this->obTestObject->go();

        $this->assertSame(
            200,
            strlen($result['description']),
            '?????????????? ???????????? description ???? ????????????????????.'
        );
    }
}
