<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pasien".
 *
 * @property int $id
 * @property string $no_rm
 * @property string $nik
 * @property string $no_bpjs
 * @property string $nama_pasien
 * @property string|null $tgl_lahir
 * @property string|null $tempat_lahir
 * @property int|null $usia_tahun
 * @property int|null $usia_bulan
 * @property int|null $usia_hari
 * @property int $idpekerjaan
 * @property int $idagama
 * @property int $idgolongan_darah
 * @property int $idpendidikan
 * @property int|null $idhubungan
 * @property string $kepesertaan_bpjs
 *
 * @property DataAgama $idagama0
 * @property DataGolongandarah $idgolonganDarah
 * @property DataPekerjaan $idpekerjaan0
 * @property DataPendidikan $idpendidikan0
 */
class Pasien extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pasien';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_pasien','tgllahir'], 'required'],
			
            [['tgllahir','tgldaftar','jamdaftar'], 'safe'],
            [['usia_tahun', 'usia_bulan', 'usia_hari', 'idpekerjaan','idetnis', 'idagama', 'idgolongan_darah', 'idpendidikan', 'idhubungan','idkepesertaan','idhambatan','status_pasien','idsb_penanggungjawab','iduser','doa','barulahir','idusia','pasien_lama'], 'integer'],
            [['no_rm','kodepasien','jenis_kelamin'], 'string', 'max' => 10],
            //[['no_rm','idkelurahan'], 'string', 'min' => 6],
            [['nik','nohp'], 'string', 'max' => 16],
            [['no_bpjs'], 'string', 'max' => 13],
            [['nama_pasien','email','nrp','pangkat','kesatuan','penanggung_jawab','alamat_penanggunjawab','nohp_penanggungjawab','alergi'], 'string', 'max' => 250],
            [['tempat_lahir', 'kepesertaan_bpjs'], 'string', 'max' => 50],
            [['kodepasien'], 'unique'],
            [['nik'], 'unique'],
            [['no_bpjs'], 'unique'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_rm' => 'No Rm',
            'nik' => 'Nik',
            'no_bpjs' => 'No Bpjs',
            'nama_pasien' => 'Nama Pasien',
            'tgllahir' => 'Tgl Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'usia_tahun' => 'Usia Tahun',
            'usia_bulan' => 'Usia Bulan',
            'usia_hari' => 'Usia Hari',
            'idpekerjaan' => 'Idpekerjaan',
            'idagama' => 'Idagama',
            'idgolongan_darah' => 'Idgolongan Darah',
            'idpendidikan' => 'Idpendidikan',
            'idhubungan' => 'Idhubungan',
            'kepesertaan_bpjs' => 'Kepesertaan Bpjs',
            'barulahir' => 'Baru Lahir',
        ];
    }

    /**
     * Gets query for [[Idagama0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgama()
    {
        return $this->hasOne(DataAgama::className(), ['id' => 'idagama']);
    }
	public function getEtnis()
    {
        return $this->hasOne(DataEtnis::className(), ['id' => 'idetnis']);
    }
	
	public function getPenanggung()
    {
        return $this->hasOne(PasienPenanggungjawab::className(), ['id' => 'idsb_penanggungjawab']);
    }

    /**
     * Gets query for [[IdgolonganDarah]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDarah()
    {
        return $this->hasOne(DataGolongandarah::className(), ['id' => 'idgolongan_darah']);
    }

	public function getHubungan()
    {
        return $this->hasOne(DataHubungan::className(), ['id' => 'idhubungan']);
    }

    /**
     * Gets query for [[Idpekerjaan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaan()
    {
        return $this->hasOne(DataPekerjaan::className(), ['id' => 'idpekerjaan']);
    }
	
	public function getHambatan()
    {
        return $this->hasOne(DataHambatan::className(), ['id' => 'idhambatan']);
    }

	public function getDesa()
    {
        return $this->hasOne(Kelurahan::className(), ['id_kel' => 'idkelurahan']);
    }

    /**
     * Gets query for [[Idpendidikan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPendidikan()
    {
        return $this->hasOne(DataPendidikan::className(), ['id' => 'idpendidikan']);
    }
	public function genKode()
	{
		$pf='P-';
		$max = $this::find()->select('max(kodepasien)')->andFilterWhere(['like','kodepasien',$pf])->scalar(); 
		$last= substr($max,strlen($pf),6) + 1;
		
		if($last<10){
			$id=$pf.'00000'.$last;}
		elseif($last<100){
			$id=$pf.'0000'.$last;}
		elseif($last<1000){
			$id=$pf.'000'.$last;}
		elseif($last<10000){
			$id=$pf.'00'.$last;}
		elseif($last<100000){
			$id=$pf.'0'.$last;}
		elseif($last<1000000){
			$id=$pf.$last;}
		$this->kodepasien=$id;
		
	}

}
