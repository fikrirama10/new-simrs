<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * DataBarangController implements the CRUD actions for DataBarang model.
 */
class JasaController extends Controller
{
	public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionShow(){
		return $this->renderAjax('show');
	}
	public function actionJasaUgd($start='',$end=''){
		// $url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		$content = $this->renderPartial('jasa-ugd');
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
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
	public function actionJasaSpesialis($start='',$end=''){
		// $url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		$content = $this->renderPartial('jasa-spesialis');
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
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
	public function actionPendapatanFarmasi($start='',$end=''){
		// $url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		$content = $this->renderPartial('pendapatan-farmasi');
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
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
	public function actionPendapatanRuangan($start='',$end=''){
		// $url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		$content = $this->renderPartial('pendapatan-ruangan');
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
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
	public function actionPendapatan($start='',$end=''){
		// $url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		
		$content = $this->renderPartial('pendapatan');
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
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
	
?>