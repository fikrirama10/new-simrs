<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permintaan_obat".
 *
 * @property int $id
 * @property string|null $kode_permintaan
 * @property string|null $tgl_permintaan
 * @property int|null $iduser_peminta
 * @property int|null $iduser_persetujuan
 * @property string|null $keretangan
 * @property int|null $status
 * @property float|null $total_biaya
 */
class PermintaanObat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permintaan_obat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_permintaan'], 'safe'],
            [['iduser_peminta', 'iduser_persetujuan', 'status','asal_permintaan','idbayar','idruangan'], 'integer'],
            [['keterangan'], 'string'],
            [['total_biaya'], 'number'],
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
            'iduser_peminta' => 'Iduser Peminta',
            'iduser_persetujuan' => 'Iduser Persetujuan',
            'keterangan' => 'keterangan',
            'status' => 'Status',
            'total_biaya' => 'Total Biaya',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "UP".$tgl;
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
	
	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'iduser_peminta']);
    }
	public function getUnit()
    {
        return $this->hasOne(UserUnit::className(), ['id' => 'asal_permintaan']);
    }
    public function getRuangan()
    {
        return $this->hasOne(UnitRuangan::className(), ['id' => 'idruangan']);
    }
	public function getPermintaanstatus()
    {
        return $this->hasOne(PermintaanObatstatus::className(), ['id' => 'status']);
    }
}
