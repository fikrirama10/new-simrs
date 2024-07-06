<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\Pasien;
use common\models\Obat;
use common\models\ObatBacth;
use common\models\ObatKartustok;
use common\models\ObatMutasi;

class ObatRestController extends \yii\rest\Controller
{
	public static function allowedDomains()
	{
		return [
		   '*' ,  // star allows all domains
		   'http://localhost:3000',
		];
	}  
	
	public $enableCsrfValidation = false;
	
	public function actionObatEd($jenis){
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$obat = ObatBacth::find()->orderBy(['tgl_kadaluarsa'=>SORT_ASC])->all();
		$arrdip = array();
		foreach($obat as $o){
			$date1=date_create($o->tgl_kadaluarsa);
			$date2=date_create($tgl);
			$diff=date_diff($date1,$date2);
			$hari = $diff->format("%a");
			if($jenis == 1){
				$stok = $o->stok_gudang;
			}else{
				$stok = $o->stok_apotek;
			}
			if($hari < 100){
				array_push($arrdip,[
					'kodeBacth'=>$o->no_bacth,
					'merkObat'=>$o->merk,
					'namaObat'=>$o->obat->nama_obat,						
					'tglED'=>$o->tgl_kadaluarsa,						
					'tglPro'=>$o->tgl_produksi,												
					'ED'=>$hari,												
					'stok'=>$stok,												
				]);
			}
			
			
		}
		return $arrdip;
	}
	public function actionObatBarangHabis($jenis){
		$obat = Obat::find()->all();
		$arrdip=array();
		foreach($obat as $o){
			if($jenis == 1){
				$obat_bacth = ObatBacth::find()->where(['idobat'=>$o->id])->sum('stok_gudang');
				if($o->min_stokgudang > $obat_bacth){
					if($obat_bacth < 1){
						$obat_bacth = 0;
					}
					array_push($arrdip,[
						'namaObat'=>$o->nama_obat,						
						'stok_min'=>$o->min_stokgudang,						
						'stok'=>$obat_bacth,						
					]);
				}
			}else{
				$obat_bacth = ObatBacth::find()->where(['idobat'=>$o->id])->sum('stok_apotek');
				if($o->min_stokapotek > $obat_bacth){
					if($obat_bacth < 1){
						$obat_bacth = 0;
					}
					array_push($arrdip,[
						'namaObat'=>$o->nama_obat,						
						'stok_min'=>$o->min_stokapotek,						
						'stok'=>$obat_bacth,						
					]);
				}
			}
			
		}
		return $arrdip;
	}
	public function actionObatKeluarMasuk($idgudang,$start,$end){
		$obat = ObatKartustok::find()->where(['idasal'=>$idgudang])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->groupBy('idbatch')->all();
		$arrobat = array();
		foreach($obat as $o){
			$keluar = ObatKartustok::find()->where(['idasal'=>$idgudang])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['jenis'=>1])->andwhere(['idbatch'=>$o->idbatch])->sum('jumlah');
			$masuk = ObatKartustok::find()->where(['idasal'=>$idgudang])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['jenis'=>2])->andwhere(['idbatch'=>$o->idbatch])->sum('jumlah');
			if($keluar < 1){
				$keluar = 0;
			}

