<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shares}}`.
 */
class m220303_202950_add_only_url_columns_to_shares_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shares}}', 'only_url', $this->boolean()->defaultValue(0)->after('position'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shares}}', 'only_url');
    }
}
