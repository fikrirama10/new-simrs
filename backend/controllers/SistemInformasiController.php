<?php

namespace backend\controllers;

use Yii;
use common\models\RawatBayar;
use common\models\Obat;
use common\models\ObatBacth;
use common\models\ObatDropingBatch;
use common\models\ObatDroping;
use common\models\RawatBayarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class SistemInformasiController extends Controller
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
    public function actionIndex()
    {
        return $this->render('index');
    }
	public function actionShow($start,$end){
		
		return $this->renderAjax('show',[
			'start'=>$start,
			'end'=>$end,
		]);
	}
	public function actionObat(){
	    
		$obat = Obat::find()->all();
		$droping = ObatDroping::find()->all();
		$content = $this->renderPartial('obat',['obat'=>$obat,'droping'=>$droping]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paperbarang.css',
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
	public function actionMacamPenyakit($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/macam-penyakit?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('macam-penyakit',['json'=>$json,'end'=>$end]);
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
	public function actionPelayananRajal($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pelayanan-rawat-jalan?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('pelayanan-rajal',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionPelayananRanap($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pelayanan-rawat-inap?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('pelayanan-ranap',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	
	public function actionKelahiran($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kelahiran?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kelahiran',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionKunjunganGigi($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kunjungan-gigi?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kunjungan-gigi',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionKunjunganPoli($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kunjungan-poli?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kunjungan-poli',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionPenyakitGilut($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/penyakit-gilut?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('penyakit-gilut',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionKunjunganUgd($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kunjungan-ugd?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kunjungan-ugd',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionKegiatanUgd($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kegiatan-ugd?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kegiatan-ugd',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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
	public function actionKegiatanLab($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/kegiatan-lab?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('kegiatan-lab',['json'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'orientation' => Pdf::ORIENT_LANDSCAPE,
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

    /**
     * Displays a single RawatBayar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RawatBayar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RawatBayar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RawatBayar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
