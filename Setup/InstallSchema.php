<?php

namespace Magelab\BRCityFreightTable\Setup;

/**
 * Class InstallSchema
 * @package Magelab\BRCityFreightTable\Setup
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     *
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $tableName = 'magelab_brcityfreighttable';
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable($tableName)
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ID'
            )
            ->addColumn(
                'city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'City'
            )
            ->addColumn(
                'uf',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                250,
                [],
                'UF'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                11,
                [],
                'Value'
            )
            ->addColumn(
                'estimative',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                250,
                [],
                'Estimative'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                250,
                [],
                'Title'
            )
            ->addColumn(
                'is_enabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['nullable' => false],
                'Is Enabled?'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Created At'
            )
            ->setComment('Magelab - Brazilian City Freight Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
