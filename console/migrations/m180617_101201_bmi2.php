<?php

use yii\db\Migration;

/**
 * Class m180617_101201_bmi2
 */
class m180617_101201_bmi2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bmi}}', 'height', $this->integer()->notNull()->after('bmi'));
        $this->addColumn('{{%bmi}}', 'weight', $this->integer()->notNull()->after('height'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180617_101201_bmi2 cannot be reverted.\n";
        $this->dropColumn('{{%bmi}}', 'height');
        $this->dropColumn('{{%bmi}}', 'weight');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180617_101201_bmi2 cannot be reverted.\n";

        return false;
    }
    */
}
