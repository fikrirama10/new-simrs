<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi_tindakan".
 *
 * @property int $id
 * @property int|null $idpelayanan
 * @property int|null $idrad
 * @property string|null $kode_tindakan
 * @property string|null $nama_tindakan
 * @property int|null $status
 * @property string|null $keterangan
 * @property int|null $idtindakan
 * @property int|null $idbayar
 *
 * @property Radiologi $idrad0
 * @property RadiologiPelayanan $idpelayanan0
 * @property RawatBayar $idbayar0
 * @property Tindakan $idtindakan0
 */
class RadiologiTindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi_tindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpelayanan', 'idrad', 'status', 'idtindakan', 'idbayar'], 'integer'],
            [['keterangan','nama_tindakan'], 'string'],
            [['kode_tindakan', 'nama_tindakan'], 'string', 'max' => 50],
            [['idrad'], 'exist', 'skipOnError' => true, 'targetClass' => Radiologi::className(), 'targetAttribute' => ['idrad' => 'id']],
            [['idpelayanan'], 'exist', 'skipOnError' => true, 'targetClass' => RadiologiPelayanan::className(), 'targetAttribute' => ['idpelayanan' => 'id']],
            [['idbayar'], 'exist', 'skipOnError' => true, 'targetClass' => RawatBayar::className(), 'targetAttribute' => ['idbayar' => 'id']],
          
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpelayanan' => 'Idpelayanan',
            'idrad' => 'Idrad',
            'kode_tindakan' => 'Kode Tindakan',
            'nama_tindakan' => 'Nama Tindakan',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'idtindakan' => 'Idtindakan',
            'idbayar' => 'Idbayar',
        ];
    }

    /**
     * Gets query for [[Idrad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRad()
    {
        return $this->hasOne(Radiologi::className(), ['id' => 'idrad']);
    }

    /**
     * Gets query for [[Idpelayanan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPelayanan()
    {
        return $this->hasOne(RadiologiPelayanan::className(), ['id' => 'idpelayanan']);
    }

    /**
     * Gets query for [[Idbayar0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }

    /**
     * Gets query for [[Idtindakan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTindakans()
    {
        return $this->hasOne(Tindakan::className(), ['id' => 'idtindakan']);
    }
	public function getTarif()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'idtindakan']);
    }
	
	public function genKode()
	{
		$pf='RAD';
		$max = $this::find()->select('max(kode_tindakan)')->andFilterWhere(['like','kode_tindakan',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_tindakan=$id;
		
	}
}
