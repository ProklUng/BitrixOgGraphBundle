<?php

namespace Prokl\BitrixOgGraphBundle\Services\Facades;

use Prokl\BitrixOgGraphBundle\Services\DetailPageProcessor;
use Prokl\BitrixOgGraphBundle\Services\InjectGraph;
use Prokl\BitrixOgGraphBundle\Services\OgDTO;
use Psr\Cache\InvalidArgumentException;

/**
 * Class FacadeOgGraphDetailPage
 * @package Prokl\BitrixOgGraphBundle\Services\Facades
 *
 * @since 19.02.2021
 */
class FacadeOgGraphDetailPage
{
    /**
     * @var DetailPageProcessor $detailProcessor Процессор элементов.
     */
    private $detailProcessor;

    /**
     * @var OgDTO $ogDTO DTO.
     */
    private $ogDTO;

    /**
     * @var InjectGraph $injector Процессор элементов.
     */
    private $injector;

    /**
     * FacadeOgGraphDetailPage constructor.
     *
     * @param DetailPageProcessor $detailProcessor Процессор элементов.
     * @param InjectGraph         $injectGraph     Инжектор.
     * @param OgDTO               $ogDTO           DTO.
     */
    public function __construct(
        DetailPageProcessor $detailProcessor,
        InjectGraph $injectGraph,
        OgDTO $ogDTO
    ) {
        $this->detailProcessor = $detailProcessor;
        $this->injector = $injectGraph;
        $this->ogDTO = $ogDTO;
    }

    /**
     * @param integer $iblockId  ID инфоблока.
     * @param integer $idElement ID элемента.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function make(int $iblockId, int $idElement): void
    {
        $data = $this->detailProcessor->setIblockId($iblockId)
                                      ->setIdElement($idElement)
                                      ->go();

        $this->ogDTO->update($data);

        $this->injector->inject($this->ogDTO);
    }
}
