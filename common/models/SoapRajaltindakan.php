<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_rajaltindakan".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $idkunjungan
 * @property int|null $iduser
 * @property int|null $idtindakan
 * @property string|null $tgltindakan
 * @property string|null $keterangan
 */
class SoapRajaltindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_rajaltindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iduser', 'idtindakan','idbayar'], 'integer'],
            [['tgltindakan'], 'safe'],
            [['keterangan'], 'string'],
            [['idkunjungan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'idkunjungan' => 'Idkunjungan',
            'iduser' => 'Iduser',
            'idtindakan' => 'Idtindakan',
            'tgltindakan' => 'Tgltindakan',
            'keterangan' => 'Keterangan',
        ];
    }
	public function getTindakan()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtindakan']);
    }
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
}
