<?php

namespace backend\controllers;

use Yii;
use common\models\ObatDroping;
use common\models\ObatDropingSearch;
use common\models\ObatDropingBatchSearch;
use common\models\ObatDropingBatch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObatDropingController implements the CRUD actions for ObatDroping model.
 */
class ObatDropingController extends Controller
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
     * Lists all ObatDroping models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObatDropingSearch();
	
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObatDroping model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model =  $this->findModel($id);
		$searchModel = new ObatDropingBatchSearch();
		$where = ['idobat'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$obat = new ObatDropingBatch();
		if ($obat->load(Yii::$app->request->post())) {
			
			if($obat->save(false)){
				if($obat->stok > 0){
					Yii::$app->kazo->dropingmutasiStok($obat->idobat,$obat->id,2,6,$obat->stok,'Barang Masuk manual',0);
					Yii::$app->kazo->dropingkartuStok($obat->idobat,$obat->id,$obat->stok,1);
				}
				return $this->refresh();
			}
        }
        return $this->render('view', [
            'model' => $model,
            'obat' => $obat,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionDropingEditBacth($id){
		$model = ObatDropingBatch::findOne($id);
		if ($model->load(Yii::$app->request->post())){
			return $this->redirect('index');
		}
		return $this->render('edit-bacth',[
			'model'=>$model,
		]);
	}
	public function actionEditBacth($id){
		$model = ObatDropingBatch::findOne($id);
		$tgl = date('Y-m-d H:i:s');
		if ($model->load(Yii::$app->request->post())) {
			$koreksi = Yii::$app->request->post('koreksi');	
			// return $koreksi;
			$koreksi_apotek = Yii::$app->request->post('koreksi2');	
			if($koreksi == null){
				$model->stok_gudang = $model->stok_gudang;
			}else if($koreksi < $model->stok){
				$jumlah = $model->stok - $koreksi;
				Yii::$app->kazo->dropingmutasiStok($model->idobat,$model->id,5,8,$jumlah,'Barang Koreksi',$model->stok);
				Yii::$app->kazo->dropingkartuStok($model->idobat,$model->id,$jumlah,1);
				$model->stok = $koreksi;
			}else if($koreksi > $model->stok){
				$jumlah = $koreksi - $model->stok;
				
				
				Yii::$app->kazo->dropingmutasiStok($model->idobat,$model->id,3,7,$model->stok,'Barang Koreksi',0);
				Yii::$app->kazo->dropingkartuStok($model->idobat,$model->id,$model->stok,2);
				$model->stok = $koreksi;
				
			}
			if($model->save(false)){				
				return $this->redirect(['/obat-droping/view?id='.$model->idobat]);				
			}
			
		}
		return $this->render('edit-bacth',[
			'model'=>$model,
		]);
	}
    /**
     * Creates a new ObatDroping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	public function actionGetBatch()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = ObatDropingBatch::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionShowBatch($id){
		$model = ObatDropingBatch::find()->where(['idobat'=>$id])->all();
		$resep_obat = new ObatDropingBatch();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
			'resep_obat'=>$resep_obat,
		]);
	}
    public function actionCreate()
    {
        $model = new ObatDroping();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ObatDroping model.
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
     * Deletes an existing ObatDroping model.
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
     * Finds the ObatDroping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObatDroping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObatDroping::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
