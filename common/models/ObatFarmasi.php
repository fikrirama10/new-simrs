<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_farmasi".
 *
 * @property int $id
 * @property string|null $kode_resep
 * @property string|null $tgl
 * @property int|null $idjenis
 * @property int|null $total_harga
 * @property int|null $tuslah
 * @property int|null $obat_racik
 * @property int|null $jasa_racik
 * @property int|null $keuntungan
 * @property int|null $status
 * @property string|null $nama_pasien
 * @property string|null $alamat
 * @property string|null $usia
 * @property string|null $nrp
 * @property string|null $keterangan
 */
class ObatFarmasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_farmasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl'], 'safe'],
            [['idjenis', 'total_harga', 'tuslah', 'obat_racik', 'jasa_racik', 'keuntungan', 'status'], 'integer'],
            [['alamat','no_tlp', 'keterangan'], 'string'],
            [['kode_resep', 'nama_pasien', 'usia', 'nrp'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_resep' => 'Kode Resep',
            'tgl' => 'Tgl',
            'idjenis' => 'Idjenis',
            'total_harga' => 'Total Harga',
            'tuslah' => 'Tuslah',
            'obat_racik' => 'Obat Racik',
            'jasa_racik' => 'Jasa Racik',
            'keuntungan' => 'Keuntungan',
            'status' => 'Status',
            'nama_pasien' => 'Nama Pasien',
            'alamat' => 'Alamat',
            'usia' => 'Usia',
            'nrp' => 'Nrp',
            'keterangan' => 'Keterangan',
        ];
    }
	public function genKode()
	{
		$tgl=date('dmY');
		$pf='FARM'.$tgl;	
	
		$max = $this::find()->select('max(kode_resep)')->andFilterWhere(['like','kode_resep',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_resep=$id;
		
	}
}
