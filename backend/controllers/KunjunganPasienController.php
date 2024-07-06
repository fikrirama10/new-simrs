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
class KunjunganPasienController extends Controller
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
    public function actionKunjungan(){
		$searchRajal = new RawatSearch();
		$where = ['hide'=>0];
		$andWhere = ['<>','status',5];
		$andWhereTgl = ['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',date('Y-m-'.'01'),date('Y-m-d')];
        $dataRajal = $searchRajal->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhereTgl);
		
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/rawat-poli?start='.date('Y-m-').'01&end='.date('Y-m-d');
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$url_kunjungan = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/jenis-rawat?start='.date('Y-m-').'01&end='.date('Y-m-d');
		$content_kunjungan = file_get_contents($url_kunjungan);
		$json_kunjungan = json_decode($content_kunjungan, true);
		
		return $this->render('kunjungan',[
			'searchRajal'=>$searchRajal,
			'dataRajal'=>$dataRajal,
			'model'=>$json,
			'model_kunjungan'=>$json_kunjungan,
		]);
	}
	public function actionShow($start='',$end=''){
		$searchRajal = new RawatSearch();
		$where = ['hide'=>0];
		$andWhere = ['<>','status',5];
		$andWhereTgl = ['between','DATE_FORMAT(tglmasuk,"%Y-%m-%d")',$start,$end];
        $dataRajal = $searchRajal->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhereTgl);
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/rawat-poli?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$url_kunjungan = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/jenis-rawat?start='.$start.'&end='.$end;
		$content_kunjungan = file_get_contents($url_kunjungan);
		$json_kunjungan = json_decode($content_kunjungan, true);
		return $this->renderAjax('kunjungan-show',[
			'searchRajal'=>$searchRajal,
			'dataRajal'=>$dataRajal,
			'model'=>$json,
			'model_kunjungan'=>$json_kunjungan,
		]);
	}
	
    public function actionIndex($rawat=null)
    {
		// $url = Yii::$app->params['baseUrl'].'dashboard/pasien-rest/pasien-sex?start='.date('Y-m-').'01&end='.date('Y-m-d').'&rawat='.$rawat;
        $url = "https://new-simrs.rsausulaiman.com/dashboard/pasien-rest/pasien-sex?start=".date('Y-m-d')."&end=".date('Y-m-d')."&rawat=".$rawat;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
        return $this->render('index',
			['model'=>$json,'rawat'=>$rawat]
		);
    }
	public function actionShowKunjungan($start='',$end='',$rawat=''){
		$url = "https://new-simrs.rsausulaiman.com/dashboard/pasien-rest/pasien-sex?start=".$start."&end=".$end."&rawat=".$rawat;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
        return $this->renderAjax('show-cari',
			['model'=>$json]
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
