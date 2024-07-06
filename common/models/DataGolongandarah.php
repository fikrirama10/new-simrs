<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_golongandarah".
 *
 * @property int $id
 * @property string|null $golongan_darah
 *
 * @property Pasien[] $pasiens
 */
class DataGolongandarah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_golongandarah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['golongan_darah'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'golongan_darah' => 'Golongan Darah',
        ];
    }

    /**
     * Gets query for [[Pasiens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['idgolongan_darah' => 'id']);
    }
}
