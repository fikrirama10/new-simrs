<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi".
 *
 * @property int $id
 * @property string|null $radiologi
 *
 * @property RadiologiPelayanan[] $radiologiPelayanans
 * @property RadiologiTindakan[] $radiologiTindakans
 */
class Radiologi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['radiologi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'radiologi' => 'Radiologi',
        ];
    }

    /**
     * Gets query for [[RadiologiPelayanans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadiologiPelayanans()
    {
        return $this->hasMany(RadiologiPelayanan::className(), ['idrad' => 'id']);
    }

    /**
     * Gets query for [[RadiologiTindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadiologiTindakans()
    {
        return $this->hasMany(RadiologiTindakan::className(), ['idrad' => 'id']);
    }
}
