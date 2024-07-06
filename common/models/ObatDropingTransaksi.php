<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_transaksi".
 *
 * @property int $id
 * @property string|null $idtrx
 * @property int|null $idjenis
 * @property string|null $ketrangan
 * @property string|null $tgl
 * @property int|null $iduser
 * @property int|null $status
 */
class ObatDropingTransaksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_transaksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenis', 'iduser', 'status'], 'integer'],
            [['ketrangan'], 'string'],
            [['tgl'], 'safe'],
            [['idtrx'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
	public function genKode()
	{
		$tgl=date('dmY');
		$pf='DROP'.$tgl;	
	
		$max = $this::find()->select('max(idtrx)')->andFilterWhere(['like','idtrx',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idtrx=$id;
		
	}
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtrx' => 'Idtrx',
            'idjenis' => 'Idjenis',
            'ketrangan' => 'Ketrangan',
            'tgl' => 'Tgl',
            'iduser' => 'Iduser',
            'status' => 'Status',
        ];
    }
	public function getJenis()
    {
        return $this->hasOne(ObatDropingJenis::className(), ['id' => 'idjenis']);
    }
}
