<?php

namespace backend\controllers;

use Yii;
use common\models\RawatBayar;
use common\models\RawatSearch;
use common\models\RawatBayarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class FinanceController extends Controller
{
	public function actionIndex(){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-laporan-keuangan/transaksi-bulanan?tahun='.date('Y');
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('index',[
			'model'=>$json
		]);
	}
	
}