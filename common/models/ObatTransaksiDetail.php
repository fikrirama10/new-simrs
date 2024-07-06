<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_transaksi_detail".
 *
 * @property int $id
 * @property int|null $idtrx
 * @property int|null $idobat
 * @property int|null $idbatch
 * @property int|null $qty
 * @property float|null $harga
 * @property int|null $idsatuan
 * @property float|null $total
 * @property int|null $idtransaksi
 * @property string|null $dosis
 * @property string|null $keterangan
 */
class ObatTransaksiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_transaksi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtrx', 'idobat', 'idbatch', 'qty', 'idsatuan', 'idtransaksi','idbayar','tuslah','keuntungan'], 'integer'],
            [['harga', 'total'], 'number'],
            [['dosis', 'keterangan','diminum','takaran','signa'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtrx' => 'Idtrx',
            'idobat' => 'Idobat',
            'idbatch' => 'Idbatch',
            'qty' => 'Qty',
            'harga' => 'Harga',
            'idsatuan' => 'Idsatuan',
            'total' => 'Total',
            'idtransaksi' => 'Idtransaksi',
            'dosis' => 'Dosis',
            'keterangan' => 'Keterangan',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getTransaksi()
    {
        return $this->hasOne(Transaksi::className(), ['id' => 'idtransaksi']);
    }
	public function getTransaksiobat()
    {
        return $this->hasOne(ObatTransaksi::className(), ['id' => 'idtrx']);
    }
	public function getJenis()
    {
        return $this->hasOne(TransaksiBayar::className(), ['id' => 'idbayar']);
    }
	public function getBacth()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbatch']);
    }
}
