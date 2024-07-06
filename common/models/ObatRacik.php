<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_racik".
 *
 * @property int $id
 * @property int|null $idresep
 * @property string|null $tgl
 * @property int|null $idbayar
 * @property int|null $status
 */
class ObatRacik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_racik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idresep', 'idbayar', 'status'], 'integer'],
            [['tgl','kode_racik'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idresep' => 'Idresep',
            'tgl' => 'Tgl',
            'idbayar' => 'Idbayar',
            'status' => 'Status',
        ];
    }
	public function genKode()
	{
		$pf='RCK'.date('Ymd');
		$max = $this::find()->select('max(kode_racik)')->andFilterWhere(['like','kode_racik',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_racik=$id;
		
	}
}
