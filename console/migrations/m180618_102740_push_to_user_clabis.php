<?php

use yii\db\Migration;

/**
 * Class m180618_102740_push_to_user_clabis
 */
class m180618_102740_push_to_user_clabis extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_clabis}}', 'perstrain', $this->boolean()->notNull()->defaultValue(1)->after('tel'));
        $this->addColumn('{{%user_clabis}}', 'enterexit', $this->boolean()->notNull()->defaultValue(1)->after('perstrain'));
        $this->addColumn('{{%user_clabis}}', 'endcard', $this->boolean()->notNull()->defaultValue(1)->after('enterexit'));
        $this->addColumn('{{%user_clabis}}', 'schedule', $this->boolean()->notNull()->defaultValue(1)->after('endcard'));
        $this->addColumn('{{%user_clabis}}', 'info', $this->boolean()->notNull()->defaultValue(1)->after('schedule'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180618_102740_push_to_user_clabis cannot be reverted.\n";
        $this->dropColumn('{{%user_clabis}}', 'perstrain');
        $this->dropColumn('{{%user_clabis}}', 'enterexit');
        $this->dropColumn('{{%user_clabis}}', 'endcard');
        $this->dropColumn('{{%user_clabis}}', 'schedule');
        $this->dropColumn('{{%user_clabis}}', 'info');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180618_102740_push_to_user_clabis cannot be reverted.\n";

        return false;
    }
    */
}
