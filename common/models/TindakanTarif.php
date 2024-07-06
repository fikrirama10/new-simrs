<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tindakan_tarif".
 *
 * @property int $id
 * @property int|null $idtindakan
 * @property string|null $tarif
 * @property int|null $idbayar
 *
 * @property Tindakan $idtindakan0
 */
class TindakanTarif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tindakan_tarif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtindakan', 'idbayar','persentase_dokter','idtarif'], 'integer'],
            [['tarif'], 'string', 'max' => 50],
            [['idtindakan'], 'exist', 'skipOnError' => true, 'targetClass' => Tindakan::className(), 'targetAttribute' => ['idtindakan' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtindakan' => 'Idtindakan',
            'tarif' => 'Tarif',
            'idbayar' => 'Idbayar',
        ];
    }

    /**
     * Gets query for [[Idtindakan0]].
     *
     * @return \yii\db\ActiveQuery
     */
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
    public function getTindakans()
    {
        return $this->hasOne(Tindakan::className(), ['id' => 'idtindakan']);
    }
}
