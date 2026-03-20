<?php

use yii\db\Migration;

/**
 * Class m180629_133938_user_clabis_change_id
 */
class m180629_133938_user_clabis_change_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_clabis}}', 'token', $this->string()->notNull()->after('tel'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180629_133938_user_clabis_change_id cannot be reverted.\n";
        $this->dropColumn('{{%user_clabis}}', 'token');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180629_133938_user_clabis_change_id cannot be reverted.\n";

        return false;
    }
    */
}
