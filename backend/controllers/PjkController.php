<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class  PjkController extends Controller
{
	public function actionPjkObat(){
		return $this->render('index-obat');
	}
	public function actionPjkBarang(){
		return $this->render('index-barang');
	}
	public function actionShowPjkObat($start,$end){
		$url_rincian = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/pjk-obat?awal='.$start.'&akhir='.$end;
		$content_rincian = file_get_contents($url_rincian);
		$json_rincian = json_decode($content_rincian, true);
		
		return $this->renderAjax('show-obat',[
			'model'=>$json_rincian,
		]);
	}
	
	public function actionShowPjkBarang($start,$end){
		$url_rincian = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/pjk-barang?awal='.$start.'&akhir='.$end;
		$content_rincian = file_get_contents($url_rincian);
		$json_rincian = json_decode($content_rincian, true);
		
		return $this->renderAjax('show-barang',[
			'model'=>$json_rincian,
		]);
	}
}