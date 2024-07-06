<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tarif".
 *
 * @property int $id
 * @property string|null $kode_tarif
 * @property string|null $nama_tarif
 * @property int|null $idkategori
 * @property int|null $idjenisrawat
 * @property int|null $kat_tindakan
 * @property int|null $idpoli
 * @property int|null $idkelas
 * @property float|null $medis
 * @property float|null $paramedis
 * @property float|null $petugas
 * @property float|null $apoteker
 * @property float|null $gizi
 * @property float|null $bph
 * @property float|null $sewakamar
 * @property float|null $sewaalat
 * @property float|null $makanpasien
 * @property float|null $laundry
 * @property float|null $cs
 * @property float|null $opsrs
 * @property float|null $nova_t
 * @property float|null $perekam_medis
 * @property float|null $tarif
 */
class Tarif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idkategori', 'idjenisrawat', 'kat_tindakan', 'idpoli', 'idkelas','paket','idruangan'], 'integer'],
            [['medis', 'paramedis', 'petugas', 'apoteker', 'gizi', 'bph', 'sewakamar', 'sewaalat', 'makanpasien', 'laundry', 'cs', 'opsrs', 'nova_t', 'perekam_medis', 'tarif','radiografer','radiolog','assisten','operator','ass_tim','dokter_anastesi','sewa_ok','asisten_anastesi','cssd','bbm','ranmor','supir','dokter','rs','harbang','atlm'], 'number'],
            [['kode_tarif', 'nama_tarif'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_tarif' => 'Kode Tarif',
            'nama_tarif' => 'Nama Tarif',
            'idkategori' => 'Idkategori',
            'idjenisrawat' => 'Idjenisrawat',
            'kat_tindakan' => 'Kat Tindakan',
            'idpoli' => 'Idpoli',
            'idkelas' => 'Idkelas',
            'medis' => 'Medis',
            'paramedis' => 'Paramedis',
            'petugas' => 'Petugas',
            'apoteker' => 'Apoteker',
            'gizi' => 'Gizi',
            'bph' => 'Bph',
            'sewakamar' => 'Sewakamar',
            'sewaalat' => 'Sewaalat',
            'makanpasien' => 'Makanpasien',
            'laundry' => 'Laundry',
            'cs' => 'Cs',
            'opsrs' => 'Opsrs',
            'nova_t' => 'Nova T',
            'perekam_medis' => 'Perekam Medis',
            'tarif' => 'Tarif',
        ];
    }
	public function genKode()
	{
		
		$pf= "TAR";
		$max = $this::find()->select('max(kode_tarif)')->andFilterWhere(['like','kode_tarif',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->kode_tarif=$id;
		
	}
	public function getKelas()
    {
        return $this->hasOne(RuanganKelas::className(), ['id' => 'idkelas']);
    }
	public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }
	public function getRuangan()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idruangan']);
    }
	public function getKategori()
    {
        return $this->hasOne(TindakanKategori::className(), ['id' => 'idkategori']);
    }
	public function getTindakan()
    {
        return $this->hasOne(TarifKattindakan::className(), ['id' => 'kat_tindakan']);
    }
}
