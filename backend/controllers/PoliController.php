<?php

namespace backend\controllers;

use Yii;
use common\models\Poli;
use common\models\PoliSearch;
use common\models\Rawat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoliController implements the CRUD actions for Poli model.
 */
class PoliController extends Controller
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
     * Lists all Poli models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoliSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Poli model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAntrian($idpoli=''){
		$poli = Poli::findOne($idpoli);
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
		$rawat_one= Rawat::find()->where(['idpoli'=>$idpoli])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl])->andwhere(['status'=>1])->orderBy(['no_antrian'=>SORT_ASC])->one();
		if($rawat_one){
		$rawat= Rawat::find()->where(['idpoli'=>$idpoli])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl])->andwhere(['<>','id',$rawat_one->id])->andwhere(['status'=>1])->orderBy(['no_antrian'=>SORT_ASC])->limit(5)->all();
		}else{
			$rawat = '';
		}
		return $this->render('antrian',[
			'rawat'=>$rawat,
			'rawat_one'=>$rawat_one,
			'poli'=>$poli,
		]);
	}
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Poli model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Poli();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Poli model.
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
     * Deletes an existing Poli model.
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
     * Finds the Poli model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Poli the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Poli::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
