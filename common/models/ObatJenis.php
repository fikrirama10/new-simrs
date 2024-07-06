<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_jenis".
 *
 * @property int $id
 * @property string|null $jenis
 *
 * @property Obat[] $obats
 */
class ObatJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis' => 'Jenis',
        ];
    }

    /**
     * Gets query for [[Obats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObats()
    {
        return $this->hasMany(Obat::className(), ['idjenis' => 'id']);
    }
}
