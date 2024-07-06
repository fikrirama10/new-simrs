<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_barang".
 *
 * @property int $id
 * @property string|null $nama_barang
 * @property string|null $kode_barang
 * @property string|null $idkategori
 * @property int|null $idsatuan
 * @property int|null $harga
 * @property float|null $stok
 * @property int|null $status
 */
class DataBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsatuan', 'harga', 'status'], 'integer'],
            [['stok'], 'number'],
            [['nama_barang', 'kode_barang', 'idkategori'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_barang' => 'Nama Barang',
            'kode_barang' => 'Kode Barang',
            'idkategori' => 'Idkategori',
            'idsatuan' => 'Idsatuan',
            'harga' => 'Harga',
            'stok' => 'Stok',
            'status' => 'Status',
        ];
    }
	public function genKode()
	{
		$pf='BRG';
		$max = $this::find()->select('max(kode_barang)')->andFilterWhere(['like','kode_barang',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_barang=$id;
		
	}
	public function getSatuan()
    {
        return $this->hasOne(DataSatuan::className(), ['id' => 'idsatuan']);
    }
}
