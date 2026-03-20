<?php

use yii\db\Migration;

/**
 * Class m180118_123322_club_title
 */
class m180118_123322_club_title extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'legal_title', $this->string()->after('privacy'));
        $this->addColumn('{{%club}}', 'privacy_title', $this->string()->after('legal_title'));
        $this->addColumn('{{%club}}', 'contacts_title', $this->string()->after('privacy_title'));

        $this->update('{{%club}}', [
            'legal_title' => 'Правовая информация',
            'privacy_title' => 'Политика конфиденциальности',
            'contacts_title' => 'Контакты',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180118_123322_club_title cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'legal_title');
        $this->dropColumn('{{%club}}', 'privacy_title');
        $this->dropColumn('{{%club}}', 'contacts_title');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180118_123322_club_title cannot be reverted.\n";

        return false;
    }
    */
}
