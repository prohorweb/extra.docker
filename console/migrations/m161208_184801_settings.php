<?php

use yii\db\Migration;

class m161208_184801_settings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'key' => $this->string(),
            'email_from' => $this->string(),
            'email_form_guest' => $this->string(),
            'email_form_visit' => $this->string(),
            'email_request_training' => $this->string(),
            'email_request_freezing' => $this->string(),
            //'email_club_card' => $this->string(),
            'email_feedback' => $this->string(),
            'yandex_metrica' => $this->text(),
            'file_yandex' => $this->string(),
            'google_analytics' => $this->text(),
            'file_google' => $this->string(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
        ], $tableOptions);

        $this->insert('{{%settings}}', [
            'id' => 1
        ]);
    }

    public function down()
    {
        //echo "m161208_184801_settings cannot be reverted.\n";
        $this->dropTable('{{%settings}}');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
