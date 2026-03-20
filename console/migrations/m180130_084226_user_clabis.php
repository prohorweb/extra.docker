<?php

use yii\db\Migration;

/**
 * Class m180130_084226_user_clabis
 */
class m180130_084226_user_clabis extends Migration
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

        $this->createTable('{{%user_clabis}}', [
            'id' => $this->primaryKey(),
            'tel' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180130_084226_user_clabis cannot be reverted.\n";

        $this->dropTable('{{%user_clabis}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180130_084226_user_clabis cannot be reverted.\n";

        return false;
    }
    */
}
