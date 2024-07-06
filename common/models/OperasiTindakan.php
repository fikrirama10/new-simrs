<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "operasi_tindakan".
 *
 * @property int $id
 * @property int|null $idok
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $tgl
 * @property int|null $idtindakan
 * @property int|null $iddokter
 * @property int|null $idtrx
 * @property int|null $idbayar
 */
class OperasiTindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'operasi_tindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idok', 'idrawat', 'idtindakan', 'iddokter', 'idtrx', 'idbayar','harga_bhp','harga_total','harga_tindakan'], 'integer'],
            [['tgl'], 'safe'],
            [['no_rm','keterangan_tindakan'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idok' => 'Idok',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'tgl' => 'Tgl',
            'idtindakan' => 'Idtindakan',
            'iddokter' => 'Iddokter',
            'idtrx' => 'Idtrx',
            'idbayar' => 'Idbayar',
        ];
    }
	public function getTarif()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtindakan']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	public function getTransaksi()
    {
        return $this->hasOne(Transaksi::className(), ['id' => 'idtrx']);
    }
}
