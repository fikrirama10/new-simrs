<?php

namespace backend\controllers;

use Yii;
use common\models\Obat;
use common\models\ObatKartustok;
use common\models\ObatBacth;
use common\models\DataBarang;
use common\models\ObatMutasi;
use common\models\GudangInventori;
use common\models\ObatSeacrh;
use common\models\PermintaanObat;
use common\models\BarangAmprahSearch;
use common\models\PermintaanObatdetail;
use common\models\PermintaanObatRequest;
use common\models\PermintaanObatRequestSearch;
use common\models\PermintaanObatdetailSearch;
use common\models\PermintaanObatSearch;
use common\models\BarangAmprah;
use common\models\BarangAmprahDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * PermintaanObatController implements the CRUD actions for PermintaanObat model.
 */
class PengadaanController extends Controller
{
    public function actionRekap(){
		return $this->render('index');
	}
	public function actionShowRekap($start,$end){
		return $this->renderAjax('show',[
			'start'=>$start,
			'end'=>$end,
		]);
	}
	public function actionRekapPembelian(){
		return $this->render('index-pembelian');
	}
	public function actionShowRekapPembelian($start,$end){
		return $this->renderAjax('showpembelian',[
			'start'=>$start,
			'end'=>$end,
		]);
	}
	public function actionListAmprah(){
		 $searchModel = new BarangAmprahSearch();
		 $where = ['status'=>3];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('list-amprah',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
	}
	public function actionListBarang(){
		 $searchModel = new PermintaanObatSearch();
		 $where = ['status'=>2];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('list-permintaan',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
	}
	public function actionListProses(){
		 $searchModel = new PermintaanObatSearch();
		 $where = ['status'=>13];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('list-proses',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
		 
		
	}
	public function actionTambahBarang($id){
		$model = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$barang = new DataBarang();
		if ($barang->load(Yii::$app->request->post())) {
			$model->baru = 0;
			$barang->genKode();
			if($barang->save()){
				$model->idbarang = $barang->id;
				$model->save();
			}
			return $this->redirect(['pengadaan/view-amprah?id='.$model->idamprah]);
		}
		return $this->render('tambah-barang',[
			'model'=>$model,
			'barang'=>$barang,
		]);
	}
	public function actionSelesai($id){
		$model = PermintaanObat::findOne($id);
		$detail = PermintaanObatdetail::find()->where(['idpermintaan'=>$id])->all();
		$total = 0;
		foreach($detail as $d){
			$total += $d->total;
		}
		$model->total_biaya = $total;
		$model->status = 13;
		if($model->save(false)){
			Yii::$app->session->setFlash('success', 'Pengajuan Di proses');
			return $this->redirect(['list-barang']);
		}
		
	}
	public function actionAmprahBerikan($id){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$barang = DataBarang::find()->where(['id'=>$detail->idbarang])->one();
		if($detail->baru == 1){
			Yii::$app->session->setFlash('warning', 'Data barang bekum ada di gudang silahkan lengkapi isian berikut');
			return $this->redirect(['pengadaan/tambah-barang?id='.$detail->id]);
		}
		$detail->status = 3;
		if($detail->save()){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionAmprahKoreksi($id){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->status = 2;
		if($detail->save()){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionAmprahSelesai($id){
		$detail = BarangAmprah::find()->where(['id'=>$id])->one();
		$detail->status = 4;
		if($detail->save(false)){
			Yii::$app->session->setFlash('success', 'Pengajuan Di proses');
			return $this->redirect(['pengadaan/list-amprah']);
		}
	}
	public function actionEditItemAmprah($id,$jumlah){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->qty_setuju = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				if($detail->save(false)){	
					if($detail->qty_setuju == 0){
						$detail->save();
					}
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionHapusItem($id){
		$model = PermintaanObatdetail::findOne($id);
		if($model->delete()){
			Yii::$app->session->setFlash('success', 'Data Berhasil Dihapus');
			return $this->redirect(Yii::$app->request->referrer);
		}
	}

	public function actionViewAmprah($id){
		$model = BarangAmprah::findOne($id);
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		return $this->render('view-amprah',[
			'model'=>$model,
			'detail'=>$detail,
		]);
	}
	public function actionView($id){
		$model = PermintaanObat::findOne($id);
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$detail_permintaan = new PermintaanObatdetail();
		$detail_permintaan_list = PermintaanObatdetail::find()->where(['idpermintaan'=>$model->id])->all();
		$permintaan = new PermintaanObatRequest();
		$permintaan_list = PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->all();
		$searchModel = new PermintaanObatRequestSearch();
		$where= ['idpermintaan'=>$model->id];
		$andwhere = ['<>','status',4];
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andwhere);
			if($detail_permintaan->load(Yii::$app->request->post())){
				$obat_bacth = ObatBacth::find()->where(['id'=>$detail_permintaan->idbacth])->one();
				$obat_detail = PermintaanObatdetail::find()->where(['idbacth'=>$detail_permintaan->idbacth])->andwhere(['idpermintaan'=>$model->id])->one();
				$detail_permintaan->total = $detail_permintaan->harga * $detail_permintaan->jumlah_setuju;
				 if($obat_detail){
					 $totalan = $obat_bacth->stok_gudang + $obat_detail->jumlah_setuju;
					if($totalan < $detail_permintaan->jumlah_setuju){
					 Yii::$app->session->setFlash('warning', 'Jumlah stok tidak cukup');
					 return $this->refresh();
					}
					 $obat_detail->jumlah_setuju = $obat_detail->jumlah_setuju +$detail_permintaan->jumlah_setuju;
					 if($obat_detail->save(false)){
						 return $this->refresh();
					}
				 }else{
					 if($obat_bacth->stok_gudang < $detail_permintaan->jumlah_setuju){
					 Yii::$app->session->setFlash('warning', 'Jumlah stok tidak cukup');
					 return $this->refresh();
					}
					 $detail_permintaan->status = 1;
					 if($detail_permintaan->save(false)){
						 return $this->refresh();
					 }
				 }
				
			}
			
		return $this->render('view',[
			'model'=>$model,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
			'detail_permintaan'=>$detail_permintaan,
			'permintaan_list'=>$permintaan_list,
			'detail_permintaan_list'=>$detail_permintaan_list,
		]);
	}
}
	