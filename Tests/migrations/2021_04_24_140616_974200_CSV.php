<?php

use Arrilot\BitrixMigrationsFork\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrationsFork\Traits\CSVTrait;
use Arrilot\BitrixMigrationsFork\Exceptions\MigrationException;

class CSV20210424140616974200 extends BitrixMigration
{
    use CSVTrait;

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->importCsv();
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

    /**
     * @return string
     */
    protected function getIblockCode() {
        return 'common';
    }

    /**
     * @return array
     */
    protected function getImportDefinitionSections() {
        return [
            18
        ];
    }

    /**
     * @return array
     */
    protected function getImportDefinitionProperties() {
        return [

        ];
    }

    /**
     * @return string
     */
    protected function getCsvPath() {
        return __DIR__ . '/../csv/common.csv';
    }
}
