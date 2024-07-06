<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "operasi".
 *
 * @property int $id
 * @property string|null $kode_ok
 * @property int|null $idasal
 * @property string|null $tgl_ok
 * @property int|null $idjenis
 * @property int|null $iddokter
 * @property int|null $idanastesi
 * @property string|null $laporan_pembedahan
 * @property string|null $diagnosisprabedah
 * @property string|null $icd10
 * @property string|null $icd9
 */
class Operasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'operasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idasal', 'idjenis', 'iddokter', 'idanastesi','idtarif','status','idrawat'], 'integer'],
            [['tgl_ok'], 'safe'],
            [['laporan_pembedahan', 'diagnosisprabedah','no_rm'], 'string'],
            [['kode_ok'], 'string', 'max' => 50],
            [['icd10', 'icd9'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_ok' => 'Kode Ok',
            'idasal' => 'Idasal',
            'tgl_ok' => 'Tgl Ok',
            'idjenis' => 'Idjenis',
            'iddokter' => 'Iddokter',
            'idanastesi' => 'Idanastesi',
            'laporan_pembedahan' => 'Laporan Pembedahan',
            'diagnosisprabedah' => 'Diagnosisprabedah',
            'icd10' => 'icd10',
            'icd9' => 'Icd9',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "OK".$tgl;
		$max = $this::find()->select('max(kode_ok)')->andFilterWhere(['like','kode_ok',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_ok=$id;
		
	}
	public function getStatusok()
    {
        return $this->hasOne(OperasiStatus::className(), ['id' => 'status']);
    }
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
	public function getAsal()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idasal']);
    }
}
