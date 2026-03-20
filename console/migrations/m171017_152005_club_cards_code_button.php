<?php

use yii\db\Migration;

class m171017_152005_club_cards_code_button extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%club_cards}}', 'code_button', $this->string()->after('color'));
    }

    public function safeDown()
    {
        //echo "m171017_152005_club_cards_code_button cannot be reverted.\n";
        $this->dropColumn('{{%club_cards}}', 'code_button');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171017_152005_club_cards_code_button cannot be reverted.\n";

        return false;
    }
    */
}
