<?php

use yii\db\Migration;

/**
 * Class m180412_154745_settings_code3
 */
class m180412_154745_settings_code3 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'form_title', $this->string()->after('code_club_card'));
        $this->addColumn('{{%settings}}', 'form_text', $this->text()->after('form_title'));

        $this->update('{{%settings}}', ['form_title' => 'ЗАПИШИТЕСЬ НА ПРОБНОЕ ЗАНЯТИЕ', 'form_text' => 'Если вы еще никогда не были в Клубе, отправьте заявку на гостевой визит и мы подарим вам целый день здоровья и удовольствия!']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180412_154745_settings_code3 cannot be reverted.\n";

        $this->dropColumn('{{%settings}}', 'form_title');
        $this->dropColumn('{{%settings}}', 'form_text');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_154745_settings_code3 cannot be reverted.\n";

        return false;
    }
    */
}
