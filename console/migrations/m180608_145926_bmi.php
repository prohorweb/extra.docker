<?php

use yii\db\Migration;

/**
 * Class m180608_145926_bmi
 */
class m180608_145926_bmi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bmi}}', [
            'id' => $this->primaryKey(),
            'bmi' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180608_145926_bmi cannot be reverted.\n";

        $this->dropTable('{{%bmi}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_145926_bmi cannot be reverted.\n";

        return false;
    }
    */
}
