<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "amprah_gudangatk".
 *
 * @property int $id
 * @property string|null $idamprah
 * @property int|null $idpermintaan
 * @property string|null $tgl_permintaan
 * @property string|null $tgl_penyerahan
 * @property int|null $idasal
 * @property int|null $idpeminta
 * @property int|null $status
 * @property string|null $keterangan
 */
class AmprahGudangatk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'amprah_gudangatk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpermintaan', 'idasal', 'idpeminta', 'status'], 'integer'],
            [['tgl_permintaan', 'tgl_penyerahan'], 'safe'],
            [['keterangan'], 'string'],
            [['idamprah'], 'string', 'max' => 50],
        ];
    }
		public function genKode()
	{
		$tgl=date('Ymd');
		$pf= "AMP-ATK".$tgl;
		$max = $this::find()->select('max(idamprah)')->andFilterWhere(['like','idamprah',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idamprah=$id;
		
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idamprah' => 'Idamprah',
            'idpermintaan' => 'Idpermintaan',
            'tgl_permintaan' => 'Tgl Permintaan',
            'tgl_penyerahan' => 'Tgl Penyerahan',
            'idasal' => 'Idasal',
            'idpeminta' => 'Idpeminta',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
	public function getRuangan()
    {
        return $this->hasOne(UnitRuangan::className(), ['id' => 'idpeminta']);
    }
}