			array_push($arrobat,[
				'idbatch'=>$o->batch->merk,	
				'idobat'=>$o->obat->nama_obat,	
				'keluar'=>$keluar,	
				'masuk'=>$masuk,	
				
			]);
		}
		return $arrobat;
	}
	public function actionKartuStok($idgudang,$idobat,$start,$end){
		$obat = ObatKartustok::find()->where(['idobat'=>$idobat])->andwhere(['idasal'=>$idgudang])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->orderBy(['id'=>SORT_ASC])->all();
		$arrobat = array();
		foreach($obat as $m ){
			$obat = Obat::findOne($m->idobat);
			array_push($arrobat,[
				'idobat'=>$obat->id,	
				'namaObat'=>$obat->nama_obat,	
				'jumlah'=>$m->jumlah,	
				'tgl'=>$m->tgl,	
				'jenis'=>$m->jenis,	
				
			]);
		}
		return $arrobat;		
	}
	public function actionObatMutasi($idgudang,$idobat,$start,$end){
		$mutasi = ObatMutasi::find()->where(['idobat'=>$idobat])->andwhere(['idgudang'=>$idgudang])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->orderBy(['tgl'=>SORT_ASC])->all();
		$arrobat = array();
		foreach($mutasi as $m){
			$obat = Obat::findOne($m->idobat);
			array_push($arrobat,[
				'idobat'=>$obat->id,	
				'namaObat'=>$obat->nama_obat,	
				'stokawal'=>$m->stokawal,	
				'stokakhir'=>$m->stokakhir,	
				'jumlah'=>$m->jumlah,	
				'tgl'=>$m->tgl,	
				'jenisMutasi'=>$m->jenis->jenis_mutasi,	
				'mutasiDetail'=>$m->subjenis->subjenis,	
				
			]);
		}
		return $arrobat;
	}
	public function actionPenggunaanObat($idgudang,$idobat,$bulan,$tahun){
		$obat = Obat::findOne($idobat);
		// $obat_keluar = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['jenis.jenis'=>1])->andwhere(['idgudang'=>$idgudang])->andwhere(['idobat'=>$obat->id])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->sum('jumlah');
		
		// $obat_masuk = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['jenis.jenis'=>2])->andwhere(['idgudang'=>$idgudang])->andwhere(['idobat'=>$obat->id])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->sum('jumlah');
		// $kalender = CAL_GREGORIAN;
		// $hari = cal_days_in_month($kalender,$bulan,$tahun);
		// $arhar = array();
		// $array_bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun','07'=>'Jul','08'=>'Ags','09'=>'Sep','10'=>'Okt', '11'=>'Nov','12'=>'Des');
		// $bulann = $array_bulan[$bulan];
		// for($a = 1;$a < $hari+1 ;$a++){			
			// $obathari_masuk = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['MONTH(tgl)'=>$bulan])->andwhere(['DAY(tgl)'=>$a])->andwhere(['jenis.jenis'=>1])->andwhere(['idgudang'=>$idgudang])->andwhere(['idobat'=>$obat->id])->sum('jumlah');
			// $obathari_keluar = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['MONTH(tgl)'=>$bulan])->andwhere(['DAY(tgl)'=>$a])->andwhere(['jenis.jenis'=>2])->andwhere(['idgudang'=>$idgudang])->andwhere(['idobat'=>$obat->id])->sum('jumlah');			
			// array_push($arhar,[
				// 'hari'=>$a.''.$bulann,
				// 'masuk'=>$obathari_masuk,
				// 'keluar'=>$obathari_keluar,
				// 'total'=>$obathari_keluar + $obathari_masuk,
			// ]);
		// }
		
		$obat_keluar = ObatKartustok::find()->where(['jenis'=>1])->andwhere(['idasal'=>$idgudang])->andwhere(['idobat'=>$obat->id])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->sum('jumlah');
		$obat_masuk = ObatKartustok::find()->where(['jenis'=>2])->andwhere(['idasal'=>$idgudang])->andwhere(['idobat'=>$obat->id])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->sum('jumlah');
		
		$kalender = CAL_GREGORIAN;
		$hari = cal_days_in_month($kalender,$bulan,$tahun);
		$arhar = array();
		$array_bulan = array('01'=>'Jan','02'=>'Feb','03'=>'Mar', '04'=>'Apr', '05'=>'Mei', '06'=>'Jun','07'=>'Jul','08'=>'Ags','09'=>'Sep','10'=>'Okt', '11'=>'Nov','12'=>'Des');
		$bulann = $array_bulan[$bulan];
		for($a = 1;$a < $hari+1 ;$a++){			
			$obathari_masuk = ObatKartustok::find()->where(['jenis'=>2])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->andwhere(['DAY(tgl)'=>$a])->andwhere(['idasal'=>$idgudang])->andwhere(['idobat'=>$obat->id])->sum('jumlah');	
			$obathari_keluar = ObatKartustok::find()->where(['jenis'=>1])->andwhere(['MONTH(tgl)'=>$bulan])->andwhere(['YEAR(tgl)'=>$tahun])->andwhere(['DAY(tgl)'=>$a])->andwhere(['idasal'=>$idgudang])->andwhere(['idobat'=>$obat->id])->sum('jumlah');	
			array_push($arhar,[
				'hari'=>$a.''.$bulann,
				'masuk'=>$obathari_masuk,
				'keluar'=>$obathari_keluar,
				'total'=>$obathari_keluar + $obathari_masuk,
			]);
		}
		// return $obat_masuk;
		return [
			'obat_masuk'=>$obat_masuk,
			'obat_keluar'=>$obat_keluar,			
			'hari'=>$arhar,	
		];
	}
}