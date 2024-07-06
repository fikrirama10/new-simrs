<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\Pasien;
use common\models\Rawat;
use common\models\Poli;
use common\models\KategoriPenyakit;
use common\models\KategoriPenyakitMulut;
use common\models\DataJenjangusia;
use common\models\RawatStatus;
use common\models\DataKelahiran;
use common\models\KategoriDiagnosa;
use common\models\PasienKategori;
use common\models\KegiatanUgd;
use common\models\LaboratoriumLayanan;
use common\models\LaboratoriumPemeriksaan;
use common\models\LaboratoriumHasildetail;
use common\models\Radiologi;
use common\models\RadiologiPelayanan;
use common\models\RadiologiHasildetail;
use common\models\RadiologiTindakan;
use common\models\RawatBayar;
use common\models\RawatJenis;

class KunjunganRestController extends \yii\rest\Controller
{
	public static function allowedDomains()
	{
		return [
		   '*' ,  // star allows all domains
		   'http://localhost:3000',
		];
	}  
	
	public $enableCsrfValidation = false;
		public function actionStatistikPerpoli($bulan,$tahun,$poli){
		$polii = Poli::findOne($poli);
		$rawat_umum = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>1])->count();
		$rawat_bpjs = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>2])->count();
		$kalender = CAL_GREGORIAN;
		$hari = cal_days_in_month($kalender,$bulan,$tahun);
		$arhar = array();
		$array_bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun','07'=>'Jul','08'=>'Ags','09'=>'Sep','10'=>'Okt', '11'=>'Nov','12'=>'Des');
		$bulann = $array_bulan[$bulan];
		for($a = 1;$a < $hari+1 ;$a++){
			
			$rawathari_umum = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['DAY(tglmasuk)'=>$a])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>1])->count();
			$rawathari_bpjs = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['DAY(tglmasuk)'=>$a])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>2])->count();
			
			
			array_push($arhar,[
				'hari'=>$a.''.$bulann,
				'umum'=>$rawathari_umum,
				'bpjs'=>$rawathari_bpjs,
				'total'=>$rawathari_bpjs + $rawathari_umum,
			]);
		}
		$kategori = PasienKategori::find()->all();
		$arrkat = array();
		foreach($kategori as $kat){
			$rawatjumlah = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kat->id])->count();
			array_push($arrkat,[
				'id'=>$kat->id,
				'kategori'=>$kat->nama,
				'jumlah'=>$rawatjumlah,
			]);
		}
		
		$usia = DataJenjangusia::find()->all();
		$arrusia = array();
		foreach($usia as $u){
			$usiajumlah = Rawat::find()->joinWith(['pasien as pasien'])->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['idpoli'=>$poli])->andwhere(['<>','rawat.status',5])->andwhere(['pasien.idusia'=>$u->id])->count();
			array_push($arrusia,[
				'id'=>$u->id,
				'jenjang'=>$u->jenjang,
				'jumlah'=>$usiajumlah,
			]);
		}
		return [
			'poli'=>$poli,
			'rawat_umum'=>$rawat_umum,
			'rawat_bpjs'=>$rawat_bpjs,			
			'hari'=>$arhar,	
			'kategori'=>$arrkat,
			'usia'=>$arrusia,
		];
	}
	public function actionStatistikTahunan($tahun){
		$label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
		$poli = Poli::find()->all();
		$arrpol = array();
		foreach($poli as $p){
				$arrawat=array();
				for($bulan = 1;$bulan < 13;$bulan++){
					$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
					$bulann = $array_bulan[$bulan];
					$rawat = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['idpoli'=>$p->id])->andwhere(['<>','status',5])->count();
					array_push($arrawat,[
						'Jumlah'=>$rawat,
						'Bulan'=>$bulann,
					]);
				}
			
			array_push($arrpol,[
				'id'=>$p->id,
				'poli'=>$p->poli,
				'Jumlah'=>$arrawat,
			]);
		}
		return [
			'poli'=>$arrpol,
			'label'=>$label,
		];
	}
	public function actionJenisRawat($start='',$end=''){
		$rawat = RawatJenis::find()->all();
		$arrdip = array();
		foreach($rawat as $r){
			// $rawatBayar = RawatBayar::find()->all();
			// $arrbayar = array();
			// foreach($rawatBayar as $rb){
				$model_bpjs = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['idjenisrawat'=>$r->id])->andwhere(['hide'=>0])->andwhere(['idbayar'=>2])->andwhere(['<>','status',5])->count();
				$model_umum = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['idjenisrawat'=>$r->id])->andwhere(['hide'=>0])->andwhere(['idbayar'=>1])->andwhere(['<>','status',5])->count();
				// array_push($arrbayar,[
					// 'bayar'=>$rb->bayar,
					// 'jumlah'=>$model,
				// ]);
			// }
			
			array_push($arrdip,[
				'id'=>$r->id,
				'jenis'=>$r->jenis,
				'bpjs'=>$model_bpjs,
				'umum'=>$model_umum,
			]);
		}
		return $arrdip;
	}
	public function actionRawatPoli($start='',$end=''){
		$rawat = Poli::find()->where(['ket'=>1])->all();
		$arrdip = array();
		foreach($rawat as $r){
			$model_bpjs = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['idpoli'=>$r->id])->andwhere(['idbayar'=>2])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->count();
			$model_umum = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['idpoli'=>$r->id])->andwhere(['idbayar'=>1])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->count();
			
			array_push($arrdip,[
				'id'=>$r->id,
				'jenis'=>$r->poli,
				'bpjs'=>$model_bpjs,
				'umum'=>$model_umum,
			]);
		}
		return $arrdip;
	}
	public function actionFindPasien($rm){
		$pasien = Pasien::find()->where(['no_rm'=>$rm])->one();
		return[
		'response'=>array(
			'Pasien'=>array(
				'nama_pasien'=>$pasien->nama_pasien, 
				'no_rm'=>$pasien->no_rm,
			),),
		];
	}

	public function actionJumlahKunjungan($tahun){
		 $bayar = RawatBayar::find()->all();
		 $arrba = array();
		 foreach($bayar as $b){
			$model = Rawat::find()->select(['rawat.*','DATE_FORMAT(tglmasuk,"%m") AS bulan'])->andwhere(['DATE_FORMAT(tglmasuk,"%Y")'=>$tahun])->andwhere(['idbayar'=>$b->id])->andwhere(['<>','rawat.status',5])->andwhere(['hide'=>0])->groupBy('bulan')->all();
			 $arrdip = array();
			 foreach ($model as $q){
				$laki = Rawat::find()->joinWith(['pasien as pasien'])->where(['jenis_kelamin'=>'L'])->andwhere(['hide'=>0])->andwhere(['DATE_FORMAT(tglmasuk,"%m")'=>date('m',strtotime($q->tglmasuk))])->andwhere(['idbayar'=>$b->id])->andwhere(['DATE_FORMAT(tglmasuk,"%Y")'=>$tahun])->andwhere(['<>','rawat.status',5])->count();
				$perempuan = Rawat::find()->joinWith(['pasien as pasien'])->where(['jenis_kelamin'=>'P'])->andwhere(['hide'=>0])->andwhere(['DATE_FORMAT(tglmasuk,"%m")'=>date('m',strtotime($q->tglmasuk))])->andwhere(['idbayar'=>$b->id])->andwhere(['DATE_FORMAT(tglmasuk,"%Y")'=>$tahun])->andwhere(['<>','rawat.status',5])->count();
				array_push($arrdip,[
					'bulan' => date('F',strtotime($q->tglmasuk)),
					'laki' => $laki,
					'perempuan' => $perempuan,
				]);
			 }
			array_push($arrba,[
				'bayar'=>$b->bayar,
				'kunjungan'=>$arrdip,
			]); 
		 }
		 
		 return $arrba;
	}
	
	
	//Kelahiran UGD	
	public function actionKelahiran($start='',$end=''){
		$rawat = DataKelahiran::find()->all();
		$arrdip=array();
		foreach ($rawat as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			foreach($katpasien as $kp):
				$jumlah = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['melahirkan'=>$q->id])->andwhere(['kat_pasien'=>$kp->id])->count();
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,
					'jumlah'=>$jumlah,
				]);
			endforeach;
			array_push($arrdip,[
				'id' => $q->id,
				'kelahiran' => $q->kelahiran,
				'katPasien'=>$arrdip2,
			]);
		}
		return $arrdip;
	}
	function stripp($params){
		if($params < 1){
			return '-';
		}else{
			return $params;
		}
	}
	public function actionMacamPenyakit($start='',$end=''){
		$diagnosa = KategoriDiagnosa::find()->all();
		$arrdip=array();
		foreach ($diagnosa as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			$jumlah = 0;
			foreach($katpasien as $kp){
				$rawat = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['kat_penyakit'=>$q->id])->count();			
				$jumlah += $rawat ;
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,					
					'jumlah' => $this->stripp($rawat),					
				]);
			}
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->jenisdiagnosa,
				'katPasien'=>$arrdip2,
				'total' => $this->stripp($jumlah),	
			]);
		}
		return $arrdip;
	}
	public function actionPelayananRawatJalan($start='',$end=''){
		$poli = Poli::find()->orderBy(['spesialis'=>SORT_ASC])->all();
		$arrdip=array();
		foreach ($poli as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			$jumlahlama = 0;
			$jumlahbaru = 0;
			foreach($katpasien as $kp){
				$baru = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['hide'=>0])->andwhere(['idpoli'=>$q->id])->andwhere(['kunjungan'=>1])->count();			
				$lama = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['hide'=>0])->andwhere(['kunjungan'=>2])->andwhere(['idpoli'=>$q->id])->count();			
				$jumlahlama += $lama ;
				$jumlahbaru += $baru ;
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,					
					'baru' => $this->stripp($baru),					
					'lama' => $this->stripp($lama),					
				]);
			}
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->poli,
				'katPasien'=>$arrdip2,
				'totallama' => $this->stripp($jumlahlama),	
				'totalbaru' => $this->stripp($jumlahbaru),	
			]);
		}
		return $arrdip;
	}
	public function actionPelayananRawatInap($start='',$end=''){
		$poli = KategoriPenyakit::find()->all();
		$arrdip=array();
		foreach ($poli as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			$jumlah = 0;
			foreach($katpasien as $kp){
				$ranap = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['idjenisperawatan'=>$q->id])->count();	
				$jumlah += $ranap ;
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,					
					'jumlah' => $this->stripp($ranap),					
				]);
			}
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->kategori,
				'katPasien'=>$arrdip2,
				'total' => $this->stripp($jumlah),	
			]);
		}
		return $arrdip;
	}
	//Kunjungan Gigi
	public function actionKunjunganGigi($start='',$end=''){
		$arrdip2=array();
		$arrdip=array();
		$katpasien = PasienKategori::find()->all();
		$semua = 0;
		$lama = 0;
		$baru = 0;
		foreach($katpasien as $kp){
			$kunjungan = Rawat::find()->where(['idpoli'=>3])->andwhere(['hide'=>0])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->count();
			$kunjungan_baru = Rawat::find()->where(['idpoli'=>3])->andwhere(['hide'=>0])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['kunjungan'=>1])->count();
			$kunjungan_lama = Rawat::find()->where(['idpoli'=>3])->andwhere(['hide'=>0])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['kunjungan'=>2])->count();
			$semua += $kunjungan;
			$lama += $kunjungan_lama;
			$baru += $kunjungan_baru;
			array_push($arrdip,[
					'id' => $kp->id,
					'nama' => $kp->nama,
					'kunjungan'=>$kunjungan,
					'kunjungan_baru'=>$kunjungan_baru,
					'kunjungan_lama'=>$kunjungan_lama,
				]);
		}
				array_push($arrdip2,[
					'arrdip'=>$arrdip,
					'semua'=>$semua,
					'lama'=>$lama,
					'baru'=>$baru,
				]);
		return $arrdip2;
		
	}
	//Kunjungan Poli
	public function actionKunjunganPoli($start='',$end=''){
		$arrdip2=array();
		$arrdip=array();
		$katpasien = PasienKategori::find()->all();
		$semua = 0;
		$lama = 0;
		$baru = 0;
		foreach($katpasien as $kp){
			$kunjungan = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->count();
			$kunjungan_baru = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['kunjungan'=>1])->count();
			$kunjungan_lama = Rawat::find()->where(['idjenisrawat'=>1])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['kunjungan'=>2])->count();
			$semua += $kunjungan;
			$lama += $kunjungan_lama;
			$baru += $kunjungan_baru;
			array_push($arrdip,[
					'id' => $kp->id,
					'nama' => $kp->nama,
					'kunjungan'=>$kunjungan,
					'kunjungan_baru'=>$kunjungan_baru,
					'kunjungan_lama'=>$kunjungan_lama,
				]);
		}
				array_push($arrdip2,[
					'arrdip'=>$arrdip,
					'semua'=>$semua,
					'lama'=>$lama,
					'baru'=>$baru,
				]);
		return $arrdip2;
		
	}
	//Kunjungan UGD
	public function actionKunjunganUgd($start='',$end=''){
		$arrdip2=array();
		$arrdip=array();
		$katpasien = PasienKategori::find()->all();
		$semua = 0;
		$lama = 0;
		$baru = 0;
		foreach($katpasien as $kp){
			$kunjungan = Rawat::find()->where(['idjenisrawat'=>3])->andwhere(['hide'=>0])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['<>','status',5])->count();
			$kunjungan_baru = Rawat::find()->where(['idjenisrawat'=>3])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['<>','status',5])->andwhere(['kunjungan'=>1])->count();
			$kunjungan_lama = Rawat::find()->where(['idjenisrawat'=>3])->andwhere(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['<>','status',5])->andwhere(['kunjungan'=>2])->count();
			$semua += $kunjungan;
			$lama += $kunjungan_lama;
			$baru += $kunjungan_baru;
			array_push($arrdip,[
					'id' => $kp->id,
					'nama' => $kp->nama,
					'kunjungan'=>$kunjungan,
					'kunjungan_baru'=>$kunjungan_baru,
					'kunjungan_lama'=>$kunjungan_lama,
				]);
		}
				array_push($arrdip2,[
					'arrdip'=>$arrdip,
					'semua'=>$semua,
					'lama'=>$lama,
					'baru'=>$baru,
				]);
		return $arrdip2;
		
	}
	public function actionPenyakitGilut($start='',$end=''){
		$poli = KategoriPenyakitMulut::find()->all();
		$arrdip=array();
		foreach ($poli as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			$jumlah = 0;
			foreach($katpasien as $kp){
				$ranap = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['<>','status',5])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['hide'=>0])->andwhere(['kat_penyakit_gilut'=>$q->id])->count();	
				$jumlah += $ranap ;
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,					
					'jumlah' => $this->stripp($ranap),					
				]);
			}
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->penyakit,
				'katPasien'=>$arrdip2,
				'total' => $this->stripp($jumlah),	
			]);
		}
		return $arrdip;
	}
	public function actionKegiatanUgd($start='',$end=''){
		$poli = KegiatanUgd::find()->all();
		$arrdip=array();
		foreach ($poli as $q){
			$arrdip2=array();
			$katpasien = RawatStatus::find()->where(['ket'=>1])->all();
			$jumlah = 0;
			foreach($katpasien as $kp){
				$ranap = Rawat::find()->where(['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end])->andwhere(['hide'=>0])->andwhere(['<>','status',5])->andwhere(['status'=>$kp->id])->andwhere(['kegiatan_ugd'=>$q->id])->count();	
				$jumlah += $ranap ;
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->status,					
					'jumlah' => $this->stripp($ranap),					
				]);
			}
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->kegiatan,
				'katPasien'=>$arrdip2,
				'total' => $this->stripp($jumlah),	
			]);
		}
		return $arrdip;
	}
	public function actionKegiatanLab($start='',$end=''){
		$layanan = LaboratoriumLayanan::find()->orderBy(['urutan'=>SORT_ASC])->all();
		$arrdip=array();
		
		foreach ($layanan as $q){
			$arrdip2=array();
			$pemeriksaan = LaboratoriumPemeriksaan::find()->where(['idlab'=>$q->id])->all();
			foreach($pemeriksaan as $p):
			$total=0;
				$arrdip3=array();
				$katpasien = PasienKategori::find()->all();
				foreach($katpasien as $kp){
					$lab = LaboratoriumHasildetail::find()->joinwith(['hasil as hasil'])->where(['idpemeriksaan'=>$p->id])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['between','DATE_FORMAT(hasil.tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
					$total += $lab;
					array_push($arrdip3,[
						'id' => $kp->id,
						'nama' => $kp->nama,
						'jumlah' => $lab,
						
					]);
					
				}
				array_push($arrdip2,[
					'id' => $p->id,
					'nama' => $p->nama_pemeriksaan,
					'katPasien' => $arrdip3,
					'total' => $total,
					
					
				]);
			endforeach;
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->nama_layanan,
				'pemeriksaan' => $arrdip2,
				
			]);
		}
		return $arrdip;
	}
	public function actionPemeriksaanRadiologi($start='',$end=''){
		$kat_radiologi = Radiologi::find()->all();
		$arrkat = array();
		foreach($kat_radiologi as $kr){
			$pemeriksaan_umum = RadiologiHasildetail::find()->where(['idradiologi'=>$kr->id])->andwhere(['idbayar'=>1])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			$pemeriksaan_bpjs = RadiologiHasildetail::find()->where(['idradiologi'=>$kr->id])->andwhere(['idbayar'=>2])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			array_push($arrkat,[
				'id' => $kr->id,
				'nama' => $kr->radiologi,
				'jumlahUmum' => $pemeriksaan_umum,
				'jumlahBpjs' => $pemeriksaan_bpjs,
				
			]);
		}
		$pelayanan_radiologi = RadiologiPelayanan::find()->all();
		$arrpel = array();
		foreach($pelayanan_radiologi as $pr){
			$pemeriksaan_umum = RadiologiHasildetail::find()->where(['idpelayanan'=>$pr->id])->andwhere(['idbayar'=>1])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			$pemeriksaan_bpjs = RadiologiHasildetail::find()->where(['idpelayanan'=>$pr->id])->andwhere(['idbayar'=>2])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			array_push($arrpel,[
				'id' => $pr->id,
				'nama' => $pr->nama_pelayanan,
				'jumlahUmum' => $pemeriksaan_umum,
				'jumlahBpjs' => $pemeriksaan_bpjs,
				
			]);
		}
		$tindakan_radiologi = RadiologiTindakan::find()->all();
		$arrtin = array();
		foreach($tindakan_radiologi as $td){
			$pemeriksaan_umum = RadiologiHasildetail::find()->where(['idtindakan'=>$td->id])->andwhere(['idbayar'=>1])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			$pemeriksaan_bpjs = RadiologiHasildetail::find()->where(['idtindakan'=>$td->id])->andwhere(['idbayar'=>2])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			array_push($arrtin,[
				'id' => $td->id,
				'nama' => $td->nama_tindakan,
				'jumlahUmum' => $pemeriksaan_umum,
				'jumlahBpjs' => $pemeriksaan_bpjs,
				
			]);
		}
		return array(
				'Radiologi'=>$arrkat,
				'Pelayanan'=>$arrpel,
				'Tindakan'=>$arrtin,
		);
		
	}
	public function actionKegiatanRadiologi($start='',$end=''){
		$layanan = Radiologi::find()->where(['<>','id',6])->all();
		$arrdip=array();
		
		foreach ($layanan as $q){
			$arrdip2=array();
			$katpasien = PasienKategori::find()->all();
			foreach($katpasien as $kp):
				$pemeriksaan = RadiologiHasildetail::find()->where(['idradiologi'=>$q->id])->andwhere(['kat_pasien'=>$kp->id])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
				array_push($arrdip2,[
					'id' => $kp->id,
					'nama' => $kp->nama,
					'jumlah' => $pemeriksaan,
					
				]);
			endforeach;
			array_push($arrdip,[
				'id' => $q->id,
				'nama' => $q->radiologi,
				'jumlah' => $arrdip2,
				
			]);
		}
		return $arrdip;
	}
	
	public function actionPemeriksaanLab($start='',$end=''){
		$lab = LaboratoriumLayanan::find()->all();
		$arrlab = array();
		foreach($lab as $lab){
			$pemeriksaan_umum = LaboratoriumHasildetail::find()->joinwith(['pemeriksaan as pemrik'])->where(['pemrik.idlab'=>$lab->id])->andwhere(['idbayar'=>1])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			$pemeriksaan_bpjs = LaboratoriumHasildetail::find()->joinwith(['pemeriksaan as pemrik'])->where(['pemrik.idlab'=>$lab->id])->andwhere(['idbayar'=>2])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			array_push($arrlab,[
				'id' => $lab->id,
				'nama' => $lab->nama_layanan,
				'jumlahUmum' => $pemeriksaan_umum,
				'jumlahBpjs' => $pemeriksaan_bpjs,
				
			]);
		}
		$lab_pemeriksaan = LaboratoriumPemeriksaan::find()->all();
		$arrpel = array();
		foreach($lab_pemeriksaan as $pr){
			$pemeriksaan_umum = LaboratoriumHasildetail::find()->where(['idpemeriksaan'=>$pr->id])->andwhere(['idbayar'=>1])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			$pemeriksaan_bpjs = LaboratoriumHasildetail::find()->where(['idpemeriksaan'=>$pr->id])->andwhere(['idbayar'=>2])->andwhere(['between','DATE_FORMAT(tgl_hasil,"%Y-%m-%d")',$start,$end])->count();
			array_push($arrpel,[
				'id' => $pr->id,
				'nama' => $pr->nama_pemeriksaan,
				'jumlahUmum' => $pemeriksaan_umum,
				'jumlahBpjs' => $pemeriksaan_bpjs,
				
			]);
		}
		
		return array(
				'Layanan'=>$arrlab,
				'Pemeriksaan'=>$arrpel,
		);
		
	}
}