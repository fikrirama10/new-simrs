<?php

namespace backend\controllers;

use Yii;
use common\models\RawatBayar;
use common\models\RawatBayarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class DiagnosaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RawatBayar models.
     * @return mixed
     */
    public function actionPrintDiagnosa($awal,$akhir,$jenis){
		$url = Yii::$app->params['baseUrl'].'dashboard/pasien-rest/icd10jenis?awal='.$awal.'&akhir='.$akhir.'&jenis='.$jenis;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('print-diagnosa',[
			'model'=>$json,
			'awal'=>$awal,
			'akhir'=>$akhir,
			'jenis'=>$jenis,
		]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_PORTRAIT, 
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
    public function actionShowDiagnosa($awal,$akhir){
		$url = Yii::$app->params['baseUrl'].'dashboard/pasien-rest/icd10?awal='.$awal.'&akhir='.$akhir.'&limit=10';
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show-diagnosa',[
			'model'=>$json,
			'awal'=>$awal,
			'akhir'=>$akhir,
		]);
	}
    public function actionIndex()
    {
		$url = Yii::$app->params['baseUrl'].'dashboard/pasien-rest/icd10?awal='.date('Y-m-01').'&akhir='.date('Y-m-d').'&limit=10';
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$awal = date('Y-m-01');
		$akhir = date('Y-m-d');
		// return print_r($json);
        return $this->render('index',[
			'model'=>$json,
			'awal'=>$awal,
			'akhir'=>$akhir,
		]
		);
    }

    /**
     * Deletes an existing RawatBayar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RawatBayar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RawatBayar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RawatBayar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
