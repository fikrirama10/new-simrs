<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_spri".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property int|null $idjenisrawat
 * @property int|null $iddokter
 * @property int|null $idpoli
 * @property int|null $iduser
 * @property string|null $tgl_rawat
 * @property string|null $no_spri
 * @property string|null $no_rm
 * @property int|null $status
 * @property int|null $idbayar
 */
class RawatSpri extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_spri';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idjenisrawat', 'iddokter', 'idpoli', 'iduser', 'status', 'idbayar','operasi','bayi_lahir','sep'], 'integer'],
            [['tgl_rawat'], 'safe'],
            [['no_spri', 'no_rm','nama_tindakan','kode_dokter'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'idjenisrawat' => 'Pasien Dari',
            'iddokter' => 'DPJP',
            'idpoli' => 'Spesialis',
            'iduser' => 'Iduser',
            'tgl_rawat' => 'Tgl Rencana Rawat',
            'no_spri' => 'No Spri',
            'no_rm' => 'No Rm',
            'status' => 'Status',
            'idbayar' => 'Idbayar',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf='SPRI'.$tgl;	
		$max = $this::find()->select('max(no_spri)')->andFilterWhere(['like','no_spri',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->no_spri=$id;
		
	}
	public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
	public function getJenisrawat()
    {
        return $this->hasOne(RawatJenis::className(), ['id' => 'idjenisrawat']);
    }
}
