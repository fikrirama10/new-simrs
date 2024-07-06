<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_stokopname_detail".
 *
 * @property int $id
 * @property int|null $idso
 * @property int|null $idbarang
 * @property int|null $stokasal
 * @property int|null $stokreal
 * @property int|null $selisih
 * @property int|null $harga
 * @property int|null $nilaiselisih
 */
class BarangStokopnameDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_stokopname_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idso', 'idbarang', 'stokasal', 'stokreal', 'selisih', 'harga', 'nilaiselisih','status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idso' => 'Idso',
            'idbarang' => 'Idbarang',
            'stokasal' => 'Stokasal',
            'stokreal' => 'Stokreal',
            'selisih' => 'Selisih',
            'harga' => 'Harga',
            'nilaiselisih' => 'Nilaiselisih',
        ];
    }
		public function getBarang()
    {
        return $this->hasOne(DataBarang::className(), ['id' => 'idbarang']);
    }
}
