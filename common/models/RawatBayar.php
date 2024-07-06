<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_bayar".
 *
 * @property int $id
 * @property string|null $bayar
 *
 * @property Rawat[] $rawats
 */
class RawatBayar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_bayar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bayar'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bayar' => 'Bayar',
        ];
    }

    /**
     * Gets query for [[Rawats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawats()
    {
        return $this->hasMany(Rawat::className(), ['idbayar' => 'id']);
    }
}
