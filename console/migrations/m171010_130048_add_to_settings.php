<?php

use yii\db\Migration;

class m171010_130048_add_to_settings extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'code_head', $this->string());
        $this->addColumn('{{%settings}}', 'code_body', $this->string());
        $this->addColumn('{{%settings}}', 'code_form_guest', $this->string()->after('email_form_guest'));
        $this->addColumn('{{%settings}}', 'code_request_training', $this->string()->after('email_request_training'));

    }

    public function safeDown()
    {
        //echo "m171010_130048_add_to_settings cannot be reverted.\n";
        $this->dropColumn('{{%settings}}', 'code_head');
        $this->dropColumn('{{%settings}}', 'code_body');
        $this->dropColumn('{{%settings}}', 'code_form_guest');
        $this->dropColumn('{{%settings}}', 'code_request_training');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171010_130048_add_to_settings cannot be reverted.\n";

        return false;
    }
    */
}
