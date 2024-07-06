<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaksi_detail".
 *
 * @property int $id
 * @property int|null $idtransaksi
 * @property int|null $idrawat
 * @property int|null $idkunjungan
 * @property int|null $idpelayanan
 * @property string|null $tgl
 * @property string|null $nama
 * @property float|null $tarif
 * @property float|null $jumlah
 * @property float|null $total
 * @property int|null $jenis
 * @property string|null $nama_tindakan
 * @property int|null $idtindakan
 * @property int|null $idbayar
 * @property int|null $idjenispelayanan
 * @property int|null $status
 *
 * @property Transaksi $idtransaksi0
 */
class TransaksiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	public $jml;
    public static function tableName()
    {
        return 'transaksi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtransaksi', 'idrawat', 'idkunjungan', 'idpelayanan', 'jenis', 'idtindakan', 'idbayar', 'idjenispelayanan', 'status','idraw','iddokter','persentase_dokter'], 'integer'],
            [['tgl'], 'safe'],
            [['tarif', 'jumlah', 'total'], 'number'],
            [['nama'], 'string', 'max' => 150],
            [['nama_tindakan'], 'string', 'max' => 50],
            [['idtransaksi'], 'exist', 'skipOnError' => true, 'targetClass' => Transaksi::className(), 'targetAttribute' => ['idtransaksi' => 'id']],
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
            'idrawat' => 'Idrawat',
            'idkunjungan' => 'Idkunjungan',
            'idpelayanan' => 'Idpelayanan',
            'tgl' => 'Tgl',
            'nama' => 'Nama',
            'tarif' => 'Tarif',
            'jumlah' => 'Jumlah',
            'total' => 'Total',
            'jenis' => 'Jenis',
            'nama_tindakan' => 'Nama Tindakan',
            'idtindakan' => 'Idtindakan',
            'idbayar' => 'Idbayar',
            'idjenispelayanan' => 'Idjenispelayanan',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Idtransaksi0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtransaksi0()
    {
        return $this->hasOne(Transaksi::className(), ['id' => 'idtransaksi']);
    }
	public function getTindakan()
    {
        return $this->hasOne(Tindakan::className(), ['id' => 'idpelayanan']);
    }
	public function getUnit()
    {
        return $this->hasOne(UserUnit::className(), ['id' => 'idjenispelayanan']);
    }
}
