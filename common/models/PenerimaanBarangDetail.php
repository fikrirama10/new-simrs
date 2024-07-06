<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "penerimaan_barang_detail".
 *
 * @property int $id
 * @property int|null $idpenerimaan
 * @property int|null $idbarang
 * @property int|null $idbacth
 * @property float|null $jumlah
 * @property float|null $harga
 * @property float|null $total
 *
 * @property PenerimaanBarang $idpenerimaan0
 */
class PenerimaanBarangDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penerimaan_barang_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpenerimaan', 'idbarang', 'idbacth','satuan','total_diskon','diterima','jumlah_diterima'], 'integer'],
            [['jumlah', 'harga', 'total','ppn','diskon'], 'number'],
            [['idpenerimaan'], 'exist', 'skipOnError' => true, 'targetClass' => PenerimaanBarang::className(), 'targetAttribute' => ['idpenerimaan' => 'id']],
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
            'idbacth' => 'Idbacth',
            'jumlah' => 'Jumlah',
            'harga' => 'Harga',
            'total' => 'Total',
        ];
    }

    /**
     * Gets query for [[Idpenerimaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenerimaan()
    {
        return $this->hasOne(PenerimaanBarang::className(), ['id' => 'idpenerimaan']);
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idbarang']);
    }
	public function getMerk()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }
}
