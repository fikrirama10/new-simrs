<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hari".
 *
 * @property int $id
 * @property int|null $kodehari
 * @property string|null $hari
 * @property string|null $ket
 *
 * @property DokterJadwal[] $dokterJadwals
 */
class Hari extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hari';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kodehari'], 'integer'],
            [['hari', 'ket'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kodehari' => 'Kodehari',
            'hari' => 'Hari',
            'ket' => 'Ket',
        ];
    }

    /**
     * Gets query for [[DokterJadwals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDokterJadwals()
    {
        return $this->hasMany(DokterJadwal::className(), ['idhari' => 'id']);
    }
}
