<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_rujukan".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property int|null $idjenisrawat
 * @property int|null $idbayar
 * @property int|null $idpoli
 * @property int|null $iddokter
 * @property string|null $kode_tujuan
 * @property string|null $kode_asal
 * @property string|null $faskes_tujuan
 * @property string|null $faskes_asal
 * @property string|null $no_rujukan
 * @property string|null $kode_rujukan
 * @property int|null $alasan_rujuk
 * @property string|null $tgl_rujuk
 * @property string|null $tgl_kunjungan
 * @property int|null $iduser
 * @property string|null $no_sep
 * @property int|null $catatan
 * @property string|null $kode_dokter
 * @property string|null $tujuan_rujuk
 * @property string|null $diagnosa_klinis
 * @property string|null $icd10
 */
class RawatRujukan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_rujukan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idjenisrawat', 'idbayar', 'idpoli', 'iddokter',  'iduser', 'catatan'], 'integer'],
            [['tgl_rujuk', 'tgl_kunjungan'], 'safe'],
            [['kode_tujuan', 'kode_asal', 'faskes_tujuan', 'faskes_asal', 'no_rujukan', 'kode_rujukan', 'no_sep', 'kode_dokter', 'tujuan_rujuk', 'diagnosa_klinis', 'icd10','no_rm','jenis_rujukan','alasan_rujuk','idspesialis'], 'string', 'max' => 250],
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
            'idjenisrawat' => 'Idjenisrawat',
            'idbayar' => 'Idbayar',
            'idpoli' => 'Idpoli',
            'iddokter' => 'Iddokter',
            'kode_tujuan' => 'Kode Tujuan',
            'kode_asal' => 'Kode Asal',
            'faskes_tujuan' => 'Faskes Tujuan',
            'faskes_asal' => 'Faskes Asal',
            'no_rujukan' => 'No Rujukan',
            'kode_rujukan' => 'Kode Rujukan',
            'alasan_rujuk' => 'Alasan Rujuk',
            'tgl_rujuk' => 'Tgl Rujuk',
            'tgl_kunjungan' => 'Tgl Kunjungan',
            'iduser' => 'Iduser',
            'no_sep' => 'No Sep',
            'catatan' => 'Catatan',
            'kode_dokter' => 'Kode Dokter',
            'tujuan_rujuk' => 'Tujuan Rujuk',
            'diagnosa_klinis' => 'Diagnosa Klinis',
            'icd10' => 'icd10',
        ];
    }
	public function genKode()
	{
		$tgl=date('YmGis');
		$pf='R'.$tgl;
		$max = $this::find()->select('max(kode_rujukan)')->andFilterWhere(['like','kode_rujukan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_rujukan=$id;
		
	}
	 public function genKoderujuk($sd)
	{
		$tgl=$sd;
		$pf=$tgl;
		$max = $this::find()->select('max(kode_rujukan)')->andFilterWhere(['like','kode_rujukan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_rujukan=$id;
		
	}
	 public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }

    /**
     * Gets query for [[Idbayar0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
    /**
     * Gets query for [[Idjenisrawat0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisrawat()
    {
        return $this->hasOne(RawatJenis::className(), ['id' => 'idjenisrawat']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
}
