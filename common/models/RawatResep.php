<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_resep".
 *
 * @property int $id
 * @property string|null $kode_resep
 * @property int|null $idrawat
 * @property int|null $no_rm
 * @property int|null $iddokter
 * @property string|null $tgl_resep
 * @property string|null $jam_resep
 * @property int|null $status
 * @property int|null $idjenisrawat
 */
class RawatResep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_resep';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat','iddokter', 'status', 'idjenisrawat'], 'integer'],
            [['no_rm','tgl_resep', 'jam_resep'], 'safe'],
            [['kode_resep'], 'string', 'max' => 50],
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
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'iddokter' => 'Iddokter',
            'tgl_resep' => 'Tgl Resep',
            'jam_resep' => 'Jam Resep',
            'status' => 'Status',
            'idjenisrawat' => 'Idjenisrawat',
        ];
    }
	public function genKode()
	{
		$pf='R'.date('Ymd');
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
