<?php

use yii\db\Migration;

/**
 * Class m180522_151919_metro_partners
 */
class m180522_151919_metro_partners extends Migration
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

        $this->createTable('{{%metro_partners}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'position' => $this->integer()->notNull(),
            'partners_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('partners_id', '{{%metro_partners}}', 'partners_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_metroPartners_partners', '{{%metro_partners}}', 'partners_id', '{{%partners}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180522_151919_metro_partners cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_metroPartners_partners', '{{%metro_partners}}');
        $this->dropIndex('partners_id', '{{%metro_partners}}');

        $this->dropTable('{{%metro_partners}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180522_151919_metro_partners cannot be reverted.\n";

        return false;
    }
    */
}
