<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\PenerimaanBarang;
use common\models\PenerimaanBarangDetail;
use common\models\BarangPenerimaanDetail;
use common\models\AmprahGudangobatDetail;
use common\models\AmprahGudangatkDetail;
class RestPjkController extends \yii\rest\Controller
{
	public function actionAmprahAtkRuangan($awal,$akhir){
		$model = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['amprah.idpeminta'])->all();
		$arrdip = array();
		foreach($model as $m){
			$barang = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah','barang as barang'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.idpeminta'=>$m->amprah->idpeminta])->orderBy(['barang.nama_barang'=>SORT_ASC])->groupBy(['idbarang'])->all();
			$arrobat = array();
			foreach($barang as $o){
				$obatsum = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.idpeminta'=>$m->amprah->idpeminta])->andwhere(['idbarang'=>$o->idbarang])->sum('jumlah');
				array_push($arrobat,[
					'idbarang'=>$o->idbarang,
					'namaBarang'=>$o->barang->nama_barang,
					'satuan'=>$o->barang->satuan->satuan,
					'jumlah'=>$obatsum,
				]);
			}
			array_push($arrdip,[
				'idpeminta'=>$m->amprah->idpeminta,
				'ruangan'=>$m->amprah->ruangan->ruangan,
				'obat'=>$arrobat,
			]);
		}
		return $arrdip;
	}
	public function actionAmprahAtk($awal,$akhir){
		$obat = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah','barang as barang'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['idbarang'])->orderBy(['barang.nama_barang'=>SORT_ASC])->all();
		$arrobat = array();
			foreach($obat as $o){
				$obatsum = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['idbarang'=>$o->idbarang])->sum('jumlah');
				array_push($arrobat,[
					'idbarang'=>$o->idbarang,
					'namaBarang'=>$o->barang->nama_barang,
					'satuan'=>$o->barang->satuan->satuan,
					'jumlah'=>$obatsum,
				]);
			}
		return $arrobat;
	}
	public function actionAmprahAtkTanggal($awal,$akhir){
		$model = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah','barang as barang'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['amprah.tgl_penyerahan'])->orderBy(['amprah.tgl_penyerahan'=>SORT_ASC])->all();
		$arrdip = array();
		foreach($model as $m){
			$barang = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah','barang as barang'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.tgl_penyerahan'=>$m->amprah->tgl_penyerahan])->orderBy(['barang.nama_barang'=>SORT_ASC])->groupBy(['idbarang'])->all();
			$arrobat = array();
			foreach($barang as $o){
				$barang_sum = AmprahGudangatkDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.tgl_penyerahan'=>$m->amprah->tgl_penyerahan])->andwhere(['idbarang'=>$o->idbarang])->sum('jumlah');
				array_push($arrobat,[
					'idbarang'=>$o->idbarang,
					'namaBarang'=>$o->barang->nama_barang,
					'satuan'=>$o->barang->satuan->satuan,
					'jumlah'=>$barang_sum,
				]);
			}
			array_push($arrdip,[
				'tgl_penyerahan'=>$m->amprah->tgl_penyerahan,
				'obat'=>$arrobat,
			]);
		}
		return $arrdip;
		
	}
	
	
	public function actionAmprahObatTanggal($awal,$akhir){
		$model = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah','obat as obat'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['amprah.tgl_penyerahan'])->orderBy(['amprah.tgl_penyerahan'=>SORT_ASC])->all();
		$arrdip = array();
		foreach($model as $m){
			$obat = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah','obat as obat'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.tgl_penyerahan'=>$m->amprah->tgl_penyerahan])->groupBy(['idobat'])->orderBy(['obat.nama_obat'=>SORT_ASC])->all();
			$arrobat = array();
			foreach($obat as $o){
				$obat_sum = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.tgl_penyerahan'=>$m->amprah->tgl_penyerahan])->andwhere(['idobat'=>$o->idobat])->sum('jumlah_diserahkan');
				array_push($arrobat,[
					'idobat'=>$o->idobat,
					'namaObat'=>$o->obat->nama_obat,
					'satuan'=>$o->obat->satuan->satuan,
					'jumlah'=>$obat_sum,
				]);
			}
			array_push($arrdip,[
				'tgl_penyerahan'=>$m->amprah->tgl_penyerahan,
				'obat'=>$arrobat,
			]);
		}
		return $arrdip;
	}
	public function actionAmprahObat($awal,$akhir){
		$obat = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah','obat as obat'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['idobat'])->orderBy(['obat.nama_obat'=>SORT_ASC])->all();
		$arrobat = array();
			foreach($obat as $o){
				$sumobat = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['idobat'=>$o->idobat])->sum('jumlah_diserahkan');
				array_push($arrobat,[
					'idobat'=>$o->idobat,
					'namaObat'=>$o->obat->nama_obat,
					'satuan'=>$o->obat->satuan->satuan,
					'jumlah'=>$sumobat,
				]);
			}
		return $arrobat;
	}
	public function actionAmprahObatRuangan($awal,$akhir){
		$model = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->groupBy(['amprah.idpeminta'])->all();
		$arrdip = array();
		foreach($model as $m){
			$obat = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.idpeminta'=>$m->amprah->idpeminta])->groupBy(['idobat'])->all();
			$arrobat = array();
			foreach($obat as $o){
				$obat = AmprahGudangobatDetail::find()->joinWith(['amprah as amprah'])->where(['between','DATE_FORMAT(amprah.tgl_penyerahan,"%Y-%m-%d")',$awal,$akhir])->andwhere(['amprah.status'=>2])->andwhere(['amprah.idpeminta'=>$m->amprah->idpeminta])->andwhere(['idobat'=>$o->idobat])->all();
				array_push($arrobat,[
					'idobat'=>$o->idobat,
					'namaObat'=>$o->obat->nama_obat,
					'satuan'=>$o->obat->satuan->satuan,
					'jumlah'=>$o->jumlah_diserahkan,
				]);
			}
			array_push($arrdip,[
				'idpeminta'=>$m->amprah->idpeminta,
				'ruangan'=>$m->amprah->ruangan->ruangan,
				'obat'=>$arrobat,
			]);
		}
		return $arrdip;
	}
	public function actionPjkBarang($awal,$akhir){
		$model = BarangPenerimaanDetail::find()->joinWith(['penerimaan as penerimaan','barang as barang'])->where(['between','DATE_FORMAT(penerimaan.tgl,"%Y-%m-%d")',$awal,$akhir])->groupBy(['idbarang'])->orderBy(['barang.nama_barang'=>SORT_ASC])->all();
		$arrdip = array();
		foreach($model as $m){
			$barang = BarangPenerimaanDetail::find()->joinWith(['penerimaan as penerimaan'])->where(['between','DATE_FORMAT(penerimaan.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbarang'=>$m->idbarang])->sum('qty');
			array_push($arrdip,[
				'idbarang'=>$m->idbarang,
				'barang'=>$m->barang->nama_barang,
				'satuan'=>$m->barang->satuan->satuan,
				'jumlah'=>$barang,
				'harga'=>$m->harga,
				'total'=>$barang * $m->harga,
			]);
		}
		return $arrdip;
	}
	public function actionPjkObat($awal,$akhir){
		$model = PenerimaanBarangDetail::find()->joinWith(['penerimaan as penerimaan','obat as obat'])->where(['between','DATE_FORMAT(penerimaan.tgl_faktur,"%Y-%m-%d")',$awal,$akhir])->groupBy(['idbarang'])->orderBy(['obat.nama_obat'=>SORT_ASC])->all();
		$arrdip = array();
		foreach($model as $m){
			$barang = PenerimaanBarangDetail::find()->joinWith(['penerimaan as penerimaan'])->where(['between','DATE_FORMAT(penerimaan.tgl_faktur,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbarang'=>$m->idbarang])->sum('jumlah');
			array_push($arrdip,[
				'idbarang'=>$m->idbarang,
				'barang'=>$m->obat->nama_obat,
				'satuan'=>$m->obat->satuan->satuan,
				'jumlah'=>$barang,
				'harga'=>$m->harga,
				'total'=>$barang * $m->harga,
			]);
		}
		return $arrdip;
	}
}