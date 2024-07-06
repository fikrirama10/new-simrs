<?php

namespace common\models;

use Yii;

class LaboratoriumPemeriksaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium_pemeriksaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlab', 'idjenis', 'urutan', 'idtindakan','idtarif'], 'integer'],
            [['nama_pemeriksaan', 'kode_lab'], 'string', 'max' => 50],
            [['idlab'], 'exist', 'skipOnError' => true, 'targetClass' => LaboratoriumLayanan::className(), 'targetAttribute' => ['idlab' => 'id']],
            [['idjenis'], 'exist', 'skipOnError' => true, 'targetClass' => RawatBayar::className(), 'targetAttribute' => ['idjenis' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idlab' => 'Idlab',
            'nama_pemeriksaan' => 'Nama Pemeriksaan',
            'idjenis' => 'Idjenis',
            'urutan' => 'Urutan',
            'kode_lab' => 'Kode Lab',
            'idtindakan' => 'Idtindakan',
        ];
    }


    public function getLayanan()
    {
        return $this->hasOne(LaboratoriumLayanan::className(), ['id' => 'idlab']);
    }
	public function getTarif()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtarif']);
    }

	public function genKode()
	{
		$pf='LAB';
		$max = $this::find()->select('max(kode_lab)')->andFilterWhere(['like','kode_lab',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_lab=$id;
		
	}
}
