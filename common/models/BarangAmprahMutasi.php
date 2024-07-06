<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_amprah_mutasi".
 *
 * @property int $id
 * @property string|null $tgl
 * @property int|null $idmutasi
 * @property int|null $idjenismutasi
 * @property float|null $stok_awal
 * @property float|null $stok_akhir
 * @property int|null $qty
 * @property int|null $asal
 * @property int|null $dari
 * @property string|null $keterangan
 */
class BarangAmprahMutasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_amprah_mutasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl'], 'safe'],
            [['idmutasi', 'idjenismutasi', 'qty', 'asal', 'dari'], 'integer'],
            [['stok_awal', 'stok_akhir'], 'number'],
            [['keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tgl' => 'Tgl',
            'idmutasi' => 'Idmutasi',
            'idjenismutasi' => 'Idjenismutasi',
            'stok_awal' => 'Stok Awal',
            'stok_akhir' => 'Stok Akhir',
            'qty' => 'Qty',
            'asal' => 'Asal',
            'dari' => 'Dari',
            'keterangan' => 'Keterangan',
        ];
    }
}
