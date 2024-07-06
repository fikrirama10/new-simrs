<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_agama".
 *
 * @property int $id
 * @property string|null $agama
 *
 * @property Pasien[] $pasiens
 */
class DataAgama extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_agama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agama'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agama' => 'Agama',
        ];
    }

    /**
     * Gets query for [[Pasiens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['idagama' => 'id']);
    }
}
