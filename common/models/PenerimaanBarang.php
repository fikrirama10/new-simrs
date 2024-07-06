<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "penerimaan_barang".
 *
 * @property int $id
 * @property string|null $kode_penerimaan
 * @property string|null $no_faktur
 * @property string|null $no_up
 * @property float|null $nilai_faktur
 * @property float|null $nilai_bayar
 * @property float|null $nilai_sisa
 * @property int|null $status
 * @property string|null $tgl_faktur
 * @property int|null $idsuplier
 * @property int|null $iduser
 *
 * @property PenerimaanBarangDetail[] $penerimaanBarangDetails
 */
class PenerimaanBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penerimaan_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nilai_faktur', 'nilai_bayar', 'nilai_sisa'], 'number'],
            [['status', 'idsuplier', 'iduser','idbayar'], 'integer'],
            [['tgl_faktur'], 'safe'],
            [['kode_penerimaan', 'no_faktur', 'no_up'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_penerimaan' => 'Kode Penerimaan',
            'no_faktur' => 'No Faktur',
            'no_up' => 'No Up',
            'nilai_faktur' => 'Nilai Faktur',
            'nilai_bayar' => 'Nilai Bayar',
            'nilai_sisa' => 'Nilai Sisa',
            'status' => 'Status',
            'tgl_faktur' => 'Tgl Faktur',
            'idsuplier' => 'Idsuplier',
            'iduser' => 'Iduser',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "REC".$tgl;
		$max = $this::find()->select('max(kode_penerimaan)')->andFilterWhere(['like','kode_penerimaan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_penerimaan=$id;
		
	}	
    /**
     * Gets query for [[PenerimaanBarangDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenerimaanBarangDetails()
    {
        return $this->hasMany(PenerimaanBarangDetail::className(), ['idpenerimaan' => 'id']);
    }
	public function getSuplier()
    {
        return $this->hasOne(ObatSuplier::className(), ['id' => 'idsuplier']);
    }
	public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
	
}
