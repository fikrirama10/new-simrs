<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jenispenerimaan_detail".
 *
 * @property int $id
 * @property int|null $idpenerimaan
 * @property string|null $namapenerimaan
 *
 * @property Jenispenerimaan $idpenerimaan0
 * @property Tindakan[] $tindakans
 */
class JenispenerimaanDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenispenerimaan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpenerimaan'], 'integer'],
            [['namapenerimaan'], 'string', 'max' => 50],
            [['idpenerimaan'], 'exist', 'skipOnError' => true, 'targetClass' => Jenispenerimaan::className(), 'targetAttribute' => ['idpenerimaan' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpenerimaan' => 'Idpenerimaan',
            'namapenerimaan' => 'Namapenerimaan',
        ];
    }

    /**
     * Gets query for [[Idpenerimaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpenerimaan0()
    {
        return $this->hasOne(Jenispenerimaan::className(), ['id' => 'idpenerimaan']);
    }

    /**
     * Gets query for [[Tindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTindakans()
    {
        return $this->hasMany(Tindakan::className(), ['idjenispenerimaan' => 'id']);
    }
}
