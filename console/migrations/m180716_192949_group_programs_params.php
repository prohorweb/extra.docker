<?php

use yii\db\Migration;

/**
 * Class m180716_192949_group_programs_params
 */
class m180716_192949_group_programs_params extends Migration
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

        $this->createTable('{{%group_programs_params}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
        ], $tableOptions);

        $this->insert('{{%group_programs_params}}', [
            'id' => 1
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180716_192949_group_programs_params cannot be reverted.\n";
        $this->dropTable('{{%group_programs_params}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180716_192949_group_programs_params cannot be reverted.\n";

        return false;
    }
    */
}
