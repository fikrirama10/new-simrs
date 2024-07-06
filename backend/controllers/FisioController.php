<?php

namespace backend\controllers;

use Yii;
use common\models\RawatSearch;
use common\models\Pasien;
use common\models\Transaksi;
use common\models\TransaksiDetailRinci;
use common\models\Rawat;
use common\models\TindakanFisio;
use kartik\mpdf\Pdf;	
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenerimaanBarangController implements the CRUD actions for PenerimaanBarang model.
 */
class FisioController extends Controller
{
	public function actionIndex(){
		$searchModel = new RawatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}
	public function actionHapus($id){
		$fisio = TindakanFisio::findOne($id);
		$fisio->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionKerjakan($id){
		$fisio = TindakanFisio::findOne($id);
		$model = Rawat::findOne($fisio->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$model->kunjungans->id])->one();
		$transaksi_detail = new TransaksiDetailRinci();
		if ($fisio->load(Yii::$app->request->post())) {
			if($fisio->status == 1){
				$transaksi_detail->idtransaksi = $transaksi->id;
				$transaksi_detail->idtarif = $fisio->idtindakan;
				$transaksi_detail->tarif = $fisio->pemeriksaan->tarif;
				$transaksi_detail->iddokter = $fisio->iddokter;
				$transaksi_detail->tgl = $fisio->tgl;
				$transaksi_detail->idbayar = $fisio->idbayar;
				if($transaksi_detail->save()){
					$fisio->status = 2;
					$fisio->save();
				}
			}else{
				$fisio->save();
			}
			return $this->redirect(['/fisio/'.$model->id]);
		}
		return $this->render('kerjakan',[
			'model'=>$model,
			'pasien'=>$pasien,
			'fisio'=>$fisio,
		]);
	}
	public function actionView($id){
		$model = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$fisio = TindakanFisio::find()->where(['idrawat'=>$id])->all();
		$fisio_tambah = new TindakanFisio();
		if($fisio_tambah->load(Yii::$app->request->post())){
		    if($fisio_tambah->idtindakan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan Lab"); 
				return $this->refresh();
		    }
		    $fisio_tambah->save();
			return $this->refresh();
		}
		return $this->render('view',[
			'model'=>$model,
			'pasien'=>$pasien,
			'fisio'=>$fisio,
			'fisio_tambah'=>$fisio_tambah,
		]);
	}
	public function actionShow($id){
		$model = Pasien::find()->where(['no_rm'=>$id])->one();
		$rawat = Rawat::find()->where(['no_rm'=>$id])->andwhere(['between','status',1,4])->orderBy(['tglmasuk'=>SORT_DESC])->limit(5)->all();
		return $this->renderAjax('show',[
			'model'=>$model,
			'rawat'=>$rawat,
		]);
	}
}