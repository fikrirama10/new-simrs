<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "barang_amprah".
 *
 * @property int $id
 * @property string|null $kode_permintaan
 * @property string|null $tgl_permintaan
 * @property int|null $unit_peminta
 * @property int|null $iduser
 * @property int|null $status
 * @property string|null $keterangan
 */
class BarangAmprah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_amprah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_permintaan'], 'safe'],
             [['unit_peminta', 'iduser', 'status','total','total_setuju','idruangan'], 'integer'],
            [['keterangan'], 'string'],
            [['kode_permintaan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_permintaan' => 'Kode Permintaan',
            'tgl_permintaan' => 'Tgl Permintaan',
            'unit_peminta' => 'Unit Peminta',
            'iduser' => 'Iduser',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "SR".$tgl;
		$max = $this::find()->select('max(kode_permintaan)')->andFilterWhere(['like','kode_permintaan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_permintaan=$id;
		
	}
	public function getUnit()
    {
        return $this->hasOne(UserUnit::className(), ['id' => 'unit_peminta']);
    }
    public function getRuangan()
    {
        return $this->hasOne(UnitRuangan::className(), ['id' => 'idruangan']);
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'iduser']);
    }
	public function getAmprah()
    {
        return $this->hasOne(BarangAmprahStatus::className(), ['id' => 'status']);
    }
}
