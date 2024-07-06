<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_detail".
 *
 * @property int $id
 * @property string $kode_user
 * @property string $email
 * @property string $nama
 * @property string $nohp
 * @property string $alamat
 * @property string|null $jenis_kelamin
 * @property string $foto
 * @property int|null $iddokter
 * @property int|null $idpoli
 * @property int|null $dokter
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'nama', 'nohp'], 'required'],
            [['email'], 'unique'],
            [['kode_user', 'jenis_kelamin'], 'string'],
            [['iddokter', 'idpoli','dokter','idunit','gudang','idgudang','idruangan'], 'integer'],
            [['email', 'nama', 'nohp', 'alamat', 'foto'], 'string', 'max' => 130],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_user' => 'Kode User',
            'email' => 'Email',
            'nama' => 'Nama',
            'nohp' => 'Nohp',
            'alamat' => 'Alamat',
            'jenis_kelamin' => 'Jenis Kelamin',
            'foto' => 'Foto',
            'iddokter' => 'Iddokter',
            'idpoli' => 'Idpoli',
            'dokter' => 'Dokter',
        ];
    }
	public function genKode()
	{
		$pf='USR';
		$max = $this::find()->select('max(kode_user)')->andFilterWhere(['like','kode_user',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_user=$id;
		
	}
	public function getGudang()
    {
        return $this->hasOne(Gudang::className(), ['id' => 'idgudang']);
    }
	public function getUnit()
    {
        return $this->hasOne(UserUnit::className(), ['id' => 'idunit']);
    }
    	public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }
}
