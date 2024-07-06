<?php

namespace backend\controllers;

use Yii;
use common\models\Obat;
use common\models\ObatKartustok;
use common\models\ObatBacth;
use common\models\ObatMutasi;
use common\models\GudangInventori;
use common\models\ObatSeacrh;
use common\models\PermintaanObat;
use common\models\PermintaanObatdetail;
use common\models\PermintaanObatRequest;
use common\models\PermintaanObatRequestSearch;
use common\models\PermintaanObatdetailSearch;
use common\models\PermintaanObatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * PermintaanObatController implements the CRUD actions for PermintaanObat model.
 */
class JangkesController extends Controller
{
	public function actionListPermintaanObat(){
		 $searchModel = new PermintaanObatSearch();
		 $where = ['status'=>1];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
		 
		
	}
	public function actionListPermintaanSetuju(){
		 $searchModel = new PermintaanObatSearch();
		 $where = ['>','status',1];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
		 
		
	}
	public function actionSelesai($id){
		$model = PermintaanObat::findOne($id);
		$permintaan = PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->all();
		$model->status = 2;
		foreach($permintaan as $p):
			if($p->status == 1){
				Yii::$app->session->setFlash('warning', 'ada barang yang belum mendapatkan keputusan');
				return $this->redirect(['/jangkes/'.$model->id]);
			}
		endforeach;
		if($model->save(false)){
			Yii::$app->session->setFlash('success', 'Pengajuan permintaan obat telah di ajukan ke bagian pengadaan');
			return $this->redirect(['/jangkes/list-permintaan-obat']);
		}
	}
public function actionEditHarga($id,$jumlah){
		$detail = PermintaanObatRequest::find()->where(['id'=>$id])->one();
		$detail->harga = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				$detail->total_setuju = $detail->harga * $detail->jumlah_setuju;
				$detail->total= $detail->harga * $detail->jumlah;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionEditSetuju($id,$jumlah){
		$detail = PermintaanObatRequest::find()->where(['id'=>$id])->one();
		$detail->jumlah_setuju = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				$detail->total_setuju = $detail->harga * $jumlah;
				$detail->status = 2;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionEditTolak($id){
		$pl = PermintaanObatRequest::find()->where(['id'=>$id])->one();
		if($pl->load($this->request->post())){
			$pl->status = 4;
			$pl->jumlah = 0;
			if($pl->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
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
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->render('view',[
			'model'=>$model,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
			'permintaan_list'=>$permintaan_list,
		]);
	}
}