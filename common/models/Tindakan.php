<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tindakan".
 *
 * @property int $id
 * @property string|null $nama_tindakan
 * @property string|null $kode_tindakan
 * @property int|null $idjenistindakan
 * @property int|null $idpoli
 * @property int|null $idbayar
 * @property int|null $idpenerimaan
 * @property int|null $idjenispenerimaan
 * @property float|null $tarif
 *
 * @property RadiologiTindakan[] $radiologiTindakans
 * @property Jenispenerimaan $idpenerimaan0
 * @property JenispenerimaanDetail $idjenispenerimaan0
 * @property Poli $idpoli0
 * @property RawatBayar $idbayar0
 * @property TindakanKategori $idjenistindakan0
 */
class Tindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tindakan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenistindakan', 'idpoli', 'idpenerimaan', 'idjenispenerimaan','idtarif'], 'integer'],
            [['nama_tindakan'], 'string', 'max' => 150],
            [['kode_tindakan'], 'string', 'max' => 50],
            [['idpenerimaan'], 'exist', 'skipOnError' => true, 'targetClass' => Jenispenerimaan::className(), 'targetAttribute' => ['idpenerimaan' => 'id']],
            [['idjenispenerimaan'], 'exist', 'skipOnError' => true, 'targetClass' => JenispenerimaanDetail::className(), 'targetAttribute' => ['idjenispenerimaan' => 'id']],
            [['idpoli'], 'exist', 'skipOnError' => true, 'targetClass' => Poli::className(), 'targetAttribute' => ['idpoli' => 'id']],
            [['idjenistindakan'], 'exist', 'skipOnError' => true, 'targetClass' => TindakanKategori::className(), 'targetAttribute' => ['idjenistindakan' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_tindakan' => 'Nama Tindakan',
            'kode_tindakan' => 'Kode Tindakan',
            'idjenistindakan' => 'Idjenistindakan',
            'idpoli' => 'Idpoli',
            'idpenerimaan' => 'Idpenerimaan',
            'idjenispenerimaan' => 'Idjenispenerimaan',
        ];
    }

    /**
     * Gets query for [[RadiologiTindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadiologiTindakans()
    {
        return $this->hasMany(RadiologiTindakan::className(), ['idtindakan' => 'id']);
    }

    /**
     * Gets query for [[Idpenerimaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenerimaan()
    {
        return $this->hasOne(Jenispenerimaan::className(), ['id' => 'idpenerimaan']);
    }

    /**
     * Gets query for [[Idjenispenerimaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasOne(JenispenerimaanDetail::className(), ['id' => 'idjenispenerimaan']);
    }

    /**
     * Gets query for [[Idpoli0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }

    /**
     * Gets query for [[Idbayar0]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[Idjenistindakan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTindakan()
    {
        return $this->hasOne(TindakanKategori::className(), ['id' => 'idjenistindakan']);
    }
	public function genKode()
	{
		$pf='TIND';
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
