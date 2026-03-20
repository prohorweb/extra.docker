<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%promo}}`.
 */
class m191216_122918_create_promo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->getDb()->username == 'root' || Yii::$app->getDb()->username == 'balvyandex') {
            $this->createTable('{{%promo}}', [
                'id' => $this->primaryKey(),
                'phone' => $this->string(),
                'img' => $this->string(),
                'text' => $this->text(),
                'text2' => $this->text(),

                'price1_piter' => $this->string(),
                'price3_piter' => $this->string(),
                'price6_piter' => $this->string(),
                'price12_piter' => $this->string(),
                'price1_rodeo' => $this->string(),
                'price3_rodeo' => $this->string(),
                'price6_rodeo' => $this->string(),
                'price12_rodeo' => $this->string(),
                'price1_june' => $this->string(),
                'price3_june' => $this->string(),
                'price6_june' => $this->string(),
                'price12_june' => $this->string(),
                'price1_polis' => $this->string(),
                'price3_polis' => $this->string(),
                'price6_polis' => $this->string(),
                'price12_polis' => $this->string(),
                'price1_matros' => $this->string(),
                'price3_matros' => $this->string(),
                'price6_matros' => $this->string(),
                'price12_matros' => $this->string(),

                'icon1' => $this->string(),
                'icon3' => $this->string(),
                'icon6' => $this->string(),
                'icon12' => $this->string(),
            ]);
        }

        $this->insert('{{%promo}}', [
            'id' => 1,
            'phone' => '+7 812 777-01-00',
            'text' => 'Подарите близким карту на посещения фитнес-клуба<br>Счастливого и здорового Нового Года вам!',
            'text2' => '<p>Дорогие друзья, мы рады вступить в новый год вместе с вами!Мы рады видеть вас всегда!</p><br><p>Поэтому <b>Extra Sport</b> и <b>De-Vision Sport</b> подготовили для вас решение новогодних хлопот.</p><br>
                        <p>Специально для ваших родных и близких вы можете приобрести <b>Подарок на Новый год и Рождество!</b></p><br><p>Подарок будет полностью готов к торжественному вручению. И вам не придётся ломать голову, какую же подарочную упаковку выбрать!</p><br>
                        <p>Наш курьер доставит подарок до вас или ваших близких.</p><br><p>Вам остаётся только выбрать удобное расположение клуба и срок Абонемента.</p><br><p><b>Счастливых праздников!</b></p>',
            'price1_piter' => '4900'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->getDb()->username == 'root' || Yii::$app->getDb()->username == 'balvyandex') {
            $this->dropTable('{{%promo}}');
        }
    }
}
