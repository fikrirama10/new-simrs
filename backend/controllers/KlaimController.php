<?php

namespace backend\controllers;

use Yii;
use common\models\PasienSearch;
use common\models\Pasien;
use common\models\Rawat;
use common\models\RawatSearch;
use common\models\RawatKunjungan;
use kartik\mpdf\Pdf;	
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenerimaanBarangController implements the CRUD actions for PenerimaanBarang model.
 */
class KlaimController extends Controller
{
	public function actionIndex()
    {
        $searchModel = new RawatSearch();
		$where = ['idbayar'=>2];
        $dataProvider = $searchModel->search($this->request->queryParams,$where);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionKunjungan($id){
		$model = RawatKunjungan::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$rawat = Rawat::find()->where(['idkunjungan'=>$model->idkunjungan])->andwhere(['<>','status',5])->all();
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-tindakan-trx?id='.$id;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('kunjungan',[
			'model'=>$model,
			'rawat'=>$rawat,
			'pasien'=>$pasien,
			'rincian'=>$json,
		]);
	}
	public function actionShowRincian($id){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-tindakan?id='.$id;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show-rincian',[
			'model'=>$json,
		]);
	}
	public function actionView($id){
		$model = Pasien::findOne($id);
		$list_kunjungan = RawatKunjungan::find()->where(['no_rm'=>$model->no_rm])->andwhere(['<>','status',5])->orderBy(['tgl_kunjungan'=>SORT_DESC])->limit(5)->all();
		return $this->render('view',[
			'model'=>$model,
			'list_kunjungan'=>$list_kunjungan
		]);
	}
}