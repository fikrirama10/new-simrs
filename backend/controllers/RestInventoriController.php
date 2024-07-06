<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\ObatKartustok;
use common\models\Obat;
use common\models\ObatBacth;
use common\models\ObatMutasi;
use common\models\ObatDroping;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;
use common\models\PenerimaanBarangDetail;
use common\models\AmprahGudangobatDetail;

class RestInventoriController extends \yii\rest\Controller
{
	public function actionObats($start='',$end='',$idgudang=''){
		$obat = Obat::find()->orderBy(['nama_obat'=>SORT_ASC])->all();
		$arrobat = array();
		foreach($obat as $o){
			$mutasi_pemerimaan = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->andwhere(['jenis.jenis'=>2])->sum('jumlah');
			$mutasi_penggunaan = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->andwhere(['jenis.jenis'=>1])->sum('jumlah');
			array_push($arrobat,[
				'id'=>$o->id,
				'namaObat'=>$o->nama_obat,
				'mutasi_pemerimaan'=>$mutasi_pemerimaan,
				'mutasi_penggunaan'=>$mutasi_penggunaan,
			]);
		}
		return $arrobat;
	}
	public function actionObatMutasiGudang($start='',$end=''){
		$obat = Obat::find()->orderBy(['nama_obat'=>SORT_ASC])->all();
		$arrobat = array();
		foreach($obat as $o){
		$penerimaan = PenerimaanBarangDetail::find()->joinWith(['penerimaan as penerimaan'])->where(['idbarang'=>$o->id])->andwhere(['between','penerimaan.tgl_faktur',$start,$end])->andwhere(['diterima'=>1])->sum('jumlah');
		
		$penyerahan = AmprahGudangobatDetail::find()->joinWith(['amprah as amp'])->where(['idobat'=>$o->id])->andwhere(['between','amp.tgl_penyerahan',$start,$end])->andwhere(['amp.status'=>2])->sum('jumlah_diserahkan');
		
		$mutasi = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>1])->orderBy(['id'=>SORT_ASC])->all();
		$arrmut=array();
		$mutasi_awal_b = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>1])->orderBy(['id'=>SORT_ASC])->limit(1)->all();
		$mutasi_akhir_b = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>1])->orderBy(['id'=>SORT_DESC])->limit(1)->all();
		foreach($mutasi as $m){
			array_push($arrmut,[
				'jumlah'=>$m->jumlah,
				'jenismutasi'=>$m->idjenismutasi,
			]);
		}
		array_push($arrobat,[
				'id'=>$o->id,
				'namaObat'=>$o->nama_obat,
				'satuan'=>$o->satuan->satuan,
				'penerimaan'=>$penerimaan,
				'penyerahan'=>$penyerahan,
				'stokAwal'=>$mutasi_awal_b,
				'stokAkhir'=>$mutasi_akhir_b,
				'mutasi'=>$arrmut
				
			]);
		}
		return $arrobat;
	}
	public function actionObat($start='',$end='',$idgudang=1){
		$obat = Obat::find()->orderBy(['nama_obat'=>SORT_ASC])->all();
		$arrobat = array();
		foreach($obat as $o){
			$bacth = ObatBacth::find()->where(['idobat'=>$o->id])->all();
			$arrb = array();
			$stokawal = 0;
			$stokakhir = 0;
			$harga = 0;
			foreach($bacth as $b){
				$bacth = ObatBacth::find()->where(['id'=>$b->id])->one();
				$mutasi_awal_b = ObatMutasi::find()->where(['idobat'=>$b->idobat])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->orderBy(['id'=>SORT_ASC])->limit(1)->all();
				$mutasi_akhir_b = ObatMutasi::find()->where(['idobat'=>$b->idobat])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->orderBy(['id'=>SORT_DESC])->limit(1)->all();
				$arrmb = array();
				$arrmbb = array();
				$jumlaha=0;
				$jumlahb=0;
				$jumlahbharga=0;
				foreach($mutasi_awal_b as $mab){
					$jumlaha += $mab->stokawal;
					
				}
				foreach($mutasi_akhir_b as $mabb){
					$jumlahb += $mabb->stokakhir;
					$jumlahbharga += $mabb->stokakhir * $b->harga_beli;
					
				}
				
				$stokawal += $jumlaha;
				$stokakhir += $jumlahb;
				$harga += $jumlahbharga;

			}
			$mutasi_jumlah = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->orderBy(['tgl'=>SORT_ASC])->count();
			$mutasi_awal = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->orderBy(['tgl'=>SORT_ASC])->one();
			$mutasi_akhir = ObatMutasi::find()->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->orderBy(['tgl'=>SORT_DESC])->one();
			$mutasi_pemerimaan = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->andwhere(['jenis.jenis'=>2])->sum('jumlah');
			$mutasi_penggunaan = ObatMutasi::find()->joinWith(['jenis as jenis'])->where(['idobat'=>$o->id])->andwhere(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->andwhere(['idgudang'=>$idgudang])->andwhere(['jenis.jenis'=>1])->sum('jumlah');
			$kartustok_masuk = ObatKartustok::find()->where(['between','tgl',$start,$end])->andwhere(['idobat'=>$o->id])->andwhere(['idasal'=>$idgudang])->andwhere(['jenis'=>2])->sum('jumlah');
			$kartustok_keluar = ObatKartustok::find()->where(['between','tgl',$start,$end])->andwhere(['idobat'=>$o->id])->andwhere(['idasal'=>$idgudang])->andwhere(['jenis'=>1])->sum('jumlah');
			array_push($arrobat,[
				'id'=>$o->id,
				'namaObat'=>$o->nama_obat,
				'satuan'=>$o->satuan->satuan,
				'harga'=>$harga,
				'satuab'=>$harga,
				'stokAwal'=>$stokawal,
				'stokAkhir'=>$stokakhir,
				'mutasi_pemerimaan'=>$mutasi_pemerimaan,
				'mutasi_penggunaan'=>$mutasi_penggunaan,
				'mutasi_jumlah'=>$mutasi_jumlah,
			]);
		}
		return $arrobat;
	}
}