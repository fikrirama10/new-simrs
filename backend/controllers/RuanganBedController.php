<?php

namespace backend\controllers;

use Yii;
use common\models\Ruangan;
use yii\base\Model;
use common\models\RuanganBed;
use common\models\RuanganBedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RuanganBedController implements the CRUD actions for RuanganBed model.
 */
class RuanganBedController extends Controller
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
     * Lists all RuanganBed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RuanganBedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RuanganBed model.
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
     * Creates a new RuanganBed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RuanganBed();
		
		
        if ($model->load(Yii::$app->request->post())&&Model::validateMultiple([$model])) {
			$ruangan = Ruangan::find()->where(['id'=>$model->idruangan])->one();
			
			$model->genKode();
			if($model->save(false)){
				$bed = RuanganBed::find()->where(['idruangan'=>$model->idruangan])->andwhere(['status'=>1])->count();
				$ruangan->kapasitas = $bed;
				$ruangan->save(false);
				return $this->redirect(['view', 'id' => $model->id]);
			}else{
				return $this->render('create', [
					'model' => $model,
				]);
			}
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RuanganBed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$ruangan = Ruangan::find()->where(['id'=>$model->idruangan])->one();
        if ($model->load(Yii::$app->request->post())&&Model::validateMultiple([$model])) {
            if($model->save(false)){
				$bed = RuanganBed::find()->where(['idruangan'=>$model->idruangan])->andwhere(['status'=>1])->count();
				$ruangan->kapasitas = $bed;
				$ruangan->save(false);
				return $this->redirect(['ruangan/'.$ruangan->id]);
			}else{
				return $this->render('update', [
					'model' => $model,
				]);
			}
			
		}

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RuanganBed model.
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
     * Finds the RuanganBed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RuanganBed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RuanganBed::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
