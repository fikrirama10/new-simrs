<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_pendidikan".
 *
 * @property int $id
 * @property string|null $pendidikan
 *
 * @property Pasien[] $pasiens
 */
class DataPendidikan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pendidikan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pendidikan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pendidikan' => 'Pendidikan',
        ];
    }

    /**
     * Gets query for [[Pasiens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['idpendidikan' => 'id']);
    }
}
