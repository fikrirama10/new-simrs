<?php

namespace backend\controllers;

use Yii;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\TindakanSeach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TindakanController implements the CRUD actions for Tindakan model.
 */
class TindakanController extends Controller
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
     * Lists all Tindakan models.
     * @return mixed
     */
	public function actionEditTarif($id){
		$tn = TindakanTarif::find()->where(['id'=>$id])->one();
		if($tn->load($this->request->post())){
			if($tn->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
    public function actionIndex()
    {
        $searchModel = new TindakanSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tindakan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$tarif = new TindakanTarif();
		$model =  $this->findModel($id);
		if ($tarif->load(Yii::$app->request->post())) {
			$model->status = 1;
			$model->save(false);
			$tarif->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('view', [
            'model' => $model,
            'tarif' => $tarif,
        ]);
    }

    /**
     * Creates a new Tindakan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tindakan();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tindakan model.
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
     * Deletes an existing Tindakan model.
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
     * Finds the Tindakan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tindakan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tindakan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
