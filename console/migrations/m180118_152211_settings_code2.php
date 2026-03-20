<?php

use yii\db\Migration;

/**
 * Class m180118_152211_settings_code
 */
class m180118_152211_settings_code2 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'code_form_visit', $this->string()->after('email_form_visit'));
        $this->addColumn('{{%settings}}', 'code_club_card', $this->string()->after('email_club_card'));
        $this->addColumn('{{%settings}}', 'code_request_freezing', $this->string()->after('email_request_freezing'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180118_152211_settings_code cannot be reverted.\n";
        $this->dropColumn('{{%settings}}', 'code_form_visit');
        $this->dropColumn('{{%settings}}', 'code_club_card');
        $this->dropColumn('{{%settings}}', 'code_request_freezing');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180118_152211_settings_code cannot be reverted.\n";

        return false;
    }
    */
}
