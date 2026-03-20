<?php

use yii\db\Migration;

/**
 * Class m180605_103559_add_icon
 */
class m180605_103559_add_icon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club_cards}}', 'icon', $this->string()->notNull()->after('comment'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180605_103559_add_icon cannot be reverted.\n";

        $this->dropColumn('{{%club_cards}}', 'icon');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180605_103559_add_icon cannot be reverted.\n";

        return false;
    }
    */
}
