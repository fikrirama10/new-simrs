<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "poli".
 *
 * @property int $id
 * @property string|null $poli
 *
 * @property Dokter[] $dokters
 * @property Rawat[] $rawats
 */
class Poli extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poli';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poli'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poli' => 'Poli',
        ];
    }

    /**
     * Gets query for [[Dokters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDokters()
    {
        return $this->hasMany(Dokter::className(), ['idpoli' => 'id']);
    }

    /**
     * Gets query for [[Rawats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawats()
    {
        return $this->hasMany(Rawat::className(), ['idpoli' => 'id']);
    }
}
