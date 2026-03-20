<?php

use yii\db\Migration;

class m161208_172426_club_cards_params extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%club_cards_params}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text(),
            'freezing_text' => $this->text(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
        ], $tableOptions);

        $this->insert('{{%club_cards_params}}', [
            'id' => 1
        ]);

    }

    public function down()
    {
        //echo "m161208_172324_club_cards cannot be reverted.\n";
        $this->dropTable('{{%club_cards_params}}');

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
