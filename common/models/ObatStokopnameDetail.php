<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_stokopname_detail".
 *
 * @property int $id
 * @property int|null $id_so
 * @property int|null $idbarang
 * @property int|null $idbatch
 * @property int|null $stok_asal
 * @property int|null $jumlah
 * @property int|null $selisih
 * @property float|null $harga
 * @property float|null $total
 * @property string|null $keterangan
 * @property string|null $merk
 * @property int|null $status
 */
class ObatStokopnameDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_stokopname_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_so', 'idbarang', 'idbatch', 'stok_asal', 'jumlah', 'selisih', 'status'], 'integer'],
            [['harga', 'total'], 'number'],
            [['keterangan'], 'string'],
            [['merk','klarifikasi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_so' => 'Id So',
            'idbarang' => 'Idbarang',
            'idbatch' => 'Idbatch',
            'stok_asal' => 'Stok Asal',
            'jumlah' => 'Jumlah',
            'selisih' => 'Selisih',
            'harga' => 'Harga',
            'total' => 'Total',
            'keterangan' => 'Keterangan',
            'merk' => 'Merk',
            'status' => 'Status',
        ];
    }
	public function getBacth()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbatch']);
    }
	
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idbarang']);
    }
}
