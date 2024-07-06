<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi_pelayanan".
 *
 * @property int $id
 * @property int|null $idrad
 * @property string|null $nama_pelayanan
 *
 * @property Radiologi $idrad0
 * @property RadiologiTindakan[] $radiologiTindakans
 */
class RadiologiPelayanan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi_pelayanan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrad'], 'integer'],
            [['nama_pelayanan'], 'string', 'max' => 150],
            [['idrad'], 'exist', 'skipOnError' => true, 'targetClass' => Radiologi::className(), 'targetAttribute' => ['idrad' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrad' => 'Idrad',
            'nama_pelayanan' => 'Nama Pelayanan',
        ];
    }

    /**
     * Gets query for [[Idrad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrad0()
    {
        return $this->hasOne(Radiologi::className(), ['id' => 'idrad']);
    }

    /**
     * Gets query for [[RadiologiTindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadiologiTindakans()
    {
        return $this->hasMany(RadiologiTindakan::className(), ['idpelayanan' => 'id']);
    }
}
