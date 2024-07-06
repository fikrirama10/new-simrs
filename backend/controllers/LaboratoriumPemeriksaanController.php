<?php

namespace backend\controllers;

use Yii;
use common\models\LaboratoriumPemeriksaan;
use common\models\LaboratoriumForm;
use common\models\Tindakan;
use common\models\LaboratoriumPemeriksaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LaboratoriumPemeriksaanController implements the CRUD actions for LaboratoriumPemeriksaan model.
 */
class LaboratoriumPemeriksaanController extends Controller
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
     * Lists all LaboratoriumPemeriksaan models.
     * @return mixed
     */
	public function actionEditLab($id){
		$cl = LaboratoriumForm::find()->where(['id'=>$id])->one();
		if($cl->load($this->request->post())){
			if($cl->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
    public function actionIndex()
    {
        $searchModel = new LaboratoriumPemeriksaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LaboratoriumPemeriksaan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$item = new LaboratoriumForm();
		$model = $this->findModel($id);
		if ($item->load(Yii::$app->request->post()) && $item->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('view', [
            'model' => $model,
            'item' => $item,
        ]);
    }

    /**
     * Creates a new LaboratoriumPemeriksaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LaboratoriumPemeriksaan();
        if ($model->load(Yii::$app->request->post()) ) {			
			$model->genKode();
			if($model->save(false)){
				return $this->redirect(['view', 'id' => $model->id]);
			}
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LaboratoriumPemeriksaan model.
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
     * Deletes an existing LaboratoriumPemeriksaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHapusItem($id){
		$model = LaboratoriumForm::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LaboratoriumPemeriksaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LaboratoriumPemeriksaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LaboratoriumPemeriksaan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
