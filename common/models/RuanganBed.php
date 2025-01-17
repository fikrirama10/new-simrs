<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ruangan_bed".
 *
 * @property int $id
 * @property int|null $idruangan
 * @property int|null $idbed
 * @property string|null $kodebed
 * @property int|null $status
 * @property int|null $idjenis
 *
 * @property Ruangan $idruangan0
 * @property RuanganJenis $idjenis0
 */
class RuanganBed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ruangan_bed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idruangan', 'idbed', 'status', 'idjenis','terisi','bayi'], 'integer'],
            [['kodebed'], 'string', 'max' => 50],
            [['idruangan'], 'exist', 'skipOnError' => true, 'targetClass' => Ruangan::className(), 'targetAttribute' => ['idruangan' => 'id']],
            [['idjenis'], 'exist', 'skipOnError' => true, 'targetClass' => RuanganJenis::className(), 'targetAttribute' => ['idjenis' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idruangan' => 'Idruangan',
            'idbed' => 'Idbed',
            'kodebed' => 'Kodebed',
            'status' => 'Status',
            'idjenis' => 'Idjenis',
        ];
    }

    /**
     * Gets query for [[Idruangan0]].
     *
     * @return \yii\db\ActiveQuery
     */
	public function genKode()
	{
		$pf = 'BED';
		
		$max = $this::find()->select('max(kodebed)')->andFilterWhere(['like','kodebed',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kodebed=$id;
		
	}
    public function getRuangan()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idruangan']);
    }

    /**
     * Gets query for [[Idjenis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenis()
    {
        return $this->hasOne(RuanganJenis::className(), ['id' => 'idjenis']);
    }
}
