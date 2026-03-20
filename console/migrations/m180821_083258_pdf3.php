<?php

use yii\db\Migration;

/**
 * Class m180821_083258_pdf3
 */
class m180821_083258_pdf3 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'pdf3', $this->string()->after('pdf2'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180821_083258_pdf3 cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'pdf3');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180821_083258_pdf3 cannot be reverted.\n";

        return false;
    }
    */
}
