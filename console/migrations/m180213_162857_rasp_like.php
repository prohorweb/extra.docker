<?php

use yii\db\Migration;

/**
 * Class m180213_162857_rasp_like
 */
class m180213_162857_rasp_like extends Migration
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

        $this->createTable('{{%rasp_like}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'rasp_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'time' => $this->time()->notNull(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%rasp_like}}', 'user_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspLike_userClabis', '{{%rasp_like}}', 'user_id', 'user_clabis', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('rasp_id', '{{%rasp_like}}', 'rasp_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspLike_rasp', '{{%rasp_like}}', 'rasp_id', '{{%rasp}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180213_162857_rasp_like cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspLike_userClabis', '{{%rasp_like}}');
        $this->dropIndex('user_id', '{{%rasp_like}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_raspLike_rasp', '{{%rasp_like}}');
        $this->dropIndex('rasp_id', '{{%rasp_like}}');

        $this->dropTable('{{%rasp_like}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_162857_rasp_like cannot be reverted.\n";

        return false;
    }
    */
}
