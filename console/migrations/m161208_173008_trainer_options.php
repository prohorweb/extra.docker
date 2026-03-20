<?php

use yii\db\Migration;

class m161208_173008_trainer_options extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%trainer_options}}', [
            'id' => $this->primaryKey(),
            'trainer_id' => $this->integer()->notNull(),
            'option_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('trainer_id', '{{%trainer_options}}', 'trainer_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_trainerOptions_trainer', '{{%trainer_options}}', 'trainer_id', '{{%trainers}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('option_id', '{{%trainer_options}}', 'option_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_trainerOptions_trainerOptionsType', '{{%trainer_options}}', 'option_id', '{{%trainer_options_type}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        //echo "m161208_172908_trainer_options cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_trainerOptions_trainer', '{{%trainer_options}}');
        $this->dropIndex('trainer_id', '{{%trainer_options}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_trainerOptions_trainerOptionsType', '{{%trainer_options}}');
        $this->dropIndex('option_id', '{{%trainer_options}}');

        $this->dropTable('{{%trainer_options}}');

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
