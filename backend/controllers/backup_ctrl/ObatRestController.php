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
	
}