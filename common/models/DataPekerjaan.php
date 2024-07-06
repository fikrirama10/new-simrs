<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_pekerjaan".
 *
 * @property int $id
 * @property string|null $pekerjaan
 *
 * @property Pasien[] $pasiens
 */
class DataPekerjaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pekerjaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pekerjaan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pekerjaan' => 'Pekerjaan',
        ];
    }

    /**
     * Gets query for [[Pasiens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['idpekerjaan' => 'id']);
    }
}
