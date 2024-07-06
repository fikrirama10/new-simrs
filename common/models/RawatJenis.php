<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_jenis".
 *
 * @property int $id
 * @property string|null $jenis
 *
 * @property Rawat[] $rawats
 */
class RawatJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis','ket'], 'string', 'max' => 50],
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
     * Gets query for [[Rawats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawats()
    {
        return $this->hasMany(Rawat::className(), ['idjenisrawat' => 'id']);
    }
}
