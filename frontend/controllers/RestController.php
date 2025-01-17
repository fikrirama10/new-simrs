<?php
namespace frontend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\Daftar;
use common\models\Vaksin;
use common\models\Tarif;
use common\models\Poli;
use common\models\Dokter;
use common\models\DokterJadwal;
use common\models\DokterKuota;
use common\models\Rawat;
use common\models\RawatSpri;
use common\models\Pasien;
use common\models\PasienAlamat;
use common\models\PasienStatus;
use common\models\TransaksiDetailRinci;
use common\models\Transaksi;
use common\models\RawatKunjungan;
use common\models\User;
class RestController extends \yii\rest\Controller
{
	public static function allowedDomains()
{
    return [
       '*' ,  // star allows all domains
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
                    'Origin'=> static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST','GET','PUT','OPTIONS'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age'=> 260000,// Cache (seconds)
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Origin' => false,
					

                ],
				
            ],

        ]);
    }
    protected function verbs()
    {
       return [
           'index' => ['GET'],
           'antrian' => ['POST'],
           'status-antrian' => ['POST'],
       ];
    }
	public function beforeAction($action){
		if(parent::beforeAction($action)){
			$headers = Yii::$app->request->headers;
			$token = $headers->get('x-token');
			$username = $headers->get('x-username');
			$user = \common\models\User::findByUsername($username);
			if($user){
				return true;
			}else{
				throw new \yii\web\UnauthorizedHttpException('User not found',201);
					return false;
			}
		}
		throw new \yii\web\UnauthorizedHttpException('Error');
		return false;
	}
	function jenjang_usia($barulahir='',$usia_tahun){
		if($usia_tahun < 1){
			if($barulahir == 1){
				return 1;
			}else{
				return 2;
			}
		}else if($usia_tahun > 0 && $usia_tahun < 4 ){
			return 3;
		}else if($usia_tahun > 3 && $usia_tahun < 11 ){
			return 4;
		}else if($usia_tahun > 10 && $usia_tahun < 20 ){
			return 5;
		}else if($usia_tahun > 19 && $usia_tahun < 41 ){
			return 6;
		}else if($usia_tahun > 40 && $usia_tahun < 60 ){
			return 7;
		}else{
			return 8;
		}
	}

	function milliseconds() {
		date_default_timezone_set("Asia/Jakarta");
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
	
	function searchForId($dokter,$array) {
		//return $array;
	   foreach ($array['response'] as $key => $val) {
			//return $val;
		   if ($val['kodedokter'] == $dokter) {
				return true;
		   }
	   }
	   return false;
	}
	// function checkuserToken($header){
			// $cek = Yii::$app->kazo->cekJwt($headers->get('x-token'));
			// if($cek == 0){
				// return array(
					// 'metadata'=>array(
						// 'message'=>'Token Exprored',
						// 'code'=>201,
					// ),
				// );
			// }
		
	// }
	public function actionPasienBaru(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				$pasien = new Pasien();
				$pasien_alamat = new PasienAlamat();
				$pasien_status = new PasienStatus();
				$tglSep = date('Y-m-d');
				$nomorkartu = $arr->nomorkartu;
				$nik = $arr->nik;
				$nomorkk = $arr->nomorkk;
				$nama = $arr->nama;
				$jeniskelamin = $arr->jeniskelamin;
				$tanggallahir = $arr->tanggallahir;
				$nohp = $arr->nohp;
				$alamat = $arr->alamat;
				$kodeprop = $arr->kodeprop;
				$namaprop = $arr->namaprop;
				$kodedati2 = $arr->kodedati2;
				$namadati2 = $arr->namadati2;
				$kodekec = $arr->kodekec;
				$namakec = $arr->namakec;
				$kodekel = $arr->kodekel;
				$namakel = $arr->namakel;
				$rw = $arr->rw;
				$rt = $arr->rt;
				//insert pasien
				$tanggal = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$date1=date_create($pasien->tgllahir);
				$date2=date_create($tanggal);
				$diff=date_diff($date1,$date2);
				if($nik == null){
					return array(
						"metadata"=>array(
							"message"=>"NIK Kosong",
							"code"=>201
						),
					);
						
				}
				if($nomorkartu == null){
					return array(
						"metadata"=>array(
							"message"=>"No Kartu Kosong",
							"code"=>201
						),
					);
				}
				if(strlen($nomorkartu) != 13){
					return array(
						"metadata"=>array(
							"message"=>"Format Nomor Kartu Tidak Sesuai",
							"code"=>201
						),
					);
				}
				if(strlen($nik) != 16){
					return array(
						"metadata"=>array(
							"message"=>"Format NIK Tidak Sesuai ",
							"code"=>201
						),
					);
				}
				if($nomorkk == null){
					return array(
						"metadata"=>array(
							"message"=>"Nomor KK Belum Diisi",
							"code"=>201
						),
					);
				}
				if($nama == null){
					return array(
						"metadata"=>array(
							"message"=>"Nama Belum Diisi",
							"code"=>201
						),
					);
				}
				if($jeniskelamin == null){
					return array(
						"metadata"=>array(
							"message"=>"Jenis Kelamin Belum Dipilih",
							"code"=>201
						),
					);
				}
				if($tanggallahir == null){
					return array(
						"metadata"=>array(
							"message"=>"Tanggal Lahir Belum Diisi",
							"code"=>201
						),
					);
				}
				if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggallahir)) {
					$date3 = date_create($tanggallahir);
					$selisih=date_diff($date3,$date2);
					$selisih_hari = $selisih->format("%R%a");
					if($selisih_hari < 0){
						return array(
							"metadata"=>array(
								"message"=>" Format Tanggal Lahir Tidak Sesuai ",
								"code"=>201
							),
						);
					}
					
				}else{
					return array(
							"metadata"=>array(
								"message"=>" Format Tanggal Lahir Tidak Sesuai ",
								"code"=>201
							),
						);
				}
				
				if($alamat == null){
					return array(
						"metadata"=>array(
							"message"=>"Alamat Belum Diisi",
							"code"=>201
						),
					);
				}
				if($kodeprop == null){
					return array(
						"metadata"=>array(
							"message"=>"Kode Propinsi Belum Diisi",
							"code"=>201
						),
					);
				}
				if($namaprop == null){
					return array(
						"metadata"=>array(
							"message"=>"Nama Propinsi Belum Diisi",
							"code"=>201
						),
					);
				}
				if($kodedati2 == null){
					return array(
						"metadata"=>array(
							"message"=>"Kode dati 2 Belum Diisi",
							"code"=>201
						),
					);
				}
				if($namadati2 == null){
					return array(
						"metadata"=>array(
							"message"=>"Nama dati 2 Belum Diisi",
							"code"=>201
						),
					);
				}
				if($kodekec == null){
					return array(
						"metadata"=>array(
							"message"=>"Kode Kecamatan 2 Belum Diisi",
							"code"=>201
						),
					);
				}
				if($namakec == null){
					return array(
						"metadata"=>array(
							"message"=>"Nama Kecamatan   Belum Diisi",
							"code"=>201
						),
					);
				}
				
				if($kodekel == null){
					return array(
						"metadata"=>array(
							"message"=>"Kode Kelurahan Belum Diisi",
							"code"=>201
						),
					);
				}
				if($namakel == null){
					return array(
						"metadata"=>array(
							"message"=>"Nama Kelurahan Belum Diisi",
							"code"=>201
						),
					);
				}
				if($rt == null){
					return array(
						"metadata"=>array(
							"message"=>"RT Belum Diisi",
							"code"=>201
						),
					);
				}
				if($rw == null){
					return array(
						"metadata"=>array(
							"message"=>"RW Belum Diisi",
							"code"=>201
						),
					);
				}
				$cek_pasien = Pasien::find()->where(['no_bpjs'=>$nomorkartu])->andwhere(['nik'=>$nik])->one();
				if($cek_pasien){
					return array(
						"metadata"=>array(
							"message"=>"Data Peserta Sudah Pernah Dientrikan",
							"code"=>201
						),
					);
				}
				$pasien->no_bpjs = $nomorkartu;
				$pasien->nik = $nik;
				$pasien->nama_pasien = $nama;
				$pasien->nohp = $nohp;
				$pasien->tgllahir = $tanggallahir;
				$pasien->jenis_kelamin = $jeniskelamin;
				$pasien->status = 1;
				$pasien->tgldaftar = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$pasien->jamdaftar = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('H:i:s'))));
				$tanggal = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$date1=date_create($pasien->tgllahir);
				$date2=date_create($tanggal);
				$diff=date_diff($date1,$date2);
				$pasien->usia_tahun = $diff->format("%y");
				$pasien->usia_bulan = $diff->format("%m");
				$pasien->usia_hari = $diff->format("%d");
				$pasien->barulahir = 0;
				$pasien->baru_online = 1;
				$pasien->idusia = $this->jenjang_usia($pasien->barulahir,$pasien->usia_tahun);
				//cek pasien
				$cek_kartu= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/Peserta/nokartu/'.$nomorkartu.'/tglSEP/'.$tglSep,2);
				$data_json=json_decode($cek_kartu, true);
				$pasien->genKode();
				$pasien->no_rm = substr($pasien->kodepasien,2);
				$cek_rm = Pasien::find()->where(['no_rm'=>$pasien->no_rm])->count();
				if($cek_rm > 0){
					$rm_pasien =  (substr($pasien->no_rm,1));
					$norm = $rm_pasien+1;
					$rm = 0;
					$pasien->no_rm = $rm.$norm;
					$pasien->kodepasien = 'P-'.$pasien->no_rm;
				}

				if($pasien->save()){
					$pasien_alamat->alamat = $alamat;
					$pasien_alamat->idpasien = $pasien->id;
					$pasien_alamat->utama = 1;

					$pasien_status->idpasien = $pasien->id;
					$pasien_status->idstatus = 7;
					$pasien_status->no_rm = $pasien->no_rm;
					$pasien_status->save(false);

					$pasien_alamat->save(false);
					return array(
						"response"=>array(
							'norm'=>$pasien->no_rm,
						),
						"metadata"=>array(
							"message"=>"Harap datang ke admisi untuk melengkapi data rekam medis",
							"code"=>200
						),	

					);
				}
				
				
			}
		}
		public function actionPasienOperasi(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
				if($cek == 0){
					return array(
						'metadata'=>array(
							'message'=>'Token Expired',
							'code'=>201,
						),
					);
				}else if($cek == 2){
					return array(
						'metadata'=>array(
							'message'=>'Auth Failed',
							'code'=>201,
						),
					);
				}
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				date_default_timezone_set('UTC');
				$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
				$nopeserta = $arr->nopeserta;
				if($nopeserta == null){
					
					return array(
						"metadata"=>array(
							"message"=>"No Kartu Tidak Valid",
							"code"=>201
						),
					);	
				}
				$pasien = Pasien::find()->where(['no_bpjs'=>$nopeserta])->one();
				if($pasien){
					$operasi = RawatSpri::find()->where(['operasi'=>1])->andwhere(['no_rm'=>$pasien->no_rm])->all();
					$arrdip = array();
					if(count($operasi) > 0){
						foreach($operasi as $op){
							if($op->status == 1){
								$terlaksana = 0;
							}else{
								$terlaksana = 1;
							}
							array_push($arrdip,[
								'kodebooking'=>$op->no_spri,
								'tanggaloperasi'=>$op->tgl_rawat,
								'jenistindakan'=>$op->nama_tindakan,
								'kodepoli'=>$op->poli->kode,
								'namapoli'=>$op->poli->poli,
								'terlaksana'=>$terlaksana,
							]);
						}
					}
					return array(
						"response"=>array(
							'list'=>$arrdip,
						),
						"metadata"=>array(
							"message"=>"Ok",
							"code"=>200
						),	

					);
				}
				
				
			}
		}
		public function actionJadwalOperasi(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
				if($cek == 0){
					return array(
						'metadata'=>array(
							'message'=>'Token Expired',
							'code'=>201,
						),
					);
				}else if($cek == 2){
					return array(
						'metadata'=>array(
							'message'=>'Auth Failed',
							'code'=>201,
						),
					);
			}
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				date_default_timezone_set('UTC');
				$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
				$tanggalawal = $arr->tanggalawal;
				$tanggalakhir = $arr->tanggalakhir;
				$date1=date_create($tanggalawal);
				$date2=date_create($tanggalakhir);
				$diff=date_diff($date1,$date2);
				$selisih_hari = $diff->format("%R%a");
				$operasi = RawatSpri::find()->where(['operasi'=>1])->andwhere(['between','tgl_rawat',$tanggalawal,$tanggalakhir])->all();
				$arrdip = array();
				
				if($selisih_hari < 0){
					return array(
						"metadata"=>array(
							"message"=>"Tanggal Akhir Tidak Boleh Lebih Kecil dari Tanggal Awal ",
							"code"=>201
						),		
					);
				}
				if(count($operasi) > 0){
					foreach($operasi as $op){
						$pasien = Pasien::find()->where(['no_rm'=>$op->no_rm])->one();
						if($pasien){
							$bpjs = $pasien->no_bpjs;
						}else{
							$bpjs=0;
						}
						if($op->status == 1){
							$terlaksana = 0;
						}else{
							$terlaksana = 1;
						}
						array_push($arrdip,[
							'kodebooking'=>$op->no_spri,
							'tanggaloperasi'=>$op->tgl_rawat,
							'jenistindakan'=>$op->nama_tindakan,
							'kodepoli'=>$op->poli->kode,
							'namapoli'=>$op->poli->poli,
							'terlaksana'=>$terlaksana,
							'nopeserta'=>$bpjs,
							'lastupdate'=>$tStamp.'000',
						]);
					}
					return array(
						"response"=>array(
							'list'=>$arrdip,
						),
						"metadata"=>array(
							"message"=>"Ok",
							"code"=>200
						),	

					);
				}else{
					return array(
						"metadata"=>array(
							"message"=>"Data tidak ada",
							"code"=>201
						),	

					);
				}
			}
		}
		public function actionCheckin(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
			
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				$kodebooking = $arr->kodebooking;
				date_default_timezone_set("Asia/Jakarta");
				$waktu = $arr->waktu/1000;
				$waktu_sekarang = date('Y-m-d H:i',$waktu);
				$rawat = Rawat::find()->where(['idrawat'=>$kodebooking])->andwhere(['antrian_online'=>1])->andwhere(['<>','status',5])->one();
				$transaksi = new Transaksi();
				$transaksi_detail = new TransaksiDetailRinci();
				$kunjungan = new RawatKunjungan();
				if($rawat){
					
					$tanggal = date('Y-m-d',strtotime($waktu_sekarang));
					// return strtotime('2022-03-01 10:00:00');
					$masuk = date('Y-m-d',strtotime($rawat->tglmasuk));
					if($masuk != $tanggal){
						return (array(					
							"metadata"=>array(
								"message"=>"Check in hanya bisa dilakukan di hari H",
								"code"=>201
							),				
						));
					}
					if($rawat->checkin == 1){
						return (array(					
							"metadata"=>array(
								"message"=>"Peserta Sudah Check in",
								"code"=>201
							),				
						));
					}
					$rawat->checkin = 1;
					$rawat->timecheckin = $waktu;
					$kunjungan->genKode();
					$kunjungan->no_rm = $rawat->no_rm;
					$kunjungan->status = 1;
					$kunjungan->idpasien = $rawat->pasien->id;
					$kunjungan->usia_kunjungan = $rawat->pasien->usia_tahun;
					$kunjungan->hide = 0;
					$kunjungan->tgl_kunjungan = date('Y-m-d',strtotime($rawat->tglmasuk));
					$kunjungan->jam_kunjungan = date('H:i:s',strtotime($rawat->tglmasuk));
					$rawat->idkunjungan = $kunjungan->idkunjungan;
					if($rawat->save()){
						if($kunjungan->save(false)){
							$transaksi->genKode();
							$transaksi->idkunjungan = $kunjungan->id;
							$transaksi->kode_kunjungan = $kunjungan->idkunjungan;
							$transaksi->tgltransaksi = $rawat->tglmasuk;
							$transaksi->status = 1;
							$transaksi->no_rm = $rawat->no_rm;
							$transaksi->tgl_masuk = $kunjungan->tgl_kunjungan;
							$transaksi->idpasien = $kunjungan->idpasien;
							$transaksi->hide = 0;
							if($transaksi->save(false)){
								$tarif = Tarif::findOne(11);
								$transaksi_detail->idtransaksi = $transaksi->id;
								$transaksi_detail->idtarif = $tarif->id;
								$transaksi_detail->tarif = $tarif->tarif;
								$transaksi_detail->idrawat = $rawat->id;
								$transaksi_detail->tgl = $kunjungan->tgl_kunjungan;
								$transaksi_detail->idbayar = 2;
								$transaksi_detail->idpaket = 0;
								$transaksi_detail->iddokter = $rawat->iddokter;
								$transaksi_detail->save(false);
								
								
							}
						}
						$pasien_baru = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
						// if($pasien_baru->baru_online == 1){
								// $taks = array(
									// "kodebooking"=> $kodebooking,
									// "taskid"=> 2,
									// "waktu"=>  $this->milliseconds(),
									// );
								// $taksid = Yii::$app->hfis->update_taks($taks);
						// }
						
						return (array(					
							"metadata"=>array(
								"message"=>"Ok",
								"code"=>200
							),				
						));
					}
				}else{
					return (array(					
						"metadata"=>array(
							"message"=>"Antrean Tidak Ditemukan atau Sudah Dibatalkan",
							"code"=>201
						),				
					));
				}
				
			}
		}
		public function actionBatalAntrean(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				$kodebooking = $arr->kodebooking;
				$keterangan = $arr->keterangan;
				$rawat = Rawat::find()->where(['idrawat'=>$kodebooking])->andwhere(['antrian_online'=>1])->andwhere(['<>','status',5])->one();
				if($rawat){
					$hari = date('N',strtotime($rawat->tglmasuk));
					$rawatjalan = Rawat::find()->where(['idrawat'=>$kodebooking])->andwhere(['antrian_online'=>1])->andwhere(['idkunjungan'=>0])->one();
					$kuota = DokterKuota::find()->where(['idhari'=>$hari])->andwhere(['iddokter'=>$rawat->iddokter])->andwhere(['tgl'=>date('Y-m-d',strtotime($rawat->tglmasuk))])->one();
					if($rawatjalan){
						$rawat->status = 5;
						$rawat->keterangan = $keterangan;
						$kuota->sisa = $kuota->sisa +1;
						$kuota->terdaftar = $kuota->terdaftar - 1;
						$arbatal = array(
							'kodebooking'=>$kodebooking,
							'keterangan'=>$keterangan,
						);
						$batal= Yii::$app->hfis->batal_antrian($arbatal);
						$taks = array(
								"kodebooking"=> $kodebooking,
								"taskid"=> 99,
								"waktu"=> $this->milliseconds(),
							);
							
							
							//return $taksid;
						if($rawat->save()){
							$kuota->save();
							$taksid = Yii::$app->hfis->update_taks($taks);
							return (array(					
								"metadata"=>array(
									"message"=>"Ok",
									"code"=>200
								),				
							));
						}
					}else{
						return (array(					
							"metadata"=>array(
								"message"=>"Pasien Sudah Dilayani, Antrean Tidak Dapat Dibatalkan",
								"code"=>201
							),				
						));
					}
				}else{
					return (array(					
						"metadata"=>array(
							"message"=>"Antrean Tidak Ditemukan atau Sudah Dibatalkan",
							"code"=>201
						),				
					));
				}
				
			}
		}
		public function actionSisaAntrean(){
			
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
			$arr = json_decode(file_get_contents("php://input"));
			if (empty($arr)){ 
				exit("Data empty.");
			} else {
				$kodebooking = $arr->kodebooking;
				$rawat = Rawat::find()->where(['idrawat'=>$kodebooking])->andwhere(['antrian_online'=>1])->andwhere(['<>','status',5])->one();
				if($rawat){
					$hari = date('N',strtotime($rawat->tglmasuk));
					$kuota = DokterKuota::find()->where(['idhari'=>$hari])->andwhere(['iddokter'=>$rawat->iddokter])->andwhere(['tgl'=>date('Y-m-d',strtotime($rawat->tglmasuk))])->one();
					$rawatjalan = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['iddokter'=>$rawat->iddokter])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$rawat->tglmasuk])->andwhere(['status'=>1])->orderBy(['id'=>SORT_ASC])->one();

					return (array(
					"response"=>array(
						"nomorantrean"=>$rawat->poli->kode_antrean.'-'.substr($rawat->no_antrian,-3),
						"namapoli"=>$rawat->poli->poli,
						"namadokter"=>$rawat->dokter->nama_dokter,
						"sisaantrean"=>$kuota->sisa,
						"antreanpanggil"=>$rawat->poli->kode_antrean.'-'.substr($rawat->no_antrian,-3),
						"waktutunggu"=>9000,
						"keterangan"=>"",
					),
					"metadata"=>array(
						"message"=>"Ok",
						"code"=>200
					),				
				));
				}else{
					return (array(
						"metadata"=>array(
							"message"=>"Kode Booking tidak ditemukan",
							"code"=>201
						),				
					));
				}
				
			}
		}
		public function actionStatusAntrean(){
		$headers = Yii::$app->request->headers;
		$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
		
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)){ 
		exit("Data empty.");
		} else {
			
			$kodepoli =  $arr->kodepoli;
			$kodedokter =  $arr->kodedokter;
			$tanggalperiksa =  $arr->tanggalperiksa;
			$jampraktek =  $arr->jampraktek;
			//Kode dan dokter kosong
			if($kodepoli && $kodedokter){
				
				$hari = date('N',strtotime($tanggalperiksa));
				$poli = Poli::find()->where(['kode'=>$kodepoli])->one();
				//Poli ditemukan
				if($poli){
					//Format Tanggal
					if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggalperiksa)) {
						$date2=date_create($tanggalperiksa);
						$date1=date_create(date('Y-m-d'));
						$diff=date_diff($date1,$date2);
						$selisih_hari = $diff->format("%R%a");
						if($selisih_hari >= 0){
							$dokter = Dokter::find()->where(['kode_dpjp'=>$kodedokter])->andwhere(['idpoli'=>$poli])->one();
						//Dokter Ditemukan
						if($dokter){
							$jadwal = DokterJadwal::find()->where(['idhari'=>$hari])->andwhere(['iddokter'=>$dokter->id])->andwhere(['status'=>1])->one();
								if($jadwal){
									$kuota = DokterKuota::find()->where(['idhari'=>$hari])->andwhere(['tgl'=>$tanggalperiksa])->andwhere(['iddokter'=>$dokter->id])->one();
									$rawat = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['iddokter'=>$dokter->id])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tanggalperiksa])->andwhere(['status'=>1])->orderBy(['id'=>SORT_ASC])->one();
									if($rawat){
										$ater = $poli->kode_antrean.'-'.substr($rawat->no_antrian,-3);
									}else{
										$ater ='';
									}
									if($kuota){
										return (array(
											"response"=>array(
												"namapoli"=>$poli->poli,
												"namadokter"=>$dokter->nama_dokter,
												"totalantrean"=> $kuota->terdaftar,
												"sisaantrean"=>$kuota->sisa,
												"antreanpanggil"=>$ater,
												"sisakuotajkn"=>$kuota->sisa,
												"kuotajkn"=>$kuota->kuota,
												"sisakuotanonjkn"=>$kuota->sisa,
												"kuotanonjkn"=>$kuota->kuota,
												"keterangan"=>"-",
											),
											"metadata"=>array(
												"message"=>"OK",
												"code"=>200
											),				
										));	
									}else{
										return (array(
											"response"=>array(
												"namapoli"=>$poli->poli,
												"namadokter"=>$dokter->nama_dokter,
												"totalantrean"=> 0,
												"sisaantrean"=>$jadwal->kuota,
												"antreanpanggil"=>'',
												"sisakuotajkn"=>$jadwal->kuota,
												"kuotajkn"=>$jadwal->kuota,
												"sisakuotanonjkn"=>$jadwal->kuota,
												"kuotanonjkn"=>$jadwal->kuota,
												"keterangan"=>"-",
											),
											"metadata"=>array(
												"message"=>"OK",
												"code"=>200
											),				
										));	
									}
								}else{
									return (array(
											"metadata"=>array(
												"message"=>"Jadwal Tidak ada",
												"code"=>201
											),				
										));	
								}
							}else{
								return (array(
											"metadata"=>array(
												"message"=>"Dokter Tidak sesuai",
												"code"=>201
											),				
										));	
							}
						}else{
							return (array(
								"metadata"=>array(
									"message"=>"Tanggal Periksa Tidak Berlaku",
									"code"=>201
								),				
							));	
						}
						
					}else{
						return (array(
							"metadata"=>array(
								"message"=>"Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd",
								"code"=>201
							),				
						));	
					}

					
				}else{
					return (array(
						"metadata"=>array(
							"message"=>"Poli Tidak Ditemukan",
							"code"=>201
						),				
					));	
				}

				
			}else{
				return (array(
					"metadata"=>array(
						"message"=>"Periksa Inputan anda",
						"code"=>201
					),				
				));	
			}
				 
		}
	}
	
	public function actionAntrean(){
			$headers = Yii::$app->request->headers;
			$cek = Yii::$app->kazo->cekJwt($headers->get('x-token'),$headers->get('x-username'));
			if($cek == 0){
				return array(
					'metadata'=>array(
						'message'=>'Token Expired',
						'code'=>201,
					),
				);
			}else if($cek == 2){
				return array(
					'metadata'=>array(
						'message'=>'Auth Failed',
						'code'=>201,
					),
				);
			}
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)){ 
		exit("Data empty.");
		}else{
			$nomorkartu =  $arr->nomorkartu;
			$nik =  $arr->nik;
			$nohp =  $arr->nohp;
			$kodepoli =  $arr->kodepoli;
			$norm =  $arr->norm;
			$tanggalperiksa =  $arr->tanggalperiksa;
			$kodedokter =  $arr->kodedokter;
			$jampraktek =  $arr->jampraktek;
			$jeniskunjungan =  $arr->jeniskunjungan;
			$nomorreferensi =  $arr->nomorreferensi;
			// $response= Yii::$app->vclaim->get_peserta($nomorkartu,$tanggalperiksa);
			// $json = json_encode($response, true);
			$pasien = Pasien::find()->where(['no_bpjs'=>$nomorkartu])->andwhere(['no_rm'=>$norm])->one();
			$jam = explode("-", $jampraktek);
			$jam_tutup = $jam[1];
			$jam_buka = $jam[0];
			// $tambah = (25) ;
			// $plus = strtotime('+ hour');
			// $time_sekarang = time();
			// date_default_timezone_set("Asia/Jakarta");
			// $jam_dilayani = date("H:i:s", strtotime("+".$tambah." minutes", strtotime($jam_buka)));
			// $dilayani = date('Y-m-d H:i:s',strtotime($tanggalperiksa.' '.$jam_dilayani));
			// $fix = strtotime($dilayani,strtotime('-7 hour')).'000';
			// return $fix;
			//return $kodedokter;
		
		// return strtotime($dilayani);
			//return $jam_tutup;
			if($pasien){
				$date2=date_create($tanggalperiksa);
				$date1=date_create(date('Y-m-d'));
				$diff=date_diff($date1,$date2);
				$selisih_hari = $diff->format("%R%a");
				$poli = Poli::find()->where(['kode'=>$kodepoli])->one();
				
				if($poli){
					$dokter = Dokter::find()->where(['kode_dpjp'=>$kodedokter])->andwhere(['idpoli'=>$poli->id])->one();
					//return $dokter; 
					// $response= Yii::$app->hfis->get_jadwaldokter($kodepoli,$tanggalperiksa);
					
					// $cek = $this->searchForId($kodedokter,$response);
					// return $cek; 
					if($dokter){
					$rawat = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['<>','status',5])->andwhere(['no_rm'=>$norm])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tanggalperiksa])->andwhere(['idpoli'=>$poli->id])->count();				
						if($selisih_hari < 0){
							return (array(
								"metadata"=>array(
									"message"=>"Tanggal Periksa Tidak Berlaku",
									"code"=>201
								),				
							));	
						}else{
							if($rawat > 0){
								return (array(
									"metadata"=>array(
										"message"=>"Nomor Antrean Hanya Dapat Diambil 1 Kali Pada Tanggal Yang Sama",
										"code"=>201
									),				
								));	
							}else{
								$jadwal_poli= Yii::$app->hfis->get_jadwalcount($kodepoli,$tanggalperiksa);
								if($jadwal_poli['jadwal'] < 1){
									return (array(
										"metadata"=>array(
											"message"=>"Pendaftaran ke Poli Ini Sedang Tutup",
											"code"=>201
										),				
									));	
								}else{
									$response= Yii::$app->hfis->get_jadwaldokter($kodepoli,$tanggalperiksa);
									$cek = $this->searchForId($kodedokter,$response);
									// return $cek;
									if($cek == false){
										// return $cek;
										return (array(
											"metadata"=>array(
												"message"=>"Jadwal Dokter ".$dokter->nama_dokter." Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya",
												"code"=>201
											),				
										));	
									}else{
										
										$tgl = date('Y-m-d',strtotime($tanggalperiksa));
										$hari = date('N',strtotime($tanggalperiksa));
										$tanggal = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
										$tanggall = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
										if($tgl == $tanggall){
											$dayy = strtotime(date("Y-m-d H:i:s"));
											$jam_ditutup = date('H:i',strtotime('-7 hour',strtotime($jam_tutup)));
											$tutup = date('Y-m-d H:i:s',strtotime($tgl.' '.$jam_ditutup.':'.date('s')));
											$day2 = strtotime(date('Y-m-d H:i:s',strtotime($tutup)));
											// return $dayy.'-'.$day2;
											if($dayy > $day2){
												return (array(
													"metadata"=>array(
														"message"=>"Pendaftaran Ke Poli ".$poli->poli." Sudah Tutup Jam ".$jam_tutup,
														"code"=>201
													),				
												));	
											}
										}
										$date1=date_create($pasien->tgllahir);
										$date2=date_create($tanggal);
										$diff=date_diff($date1,$date2);
										$pelayanan = new Rawat();
										$pelayanan->tglmasuk = $tanggalperiksa.' '.date('G:i:s',strtotime('+6 hour',strtotime(date('G:i:s'))));
										//$pelayanan->idkunjungan = $new_kunjungan->idkunjungan;
										$pelayanan->idjenisrawat = 1;
										$pelayanan->idkunjungan = 0;
										$pelayanan->idpoli = $poli->id;
										$pelayanan->iddokter = $dokter->id;
										$pelayanan->status = 1;
										$pelayanan->idruangan = 2;
										$pelayanan->idbayar = 2;
										$pelayanan->no_rm = $norm;
										$pelayanan->antrian_online = 1;
										$pelayanan->checkin = 0;
										$pelayanan->genAntri($pelayanan->idpoli,$pelayanan->iddokter,$pelayanan->anggota,date('Y-m-d',strtotime($pelayanan->tglmasuk)));
										$pelayanan->genKode($pelayanan->idjenisrawat);
										$tglmasuk = date('Y-m-d',strtotime($pelayanan->tglmasuk));
										$hitung_antrian = ltrim(substr($pelayanan->no_antrian,-3), '0');
										if($jeniskunjungan == 3){
											$pelayanan->kunjungan = 2;
											$pelayanan->no_suratkontrol = $nomorreferensi;
										}else{
											$pelayanan->kunjungan = 1;
											$pelayanan->no_rujukan = $nomorreferensi;
										}
										$tambah = (25*$hitung_antrian) ;
										$plus = strtotime('+ hour');
										$time_sekarang = time();
										date_default_timezone_set("Asia/Jakarta");
										$jam_dilayani = date("H:i:s", strtotime("+".$tambah." minutes", strtotime($jam_buka)));
										$dilayani = date('Y-m-d H:i:s',strtotime($tanggalperiksa.' '.$jam_dilayani));
										$fix = strtotime($dilayani,strtotime('-7 hour')).'000';
										$dokter_kuota = DokterKuota::find()->where(['tgl'=>$tanggalperiksa])->andwhere(['iddokter'=>$dokter->id])->andwhere(['idpoli'=>$poli->id])->one();
										$jadwal2 = DokterJadwal::find()->where(['iddokter'=>$dokter->id])->andwhere(['idhari'=>$hari])->one();
										if($dokter_kuota){
											if($dokter_kuota->sisa < 1){
												return (array(
													"metadata"=>array(
														"message"=>"Kuota Sudah Habis",
														"code"=>201
													),				
												));	
											}else{
												$dokter_kuota->terdaftar = $dokter_kuota->terdaftar + 1;
												$dokter_kuota->sisa = $dokter_kuota->kuota - $dokter_kuota->terdaftar;
												
													// return $addAntri;
												// $antrian = Yii::$app->hfis->add_antrian($addAntri);
												// return $antrian;
												
													$pasien_baru = Pasien::find()->where(['no_rm'=>$norm])->one();
													
													if($dokter_kuota->save()){
													
													if($pelayanan->save(false)){
														
																return (array(
																	"response"=>array(
																		"nomorantrean"=> $poli->kode_antrean.'-'.substr($pelayanan->no_antrian,-3),
																		"angkaantrean"=> (int) ltrim(substr($pelayanan->no_antrian,-3), '0'),
																		"kodebooking"=> $pelayanan->idrawat,
																		"norm"=> $pelayanan->no_rm,
																		"namapoli"=> $poli->poli,
																		"namadokter"=> $pelayanan->dokter->nama_dokter,
																		"estimasidilayani"=> (int) $fix,
																		"sisakuotajkn"=> $dokter_kuota->sisa,
																		"kuotajkn"=> $dokter_kuota->kuota,
																		"sisakuotanonjkn"=> $dokter_kuota->sisa,
																		"kuotanonjkn"=> $dokter_kuota->kuota,
																		"keterangan"=> "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
																	),
																	"metadata"=>array(
																		"message"=>"Ok",
																		"code"=>200
																	),		
																	// 'cek'=>array(
																		// 'antrian'=>$antrian,
																	// ),
																));	
															}
														
													}
												

											}
											
										}else{
											$newkuota = new DokterKuota();
											$newkuota->iddokter = $pelayanan->iddokter;
											$newkuota->idpoli = $pelayanan->idpoli;
											$newkuota->idhari = $hari;
											$newkuota->tgl = $tgl;
											$newkuota->kuota = $jadwal2->kuota;
											$newkuota->sisa = $jadwal2->kuota - 1;
											$newkuota->terdaftar = 1;
											$newkuota->status = 1;
											$newkuota->save();
											$pasien_kuota = DokterKuota::find()->where(['tgl'=>$tanggalperiksa])->andwhere(['iddokter'=>$dokter->id])->andwhere(['idpoli'=>$poli->id])->one();
												
												
													
														if($pelayanan->save(false)){
																// $addAntri = array(
																	// "kodebooking"=> $pelayanan->idrawat,
																	// "jenispasien"=> "JKN",
																	// "nomorkartu"=> $nomorkartu,
																	// "nik"=> $nik,
																	// "nohp"=> $nohp,
																	// "kodepoli"=> $kodepoli,
																	// "namapoli"=> $poli->poli,
																	// "pasienbaru"=> 0,
																	// "norm"=> $norm,
																	// "tanggalperiksa"=> $tanggalperiksa,
																	// "kodedokter"=> $kodedokter,
																	// "namadokter"=> $dokter->nama_dokter,
																	// "jampraktek"=> $jampraktek,
																	// "jeniskunjungan"=> $jeniskunjungan,
																	// "nomorreferensi"=> $nomorreferensi,
																	// "nomorantrean"=> $pelayanan->poli->kode_antrean.'-'.substr($pelayanan->no_antrian,-3),
																	// "angkaantrean"=>  (int)  ltrim(substr($pelayanan->no_antrian,-3), '0'),
																	// "estimasidilayani"=>(int) $dilayani,
																	// "sisakuotajkn"=>  $pasien_kuota->sisa,
																	// "kuotajkn"=> $pasien_kuota->kuota,
																	// "sisakuotanonjkn"=> $pasien_kuota->sisa,
																	// "kuotanonjkn"=> $pasien_kuota->kuota,
																	// "keterangan"=> "Peserta harap 30 menit lebih awal guna pencatatan administrasi.",
																// );
																// return $addAntri;
																//$antrian = Yii::$app->hfis->add_antrian($addAntri);
																$pasien_baru = Pasien::find()->where(['no_rm'=>$norm])->one();
																// if($pasien_baru->baru_online == 1){
																	// $tskid = 1;
																// }else{
																	// $tskid = 3;
																// }
																// $taks = array(
																	// "kodebooking"=> $pelayanan->idrawat,
																	// "taskid"=> $tskid,
																	// "waktu"=>  $this->milliseconds(),
																// );
																// $taksid = Yii::$app->hfis->update_taks($taks);
																	return (array( 
																		"response"=>array(
																			"nomorantrean"=> $poli->kode_antrean.'-'.substr($pelayanan->no_antrian,-3),
																			"angkaantrean"=>  (int) ltrim(substr($pelayanan->no_antrian,-3), '0'),
																			"kodebooking"=> $pelayanan->idrawat,
																			"norm"=> $pelayanan->no_rm,
																			"namapoli"=> $poli->poli,
																			"namadokter"=> $pelayanan->dokter->nama_dokter,
																			"estimasidilayani"=> (int) $fix,
																			"sisakuotajkn"=> $pasien_kuota->sisa,
																			"kuotajkn"=> $pasien_kuota->kuota,
																			"sisakuotanonjkn"=> $pasien_kuota->sisa,
																			"kuotanonjkn"=> $pasien_kuota->kuota,
																			"keterangan"=> "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
																		),
																		"metadata"=>array(
																			"message"=>"Ok",
																			"code"=>200
																		),
																		// "cek_rs"=>array(
																			// 'antrian'=>$addAntri,
																		// ),
																	));	
														}
												
											
										}
										
										
										

									}
								}
							}					
						}
					}else{
						return (array(
								"metadata"=>array(
								"message"=>"Poli dan dokter tidak sesuai",
								"code"=>201
							),				
						));	
					}
				}else{
					return (array(
							"metadata"=>array(
							"message"=>"Poli Tidak Ditemukan",
							"code"=>201
						),				
					));	
				}
				


			}else{
				 return (array(
					"metadata"=>array(
						"message"=>"Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru",
						"code"=>202
					),				
				));	
			}

		}
	}
	
}