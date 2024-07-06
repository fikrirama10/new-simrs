<?php

namespace backend\controllers;

use Yii;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\TindakanSeach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * TindakanController implements the CRUD actions for Tindakan model.
 */
class LaporanStokController extends Controller
{
	public function actionMutasi(){
		
		return $this->render('mutasi');
	}
	public function actionShow($start,$end){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-inventori/obat?start='.$start.'&end='.$end.'&idgudang='.Yii::$app->user->identity->userdetail->idgudang;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show',[
			'model'=>$json,
			'start'=>$start,
			'end'=>$end,
			'user'=>Yii::$app->user->identity->userdetail->idgudang,
		]);
	}
	public function actionPrint($start,$end){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-inventori/obat?start='.$start.'&end='.$end.'&idgudang='.Yii::$app->user->identity->userdetail->idgudang;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('print-mutasi',[
			'model'=>$json,
			'start'=>$start,
			'end'=>$end,
			'user'=>Yii::$app->user->identity->userdetail->idgudang,
		]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_LANDSCAPE, 
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
}