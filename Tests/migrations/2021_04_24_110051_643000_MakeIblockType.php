<?php

use Arrilot\BitrixMigrationsFork\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrationsFork\Exceptions\MigrationException;

class MakeIblockType20210424110051643000 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblocktype = "content";

        $obIBlockType =  new \CIBlockType;
        $arFields = Array(
            "ID"=>$iblocktype,
            "SECTIONS"=>"Y",
            "LANG"=>Array(
                "ru"=>Array(
                    "NAME"=>"Контент",
                )
            )
        );
        $res = $obIBlockType->Add($arFields);
        if(!$res){
            throw new MigrationException('Ошибка при добавлении типа инфоблока '.$obIBlockType->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
