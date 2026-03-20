<?php

use yii\db\Migration;

/**
 * Class m180716_132405_club_club_content
 */
class m180716_132405_club_club_content extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'club_content', $this->text()->after('main_content'));
        $this->addColumn('{{%club_cards_params}}', 'gift_text', $this->text()->after('freezing_text'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180716_132405_club_club_content cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'club_content');
        $this->dropColumn('{{%club_cards_params}}', 'gift_text');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180716_132405_club_club_content cannot be reverted.\n";

        return false;
    }
    */
}
