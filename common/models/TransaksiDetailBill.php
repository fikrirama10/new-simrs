<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi_detail_bill".
 *
 * @property int $id
 * @property int|null $idtransaksi
 * @property string|null $tindakan
 * @property string|null $idrawat
 * @property float|null $tarif
 * @property int|null $idbayar
 * @property int|null $iddokter
 * @property int|null $idtarif
 */
class TransaksiDetailBill extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi_detail_bill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtransaksi', 'idbayar', 'iddokter', 'idtarif','jumlah','hide'], 'integer'],
            [['tarif'], 'number'],
            [['tindakan', 'idrawat'], 'string', 'max' => 50],
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
            'tindakan' => 'Tindakan',
            'idrawat' => 'Idrawat',
            'tarif' => 'Tarif',
            'idbayar' => 'Idbayar',
            'iddokter' => 'Iddokter',
            'idtarif' => 'Idtarif',
        ];
    }
	public function getTarif()
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
}
