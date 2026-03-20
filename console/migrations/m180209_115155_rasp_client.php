<?php

use yii\db\Migration;

/**
 * Class m180209_115155_rasp_client
 */
class m180209_115155_rasp_client extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%rasp_client}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'time' => $this->time()->notNull(),
            'trainer_id' => $this->integer(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('trainer_id', '{{%rasp_client}}', 'trainer_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspClient_trainers', '{{%rasp_client}}', 'trainer_id', '{{%trainers}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('user_id', '{{%rasp_client}}', 'user_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspClient_userClabis', '{{%rasp_client}}', 'user_id', '{{%user_clabis}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180209_115155_client_rasp cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspClient_trainers', '{{%rasp_client}}');
        $this->dropIndex('trainer_id', '{{%rasp_client}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspClient_userClabis', '{{%rasp_client}}');
        $this->dropIndex('user_id', '{{%rasp_client}}');

        $this->dropTable('{{%rasp_client}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180209_115155_client_rasp cannot be reverted.\n";

        return false;
    }
    */
}
