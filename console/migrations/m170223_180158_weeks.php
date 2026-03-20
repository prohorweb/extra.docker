<?php

use yii\db\Migration;

class m170223_180158_weeks extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%weeks}}', [
            'id' => $this->primaryKey(),
            'week' => $this->integer()->notNull(),
            'year' => $this->integer()->notNull(),
            'type_rasp_id' => $this->integer()->notNull(),
            'is_empty' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);
    }

    public function down()
    {
        //echo "m170223_230158_type_rasp cannot be reverted.\n";
        $this->dropTable('{{%weeks}}');

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
