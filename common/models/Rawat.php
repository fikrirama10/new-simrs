<?php

namespace common\models;

use Yii;
use common\models\Poli;
/**
 * This is the model class for table "rawat".
 *
 * @property int $id
 * @property string|null $idrawat
 * @property string|null $idkunjungan
 * @property int|null $idjenisrawat
 * @property string|null $no_rm
 * @property int|null $idpoli
 * @property int|null $iddokter
 * @property int|null $idruangan
 * @property int|null $idkelas
 * @property int|null $idbayar
 * @property string|null $no_sep
 * @property string|null $no_rujukan
 * @property string|null $no_suratkontrol
 * @property string|null $tglmasuk
 * @property string|null $tglpulang
 * @property float|null $los
 * @property int|null $status
 * @property string|null $no_antrian
 * @property string|null $cara_datang
 * @property string|null $cara_keluar
 * @property int|null $kunjungan
 *
 * @property Dokter $iddokter0
 * @property Poli $idpoli0
 * @property RawatBayar $idbayar0
 * @property RawatJenis $idjenisrawat0
 * @property Ruangan $idruangan0
 * @property RuanganKelas $idkelas0
 */
class Rawat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenisrawat','asal_rujukan','kode_faskes', 'idpoli', 'iddokter', 'idruangan', 'idkelas', 'idbayar', 'status', 'kunjungan','anggota','idpekerjaan','idbed','idjenisperawatan','idmasuk','operasi','iduser','kat_pasien','melahirkan','kegiatan_ugd','kat_penyakit','ok','naik_kelas','idkelas_naik','pembiayaan','jaminan','katarak','online'], 'integer'],
            [['tglmasuk', 'tglpulang','penanggungjawab','keterangan'], 'safe'],
            [['los'], 'number'],
            [['idrawat', 'idkunjungan', 'no_rm', 'no_sep', 'no_rujukan', 'no_suratkontrol', 'no_antrian', 'cara_datang', 'cara_keluar','no_referal','icdx'], 'string', 'max' => 150],
            [['iddokter'], 'exist', 'skipOnError' => true, 'targetClass' => Dokter::className(), 'targetAttribute' => ['iddokter' => 'id']],
            [['idpoli'], 'exist', 'skipOnError' => true, 'targetClass' => Poli::className(), 'targetAttribute' => ['idpoli' => 'id']],
            [['idbayar'], 'exist', 'skipOnError' => true, 'targetClass' => RawatBayar::className(), 'targetAttribute' => ['idbayar' => 'id']],
            [['idjenisrawat'], 'exist', 'skipOnError' => true, 'targetClass' => RawatJenis::className(), 'targetAttribute' => ['idjenisrawat' => 'id']],

        ];
    }
	public function genKode($jenis)
	{
		$tgl=date('YGis');
		if($jenis == 1){
			$pf='RJ'.$tgl;	
		}else if($jenis == 3){
			$pf='UGD'.$tgl;
		}else{
			$pf='RI'.$tgl;
		}
		$max = $this::find()->select('max(idrawat)')->andFilterWhere(['like','idrawat',$pf])->scalar(); 
		$last=substr($max,strlen($pf),4) + 1;
		
		if($last<10){
			$id=$pf.'000'.$last;}
		elseif($last<100){
			$id=$pf.'00'.$last;}
		elseif($last<1000){
			$id=$pf.'0'.$last;}
		elseif($last<10000){
			$id=$pf.$last;}
		$this->idrawat=$id;
		
	}
	public function genAntri($pf,$iddokter,$ang,$daftar)
	{
		$tgl = date('Ymd',strtotime($daftar));
			$poli = Poli::findOne($pf);
			$max = $this::find()->select('max(no_antrian)')->andFilterWhere(['like', 'idpoli',$pf])->andFilterWhere(['like', 'iddokter',$iddokter])->andwhere(['<>','status','5'])->andFilterWhere(['like','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$daftar])->scalar(); 
			$last=substr($max,strlen($tgl.$pf.$iddokter.$poli->kode_antrean),4) + 1;
			//$poli = Poli::find()->where(['id'=>$pf])->one();
			if($last<10){
				$id=$tgl.$pf.$iddokter.'000'.$last;}
			elseif($last<100){
				$id=$tgl.$pf.$iddokter.'00'.$last;}
			elseif($last<1000){
				$id=$tgl.$pf.$iddokter.'0'.$last;}
			elseif($last<10000){
				$id=$tgl.$pf.$iddokter.$last;}
			$this->no_antrian=$id;
		
		
		
		
	}
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'idkunjungan' => 'Idkunjungan',
            'idjenisrawat' => 'Idjenisrawat',
            'no_rm' => 'No Rm',
            'idpoli' => 'Idpoli',
            'iddokter' => 'Iddokter',
            'idruangan' => 'Idruangan',
            'idkelas' => 'Idkelas',
            'idbayar' => 'Idbayar',
            'no_sep' => 'No Sep',
            'no_rujukan' => 'No Rujukan',
            'no_suratkontrol' => 'No Suratkontrol',
            'tglmasuk' => 'Tglmasuk',
            'tglpulang' => 'Tglpulang',
            'los' => 'Los',
            'status' => 'Status',
            'no_antrian' => 'No Antrian',
            'cara_datang' => 'Cara Datang',
            'cara_keluar' => 'Cara Keluar',
            'kunjungan' => 'Kunjungan',
        ];
    }

    /**
     * Gets query for [[Iddokter0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
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
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }

    /**
     * Gets query for [[Idjenisrawat0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisrawat()
    {
        return $this->hasOne(RawatJenis::className(), ['id' => 'idjenisrawat']);
    }
	
	public function getKunjungan()
    {
        return $this->hasOne(RawatKunjungan::className(), ['idkunjungan' => 'idkunjungan']);
    }

	public function getKunjungans()
    {
        return $this->hasOne(RawatKunjungan::className(), ['idkunjungan' => 'idkunjungan']);
    }

    /**
     * Gets query for [[Idruangan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuangan()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idruangan']);
    }
	public function getRawatstatus()
    {
        return $this->hasOne(RawatStatus::className(), ['id' => 'status']);
    }

    /**
     * Gets query for [[Idkelas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKelas()
    {
        return $this->hasOne(RuanganKelas::className(), ['id' => 'idkelas']);
    }
	public function getKelasnaik()
    {
        return $this->hasOne(RuanganKelas::className(), ['id' => 'idkelas_naik']);
    }
}
