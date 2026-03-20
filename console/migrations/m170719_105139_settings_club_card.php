<?php

use yii\db\Migration;

class m170719_105139_settings_club_card extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'email_club_card', $this->string()->after('email_request_freezing'));
        $this->update('{{%type_rasp}}', ['id' => 0], 'id = 1');
        $this->update('{{%type_rasp}}', ['id' => 1], 'id = 2');
    }

    public function safeDown()
    {
        //echo "m170719_105139_settings_club_card cannot be reverted.\n";
        $this->dropColumn('{{%settings}}', 'email_club_card');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_105139_settings_club_card cannot be reverted.\n";

        return false;
    }
    */
}
