<?php

use yii\db\Migration;

/**
 * Class m180610_114939_pdf2
 */
class m180610_114939_pdf2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'pdf2', $this->string()->after('pdf'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180610_114939_pdf2 cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'pdf2');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180610_114939_pdf2 cannot be reverted.\n";

        return false;
    }
    */
}
