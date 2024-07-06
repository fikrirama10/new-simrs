<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_penerimaan_detail".
 *
 * @property int $id
 * @property int|null $idpenerimaan
 * @property int|null $idbarang
 * @property int|null $qty
 * @property int|null $harga
 * @property int|null $total
 * @property int|null $status
 */
class BarangPenerimaanDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_penerimaan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpenerimaan', 'idbarang', 'qty', 'harga', 'total', 'status','qty_diterima','total_diterima','diterima'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpenerimaan' => 'Idpenerimaan',
            'idbarang' => 'Idbarang',
            'qty' => 'Qty',
            'harga' => 'Harga',
            'total' => 'Total',
            'status' => 'Status',
        ];
    }
	public function getBarang()
    {
        return $this->hasOne(DataBarang::className(), ['id' => 'idbarang']);
    }
    	public function getPenerimaan()
    {
        return $this->hasOne(BarangPenerimaan::className(), ['id' => 'idpenerimaan']);
    }
}
