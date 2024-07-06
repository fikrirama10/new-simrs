<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi_detail_rinci".
 *
 * @property int $id
 * @property int|null $idtransaksi
 * @property int|null $idtarif
 * @property int|null $tarif
 * @property int|null $idpaket
 * @property int|null $iddokter
 */
class TransaksiDetailRinci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi_detail_rinci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtransaksi', 'idtarif', 'tarif', 'idpaket', 'iddokter','idbayar','idjenis','jumlah'], 'integer'],
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
            'idtransaksi' => 'Idtransaksi',
            'idtarif' => 'Idtarif',
            'tarif' => 'Tarif',
            'idpaket' => 'Idpaket',
            'iddokter' => 'Iddokter',
        ];
    }
	public function getTindakan()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtarif']);
    }
	public function getTransaksi()
    {
        return $this->hasOne(Transaksi::className(), ['id' => 'idtransaksi']);
    }
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
}
