<?php

namespace backend\controllers;

use Yii;
use common\models\Gizi;
use common\models\GiziSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\RawatSearch;
use common\models\Rawat;
use common\models\Pasien;
use kartik\mpdf\Pdf;

/**
 * GiziController implements the CRUD actions for Gizi model.
 */
class GiziController extends Controller
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
     * Lists all Gizi models.
     * @return mixed
     */
     public function actionIndex()
    {
		$url = Yii::$app->params['baseUrl']."dashboard/rest/bed";
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
        $searchModel = new RawatSearch();
		$where = ['status'=>2];
		$andWhere = ['idjenisrawat'=>2];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere);
        return $this->render('index',[
			'bed'=>$json,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionShow($id){
		$model = Rawat::find()->where(['no_rm'=>$id])->andwhere(['idjenisrawat'=>2])->andwhere(['status'=>2])->all();
		
		return $this->renderAjax('show',[
			'model'=>$model,
		]);
	}
    /**
     * Displays a single Gizi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHapus($id){
		$model = $this->findModel($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionLabel($id) {
		if(Yii::$app->user->isGuest){
		return $this->redirect(['site/logout']);
		}
		$gizi = Gizi::findOne($id);
		$model = Rawat::find()->where(['id' =>$gizi->idrawat ])->one();
		$content = $this->renderPartial('label',['model' => $model,'gizi'=>$gizi]);
		  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		    'format' => [50,20],
		   'marginTop' => '2',
		   'orientation' => Pdf::ORIENT_PORTRAIT, 
		   'marginLeft' => '5',
		   'marginRight' => '0',
		   'marginBottom' => '2',
		   'content' => $content,  
		   'cssFile' => '@frontend/web/css/paper-pasien.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

	// return the pdf output as per the destination setting
	return $pdf->render(); 
	}
    public function actionView($id)
    {
		$model = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$diit = Gizi::find()->where(['idrawat'=>$model->id])->all();
		$gizi = new Gizi();
		if ($gizi->load(Yii::$app->request->post()) ) {
			$gizi->idrawat = $model->id;
			$gizi->no_rm = $model->no_rm;
			$gizi->iddokter = $model->iddokter;
			$gizi->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			if($gizi->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }
        return $this->render('view', [
            'model' => $model,
            'pasien' => $pasien,
            'gizi' => $gizi,
            'diit' => $diit,
        ]);
    }

    /**
     * Creates a new Gizi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gizi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Gizi model.
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
     * Deletes an existing Gizi model.
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
     * Finds the Gizi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gizi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gizi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
