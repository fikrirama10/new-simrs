<?php
namespace backend\controllers;
use Yii;
use yii\base\Model;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class MonitoringAntreanController extends Controller
{
	public function actionPertanggal(){
		return $this->render('dashboard-tanggal');
	}
	public function actionPerBulan(){
		return $this->render('dashboard-bulan');
	}
	
	public function actionShowDashboard($tgl,$waktu){
		$response= Yii::$app->hfis->get_dashboardtgl($tgl,$waktu);
		return $this->renderAjax('show-dashboard',[
			'response'=>$response,
		]);
	}
	
	public function actionShowDashboard2($bulan,$tahun,$waktu){
		$response= Yii::$app->hfis->get_dashboardbln($bulan,$tahun,$waktu);
		return $this->renderAjax('show-dashboardbln',[
			'response'=>$response,
		]);
	}
}