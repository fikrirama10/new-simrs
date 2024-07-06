<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usul_pesan".
 *
 * @property int $id
 * @property string|null $kode_up
 * @property string|null $tgl_up
 * @property string|null $tgl_setuju
 * @property float|null $total_harga
 * @property int|null $status
 * @property int|null $jenis_up
 * @property int|null $iduser
 *
 * @property UsulPesanDetail[] $usulPesanDetails
 */
class UsulPesan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usul_pesan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_up', 'tgl_setuju','catatan'], 'safe'],
            [['total_harga'], 'number'],
            [['status', 'jenis_up', 'iduser'], 'integer'],
            [['kode_up'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_up' => 'Kode Up',
            'tgl_up' => 'Tgl Up',
            'tgl_setuju' => 'Tgl Setuju',
            'total_harga' => 'Total Harga',
            'status' => 'Status',
            'jenis_up' => 'Jenis Up',
            'iduser' => 'Iduser',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "UP-GD".$tgl;
		$max = $this::find()->select('max(kode_up)')->andFilterWhere(['like','kode_up',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_up=$id;
		
	}
    /**
     * Gets query for [[UsulPesanDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsulPesanDetails()
    {
        return $this->hasMany(UsulPesanDetail::className(), ['idup' => 'id']);
    }
	public function getPermintaan()
    {
        return $this->hasOne(PermintaanObatstatus::className(), ['id' => 'status']);
    }
	public function getJenis()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'jenis_up']);
    }
	
}
