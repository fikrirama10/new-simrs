<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi_hasilfoto".
 *
 * @property int $id
 * @property int|null $idhasil
 * @property string|null $foto
 * @property string|null $nofoto
 *
 * @property RadiologiHasildetail $idhasil0
 */
class RadiologiHasilfoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi_hasilfoto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idhasil'], 'integer'],
            [['foto'], 'string', 'max' => 250],
            [['nofoto'], 'string', 'max' => 50],
            [['idhasil'], 'exist', 'skipOnError' => true, 'targetClass' => RadiologiHasildetail::className(), 'targetAttribute' => ['idhasil' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idhasil' => 'Idhasil',
            'foto' => 'Foto',
            'nofoto' => 'Nofoto',
        ];
    }

    /**
     * Gets query for [[Idhasil0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdhasil0()
    {
        return $this->hasOne(RadiologiHasildetail::className(), ['id' => 'idhasil']);
    }
}
