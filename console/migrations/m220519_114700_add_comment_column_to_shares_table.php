<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shares}}`.
 */
class m220519_114700_add_comment_column_to_shares_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shares}}', 'comment', $this->string()->after('title2'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shares}}', 'comment');
    }
}
