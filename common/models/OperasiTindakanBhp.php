<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "operasi_tindakan_bhp".
 *
 * @property int $id
 * @property int|null $idoperasi
 * @property int|null $idtindakan
 * @property string|null $nama_obat
 * @property int|null $jumlah
 * @property string|null $satuan
 * @property int|null $harga
 * @property int|null $iduser
 * @property int|null $iddokter
 * @property string|null $tgl
 * @property int|null $status
 */
class OperasiTindakanBhp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'operasi_tindakan_bhp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idoperasi', 'idtindakan', 'jumlah', 'harga', 'iduser', 'iddokter', 'status'], 'integer'],
            [['tgl'], 'safe'],
            [['nama_obat', 'satuan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idoperasi' => 'Idoperasi',
            'idtindakan' => 'Idtindakan',
            'nama_obat' => 'Nama Obat',
            'jumlah' => 'Jumlah',
            'satuan' => 'Satuan',
            'harga' => 'Harga',
            'iduser' => 'Iduser',
            'iddokter' => 'Iddokter',
            'tgl' => 'Tgl',
            'status' => 'Status',
        ];
    }
}
