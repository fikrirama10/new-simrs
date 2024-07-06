<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi_hasil".
 *
 * @property int $id
 * @property int|null $idhasil
 * @property int|null $idpetugas
 * @property int|null $iddokter
 * @property string|null $catatan
 * @property string|null $tgl_hasil
 * @property int|null $status
 * @property int|null $idbayar
 */
class RadiologiHasil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi_hasil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idhasil', 'idpetugas', 'iddokter', 'status', 'idbayar','idrawat'], 'integer'],
            [['catatan','no_rm'], 'string'],
            [['tgl_hasil'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idhasil' => 'Idhasil',
            'idpetugas' => 'Idpetugas',
            'iddokter' => 'Iddokter',
            'catatan' => 'Catatan',
            'tgl_hasil' => 'Tgl Hasil',
            'status' => 'Status',
            'idbayar' => 'Idbayar',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf='RAD'.$tgl;	
	
		$max = $this::find()->select('max(idhasil)')->andFilterWhere(['like','idhasil',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idhasil=$id;
		
	}
	public function getDokterrad()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }

}
