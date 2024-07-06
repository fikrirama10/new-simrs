<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "laboratorium_hasil".
 *
 * @property int $id
 * @property string|null $labid
 * @property string|null $tgl_hasil
 * @property string|null $tgl_permintaan
 * @property int|null $iddokter
 * @property int|null $idpetugas
 * @property int|null $status
 * @property string|null $catatan
 * @property int|null $idrawat
 */
class LaboratoriumHasil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium_hasil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgl_hasil', 'tgl_permintaan'], 'safe'],
            [['iddokter', 'idpetugas', 'status', 'idrawat'], 'integer'],
            [['labid', 'catatan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'labid' => 'Labid',
            'tgl_hasil' => 'Tgl Hasil',
            'tgl_permintaan' => 'Tgl Permintaan',
            'iddokter' => 'Iddokter',
            'idpetugas' => 'Idpetugas',
            'status' => 'Status',
            'catatan' => 'Catatan',
            'idrawat' => 'Idrawat',
        ];
    }
	public function genKode()
	{
		$tgl=date('Ymd');
		$pf='LAB'.$tgl;	
	
		$max = $this::find()->select('max(labid)')->andFilterWhere(['like','labid',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->labid=$id;
		
	}
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
}
