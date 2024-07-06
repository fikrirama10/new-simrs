<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_transaksi".
 *
 * @property int $id
 * @property string|null $idtrx
 * @property int|null $idjenis
 * @property string|null $tgl
 * @property int|null $idjenisrawat
 * @property string|null $no_rm
 * @property float|null $total_harga
 * @property float|null $total_bayar
 * @property float|null $total_sisa
 * @property int|null $idrawat
 * @property string|null $jam
 * @property int|null $iduser
 * @property int|null $idtransaksi
 * @property string|null $kode_resep
 *
 * @property ObatSubjenismutasi $idjenis0
 */
class ObatTransaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenis', 'idjenisrawat', 'idrawat', 'iduser', 'idtransaksi','idresep','obat_racikan','jasa_racik','jumlahracik'], 'integer'],
            [['tgl', 'jam'], 'safe'],
            [['total_harga', 'total_bayar', 'total_sisa'], 'number'],
            [['idtrx', 'no_rm', 'kode_resep'], 'string', 'max' => 50],
            [['idjenis'], 'exist', 'skipOnError' => true, 'targetClass' => ObatSubjenismutasi::className(), 'targetAttribute' => ['idjenis' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtrx' => 'Idtrx',
            'idjenis' => 'Idjenis',
            'tgl' => 'Tgl',
            'idjenisrawat' => 'Idjenisrawat',
            'no_rm' => 'No Rm',
            'total_harga' => 'Total Harga',
            'total_bayar' => 'Total Bayar',
            'total_sisa' => 'Total Sisa',
            'idrawat' => 'Idrawat',
            'jam' => 'Jam',
            'iduser' => 'Iduser',
            'idtransaksi' => 'Idtransaksi',
            'kode_resep' => 'Kode Resep',
        ];
    }

    /**
     * Gets query for [[Idjenis0]].
     *
     * @return \yii\db\ActiveQuery
     */
	public function genKode()
	{
		$pf='RI'.date('Ymd');
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
    public function getIdjenis0()
    {
        return $this->hasOne(ObatSubjenismutasi::className(), ['id' => 'idjenis']);
    }
	
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
    public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	public function getJenisrawat()
    {
        return $this->hasOne(RawatJenis::className(), ['id' => 'idjenisrawat']);
    }
	
}
