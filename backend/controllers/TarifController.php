<?php

namespace backend\controllers;

use Yii;
use common\models\Tarif;
use common\models\TarifSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TarifController implements the CRUD actions for Tarif model.
 */
class TarifController extends Controller
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
     * Lists all Tarif models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TarifSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tarif model.
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
     * Creates a new Tarif model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tarif();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->tarif = $model->medis + $model->paramedis + $model->petugas + $model->apoteker + $model->gizi + $model->bph + $model->sewakamar+ $model->sewaalat + $model->makanpasien+ $model->laundry + $model->cs + $model->opsrs+ $model->nova_t+ $model->perekam_medis + $model->radiografer + $model->radiolog + $model->assisten+ $model->operator + $model->ass_tim + $model->dokter_anastesi + $model->sewa_ok + $model->asisten_anastesi + $model->cssd + $model->bbm + + $model->ranmor + $model->supir + $model->dokter + $model->rs + $model->harbang + $model->atlm;
			
			if($model->save(false)){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	
	public function actionShowInputan($id){
		$model = new Tarif();
		if($id == 1){
			return $this->renderAjax('show-inputan-rajal',[
				'model'=>$model,
				'id'=>$id,
			]);
		}else if($id == 2){
			return $this->renderAjax('show-inputan-ranap',[
				'model'=>$model,
				'id'=>$id,
			]);
		}else if($id == 3){
			return $this->renderAjax('show-inputan-ugd',[
				'model'=>$model,
				'id'=>$id,
			]);
		}else if($id == 4){
			return $this->renderAjax('show-inputan-lab',[
				'id'=>$id,
				'model'=>$model,
			]);
		}else if($id == 5){
			return $this->renderAjax('show-inputan-radiologi',[
				'model'=>$model,
				'id'=>$id
			]);
		}else if($id == 6){
			return $this->renderAjax('show-inputan-administrasi',[
				'model'=>$model,
				'id'=>$id
			]);
		}
	}
    /**
     * Updates an existing Tarif model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			$model->tarif = $model->medis + $model->paramedis + $model->petugas + $model->apoteker + $model->gizi + $model->bph + $model->sewakamar+ $model->sewaalat + $model->makanpasien+ $model->laundry + $model->cs + $model->opsrs+ $model->nova_t+ $model->perekam_medis + $model->radiografer + $model->radiolog + $model->assisten+ $model->operator + $model->ass_tim + $model->dokter_anastesi + $model->sewa_ok + $model->asisten_anastesi + $model->cssd + $model->bbm + + $model->ranmor + $model->supir + $model->dokter + $model->rs + $model->harbang + $model->atlm;
			if($model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tarif model.
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
     * Finds the Tarif model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tarif the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tarif::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
