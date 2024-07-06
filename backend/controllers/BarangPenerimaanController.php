<?php

namespace backend\controllers;

use Yii;
use common\models\BarangPenerimaan;
use common\models\BarangPenerimaanDetail;
use common\models\BarangPenerimaanSearch;
use common\models\DataBarangSearch;
use common\models\DataBarang;
use common\models\Barang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BarangPenerimaanController implements the CRUD actions for BarangPenerimaan model.
 */
class BarangPenerimaanController extends Controller
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
     * Lists all BarangPenerimaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BarangPenerimaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BarangPenerimaan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHapusItem($id){
		$model = BarangPenerimaanDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
    public function actionBatalkan($id){
		$model = $this->findModel($id);
		$list_terima = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$id])->all();
		foreach($list_terima as $lt){
			$lt->delete();
		}
		//$model->status = 1;
		
		$model->delete();
		return $this->redirect(['index']);
	}
    public function actionSelesai($id){
		$model = $this->findModel($id);
		$list_terima = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$id])->all();
		$total = 0;
		foreach($list_terima as $lt){
			$total += $lt->total;
			//$barang = DataBarang::find()->where(['id'=>$lt->idbarang])->one();
			//Yii::$app->kazo->mutasiamprah($lt->idbarang,2,2,$barang->stok,$lt->qty,0,12);
			//$barang->stok = $barang->stok + $lt->qty;
			//$barang->save(false);
		}
		//$model->status = 1;
		$model->total_penerimaan = $total;
		$model->save();
		return $this->redirect(['index']);
	}
    public function actionView($id)
    {
		$searchModel = new DataBarangSearch();
		$dataBarang = new DataBarang();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$model = $this->findModel($id);
		$list_terima = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$id])->all();
		$barang = DataBarang::find()->all();
		$detail = new BarangPenerimaanDetail();
		 if ($detail->load(Yii::$app->request->post())) {
			 $detail->total = $detail->harga * $detail->qty;
			 $detail->status = 1;
			 if($detail->save()){
				 return $this->refresh();
			 }
		 }else if ($dataBarang->load(Yii::$app->request->post()) ) {
			$dataBarang->genKode();
			if($dataBarang->save()){
				
			}
            return $this->refresh();
        }
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'barang' => $barang,
            'detail' => $detail,
             'dataBarang' => $dataBarang,
            'list_terima' => $list_terima,
        ]);
    }

    /**
     * Creates a new BarangPenerimaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BarangPenerimaan();

        if ($model->load(Yii::$app->request->post())) {
			$model->status = 1;
			$model->total_penerimaan = 0;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BarangPenerimaan model.
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
     * Deletes an existing BarangPenerimaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $list_terima = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$id])->all();
		foreach($list_terima as $lt){
			$lt->delete();
		}
		$model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BarangPenerimaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BarangPenerimaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BarangPenerimaan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
