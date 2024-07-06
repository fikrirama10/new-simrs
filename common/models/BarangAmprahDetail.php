<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_amprah_detail".
 *
 * @property int $id
 * @property int|null $idbarang
 * @property int|null $idamprah
 * @property int|null $qty
 * @property int|null $qty_setuju
 * @property string|null $keterangan
 * @property int|null $status
 */
class BarangAmprahDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_amprah_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           [['idbarang', 'idamprah', 'qty', 'qty_setuju', 'status','harga','total','total_setuju','baru'], 'integer'],
            [['keterangan','nama_barang'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idbarang' => 'Idbarang',
            'idamprah' => 'Idamprah',
            'qty' => 'Qty',
            'qty_setuju' => 'Qty Setuju',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
        ];
    }
	public function getBarang()
    {
        return $this->hasOne(DataBarang::className(), ['id' => 'idbarang']);
    }
    public function getAmprahan()
    {
        return $this->hasOne(BarangAmprah::className(), ['id' => 'idamprah']);
    }
	public function getStatuss()
    {
        return $this->hasOne(BarangAmprahStatus::className(), ['id' => 'status']);
    }
}
