<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dokter".
 *
 * @property int $id
 * @property string|null $kode_dokter
 * @property string|null $sip
 * @property string|null $nama_dokter
 * @property string|null $jenis_kelamin
 * @property int|null $idpoli
 * @property string|null $tgl_lahir
 * @property string|null $foto
 * @property int|null $status
 *
 * @property Poli $idpoli0
 * @property Rawat[] $rawats
 */
class Dokter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dokter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_kelamin','kode_dpjp'], 'string'],
            [['idpoli', 'status','idspesialis','bpjs'], 'integer'],
            [['tgl_lahir'], 'safe'],
            [['kode_dokter', 'sip', 'nama_dokter', 'foto'], 'string', 'max' => 50],
            [['idpoli'], 'exist', 'skipOnError' => true, 'targetClass' => Poli::className(), 'targetAttribute' => ['idpoli' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_dokter' => 'Kode Dokter',
            'sip' => 'Sip',
            'nama_dokter' => 'Nama Dokter',
            'jenis_kelamin' => 'Jenis Kelamin',
            'idpoli' => 'Idpoli',
            'tgl_lahir' => 'Tgl Lahir',
            'foto' => 'Foto',
            'status' => 'Aktif ?',
        ];
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
	public function getSpesialis()
    {
        return $this->hasOne(DokterSpesialis::className(), ['id' => 'idspesialis']);
    }
	
	public function getStatusdok()
    {
        return $this->hasOne(DokterStatus::className(), ['id' => 'status']);
    }

    /**
     * Gets query for [[Rawats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawats()
    {
        return $this->hasMany(Rawat::className(), ['iddokter' => 'id']);
    }
	public function genKode()
	{
		$pf='DR';
		$max = $this::find()->select('max(kode_dokter)')->andFilterWhere(['like','kode_dokter',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_dokter=$id;
		
	}
}
