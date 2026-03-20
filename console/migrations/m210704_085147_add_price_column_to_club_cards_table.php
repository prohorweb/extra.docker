<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%club_cards}}`.
 */
class m210704_085147_add_price_column_to_club_cards_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club_cards}}', 'price', $this->integer()->after('code_button'));
        $this->addColumn('{{%shares}}', 'price', $this->integer()->after('content'));
        $this->addColumn('{{%settings}}', 'email_buy', $this->string()->after('email_feedback'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%club_cards}}', 'price');
        $this->dropColumn('{{%shares}}', 'price');
        $this->dropColumn('{{%settings}}', 'email_buy');
    }
}
