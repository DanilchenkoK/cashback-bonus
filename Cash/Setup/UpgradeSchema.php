<?php
namespace Kirill\Cash\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            if (!$installer->tableExists('cashback_bonus_history')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('cashback_bonus_history')
                )
                    ->addColumn(
                        'customer_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        '255',
                        ['nullable => false'],
                        'Customer ID'
                    )
                    ->addColumn(
                        'operation',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Operation'
                    )
                    ->addColumn(
                        'remain_coin',
                        \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                        null,
                        [],
                        'Remains Coins'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At')
                    ->setComment('Bonus Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('cashback_bonus_history'),
                    $setup->getIdxName(
                        $installer->getTable('cashback_bonus_history'),
                        ['customer_id', 'operation', 'remain_coin'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['customer_id', 'operation', 'remain_coin'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            $installer->endSetup();
        }
    }
}
