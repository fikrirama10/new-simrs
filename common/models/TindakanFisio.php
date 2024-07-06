<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tindakan_fisio".
 *
 * @property int $id
 * @property int|null $idtindakan
 * @property int|null $idrawat
 * @property int|null $iddokter
 * @property string|null $keterangan
 * @property int|null $status
 * @property string|null $tgl
 * @property int|null $idbayar
 */
class TindakanFisio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tindakan_fisio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtindakan', 'idrawat', 'iddokter', 'status', 'idbayar','iddokterpeminta'], 'integer'],
            [['keterangan'], 'string'],
            [['tgl'], 'safe'],
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
            'idrawat' => 'Idrawat',
            'iddokter' => 'Iddokter',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'tgl' => 'Tgl',
            'idbayar' => 'Idbayar',
        ];
    }
		public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	public function getPeminta()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokterpeminta']);
    }
		public function getPemeriksaan()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtindakan']);
    }
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
}
