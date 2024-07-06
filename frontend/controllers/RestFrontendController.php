<?php

namespace frontend\controllers;

use Yii;
use yii\filters\Cors;
use common\models\Poli;
use common\models\User;
use common\models\Rawat;
use common\models\Tarif;
use common\models\Daftar;
use common\models\Dokter;
use common\models\Pasien;
use common\models\Vaksin;
use common\models\Ruangan;
use common\models\RawatSpri;
use common\models\Transaksi;
use yii\helpers\ArrayHelper;
use common\models\RuanganBed;
use common\models\DokterKuota;
use common\models\DokterJadwal;
use common\models\PasienAlamat;
use common\models\RawatKunjungan;
use common\models\DokterSpesialis;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use common\models\TransaksiDetailRinci;

class RestFrontendController extends \yii\rest\Controller
{
    public static function allowedDomains()
    {
        return [
            '*',  // star allows all domains
            'http://localhost:3000',
        ];
    }
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'OPTIONS'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 260000, // Cache (seconds)
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Origin' => false,


                ],

            ],

        ]);
    }
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $headers = Yii::$app->request->headers;
            $token = $headers->get('x-token');
            $username = $headers->get('x-username');
            $user = \common\models\User::findByUsername($username);
            if ($user) {
                return true;
            } else {
                throw new \yii\web\UnauthorizedHttpException('User not found', 201);
                return false;
            }
        }
        throw new \yii\web\UnauthorizedHttpException('Error');
        return false;
    }
    public function actionAntrianPasien($tgl)
    {

        $arrjad = array();
        $jadwal = DokterJadwal::find()->where(['idhari' => date('N', strtotime($tgl))])->orderBy(['idhari'=>'SORT_ASC'])->andwhere(['<>', 'idpoli', 1])->all();

        foreach ($jadwal as $j) {
            $kuota = DokterKuota::find()->where(['iddokter' => $j->iddokter])->andwhere(['tgl' => $tgl])->one();
            if($kuota){
                $sisa = $kuota->sisa;
            }else{
                $sisa = $j->kuota;
            }
            $dokter = Dokter::findOne($j->iddokter);
            if($dokter->status == 1){
                array_push($arrjad, [
                'iddokter' => $j->iddokter,
                'jenis_kelamin' => $dokter->jenis_kelamin,
                'dokter' => $j->dokter->nama_dokter,
                'foto'=>$dokter->foto,
                'poli' => $j->poli->poli,
                'jam_selesai' => $j->jam_selesai,
                'jam_mulai' => $j->jam_mulai,
                'kuota' => $j->kuota,
                'sisa' => $sisa,
            ]);
            }
            
        }
        return $arrjad;
    }
    public function actionTempatTidurKelas($kelas){
        $ruangan = Ruangan::find()->where(['idjenis'=>1])->andwhere(['kode_kelas'=>$kelas])->all();
        $array = array();
        foreach($ruangan as $r){
            $bed = RuanganBed::find()->where(['idruangan'=>$r->id])->andwhere(['status'=>1])->count();
            $bedTerisi = RuanganBed::find()->where(['idruangan'=>$r->id])->andwhere(['terisi'=>1])->andwhere(['status'=>1])->count();
            array_push($array,[
                'id'=>$r->id,
                'ruangan'=>$r->nama_ruangan,
                'kelas'=>$r->idkelas,
                'bed'=>$bed,
                'terisi'=>$bedTerisi,
            ]);
        }
        return $array;
    }
    public function actionTempatTidur(){
        $ruangan = Ruangan::find()->where(['status'=>1])->all();
        $array = array();
        foreach($ruangan as $r){
            $bed = RuanganBed::find()->where(['idruangan'=>$r->id])->andwhere(['status'=>1])->count();
            $bedTerisi = RuanganBed::find()->where(['idruangan'=>$r->id])->andwhere(['terisi'=>1])->andwhere(['status'=>1])->count();
            array_push($array,[
                'id'=>$r->id,
                'ruangan'=>$r->nama_ruangan,
                'kelas'=>$r->idkelas,
                'bed'=>$bed,
                'terisi'=>$bedTerisi,
            ]);
        }
        return $array;
    }
    public function actionSpesialis(){
        $poli = DokterSpesialis::find()->all();
        $array = array();
        foreach ($poli as $pl) {
            
            array_push($array, [
                'spesialis' => $pl->spesialis,
                'kode' => $pl->ker,
            ]);
        }
        return $array;
    }
    public function actionPoli(){
        $poli = Poli::find()->all();
        $array = array();
        foreach ($poli as $pl) {            
            array_push($array, [
                'poli' => $pl->poli,
                'kode' => $pl->kode,
                'kode_antrean' => $pl->kode_antrean,
            ]);
        }
        return $array;
    }
    public function actionDokterSpesial($id){
        $spesial = DokterSpesialis::find()->where(['ker'=>$id])->one();
        $dokter = Dokter::find()->where(['idspesialis' => $spesial->id])->andWhere(['status' => 1])->all();
        $array = array();
        $no=1;
        foreach ($dokter as $dr) {
            $jadwal = DokterJadwal::find()->where(['iddokter' => $dr->id])->orderBy(['idhari'=>'SORT_ASC'])->all();
            $array_jadwal = array();
            foreach ($jadwal as $jd) {
                if ($jd->status == 1) {
                    $status = 'ada';
                } else {
                    $status = 'libur';
                }
                array_push($array_jadwal, [
                    'hari' => $jd->hari->hari,
                    'jam_mulai' => $jd->jam_mulai,
                    'jam_selesai' => $jd->jam_selesai,
                    'status' => $status,

                ]);
            }
            array_push($array, [
                
                'kode_dokter' => $dr->kode_dokter,
                'nama_dokter' => $dr->nama_dokter,
                'poli' => $dr->poli->poli,
                'foto' => $dr->foto,
                'jadwal' => $array_jadwal
            ]);
        }
        return $array;
    }
    public function actionDokterId($id){
        $dr = Dokter::find()->where(['kode_dokter' => $id])->andWhere(['status' => 1])->one();
            $array = array();
            $jadwal = DokterJadwal::find()->where(['iddokter' => $dr->id])->orderBy(['idhari'=>'SORT_ASC'])->all();
            $array_jadwal = array();
            foreach ($jadwal as $jd) {
                if ($jd->status == 1) {
                    $status = 'ada';
                } else {
                    $status = 'libur';
                }
                array_push($array_jadwal, [
                    'hari' => $jd->hari->hari,
                    'jam_mulai' => $jd->jam_mulai,
                    'jam_selesai' => $jd->jam_selesai,
                    'status' => $status,

                ]);
            }

           
        return [
            'kode_dokter' => $dr->kode_dokter,
                'nama_dokter' => $dr->nama_dokter,
                'sip' => $dr->sip,
                'poli' => $dr->poli->poli,
                'foto' => $dr->foto,
                'jadwal' => $array_jadwal,
                'bpjs' => $dr->bpjs,
        ];
    }
    public function actionDokterPoli($id)
    {
        $poli = Poli::find()->where(['id'=>$id])->one();
        $dokter = Dokter::find()->where(['idpoli' => $poli->id])->andWhere(['status' => 1])->all();
        $array = array();
        $no=1;
        foreach ($dokter as $dr) {
            $jadwal = DokterJadwal::find()->where(['iddokter' => $dr->id])->orderBy(['idhari'=>'SORT_ASC'])->all();
            $array_jadwal = array();
            foreach ($jadwal as $jd) {
                if ($jd->status == 1) {
                    $status = 'ada';
                } else {
                    $status = 'libur';
                }
                array_push($array_jadwal, [
                    'hari' => $jd->hari->hari,
                    'jam_mulai' => $jd->jam_mulai,
                    'jam_selesai' => $jd->jam_selesai,
                    'status' => $status,

                ]);
            }
            array_push($array, [
                'no'=>$no++,
                'kode_dokter' => $dr->kode_dokter,
                'nama_dokter' => $dr->nama_dokter,
                'poli' => $dr->poli->poli,
                'foto' => $dr->foto,
                'jadwal' => $array_jadwal
            ]);
        }
        return $array;
    }
    public function actionDokter()
    {
        
        $dokter = Dokter::find()->where(['status' => 1])->orderBy(['idpoli'=>SORT_DESC])->all();
        $array = array();
        $no=0;  
        foreach ($dokter as $dr) {
            if($dr->idspesialis != ''){
                $spesial = 'Dokter '.$dr->spesialis->spesialis;
            }else{
                $spesial = 'Dokter Umum';
            }
            $jadwal = DokterJadwal::find()->where(['iddokter' => $dr->id])->orderBy(['idhari'=>'SORT_ASC'])->all();
            $array_jadwal = array();
            foreach ($jadwal as $jd) {
                if ($jd->status == 1) {
                    $status = 'ada';
                } else {
                    $status = 'libur';
                }
                array_push($array_jadwal, [
                    'hari' => $jd->hari->hari,
                    'kuota' => $jd->kuota,
                    'jam_mulai' => $jd->jam_mulai,
                    'jam_selesai' => $jd->jam_selesai,
                    'status' => $status,

                ]);
            }
            array_push($array, [
                'no'=>$no++,
                'kode_dokter' => $dr->kode_dokter,
                'nama_dokter' => $dr->nama_dokter,
                'jenis_kelamin' => $dr->jenis_kelamin,
                'poli' => $dr->poli->poli,
                'spesialis' => $spesial,
                'sip' => $dr->sip,
                'foto' => $dr->foto,
                'jadwal' => $array_jadwal
            ]);
        }
        return $array;
    }
}
