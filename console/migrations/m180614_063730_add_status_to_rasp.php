<?php

use yii\db\Migration;

/**
 * Class m180614_063730_add_status_to_rasp
 */
class m180614_063730_add_status_to_rasp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%rasp}}', 'status', $this->smallInteger()->notNull()->defaultValue(10)->after('id'));
        $this->addColumn('{{%rasp}}', 'last_trainer_id', $this->integer()->after('trainer_id'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180614_063730_add_status_to_rasp cannot be reverted.\n";
        $this->dropColumn('{{%rasp}}', 'status');
        $this->dropColumn('{{%rasp}}', 'last_trainer_id');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180614_063730_add_status_to_rasp cannot be reverted.\n";

        return false;
    }
    */
}
