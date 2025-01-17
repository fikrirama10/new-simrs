<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_stokopname".
 *
 * @property int $id
 * @property string|null $kode_so
 * @property string|null $tgl_so
 * @property int|null $iduser
 * @property int|null $status
 * @property string|null $keterangan
 */
class BarangStokopname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_stokopname';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_so'], 'safe'],
            [['iduser', 'status'], 'integer'],
            [['kode_so', 'keterangan'], 'string', 'max' => 50],
        ];
    }

	public function genKode()
	{
		$tgl=date('Ymd');
		$pf='SOBRG'.$tgl;	
	
		$max = $this::find()->select('max(kode_so)')->andFilterWhere(['like','kode_so',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_so=$id;
		
	}
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_so' => 'Kode So',
            'tgl_so' => 'Tgl So',
            'iduser' => 'Iduser',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
}
