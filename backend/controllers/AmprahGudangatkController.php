<?php

namespace backend\controllers;

use Yii;
use common\models\AmprahGudangatk;
use common\models\DataBarangSearch;
use common\models\DataBarang;
use common\models\AmprahGudangatkDetail;
use common\models\AmprahGudangatkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AmprahGudangatkController implements the CRUD actions for AmprahGudangatk model.
 */
class AmprahGudangatkController extends Controller
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
     * Lists all AmprahGudangatk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AmprahGudangatkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AmprahGudangatk model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new DataBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$barang = DataBarang::find()->where(['>','stok',0])->all();
		$amprah = AmprahGudangatkDetail::find()->where(['idamprah'=>$id])->all();
		return $this->render('view', [
            'model' => $this->findModel($id),
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'barang' => $barang,
            'amprah' => $amprah,
        ]);
    }
	public function actionBarangKeluar($id,$jumlah,$idso){
		$model = new AmprahGudangatkDetail();
		$barang = DataBarang::findOne($id);
		$model->idamprah = $idso;
		$model->idbarang = $id;
		$model->jumlah = $jumlah;
		$model->status = 0;
		if($barang->stok < $jumlah){
			$detail ='404';
			return \yii\helpers\Json::encode($detail);
		}else{
			if($model->save()){
				$detail = 'Success';
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
		
		//return \yii\helpers\Json::encode($detail);
	}
	public function actionBatalkan($id){
		$model = $this->findModel($id);
		$detail = AmprahGudangatkDetail::find()->where(['idamprah'=>$model->id])->all();
		foreach($detail as $d){
			$d->delete();
		}
		$model->delete();
		return $this->redirect(['index']);
	}
	public function actionHapus($id){
		$model = AmprahGudangatkDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionSerahkan($id){
		$model = $this->findModel($id);
		$detail = AmprahGudangatkDetail::find()->where(['idamprah'=>$model->id])->all();
		//$tgl = date('Y-m-d');
		foreach($detail as $pr){
			$pr->status = 1;
			$barang = DataBarang::find()->where(['id'=>$pr->idbarang])->one();
			Yii::$app->kazo->mutasiamprah($pr->idbarang,1,5,$barang->stok,$pr->jumlah,$model->idpeminta,10,'Barang Keluar Ke Ruangan '.$model->ruangan->ruangan.'');
			$barang->stok = $barang->stok - $pr->jumlah;
			$barang->save(false);
			$pr->save();
		}
		$model->status = 2;
		$model->save();
		return $this->redirect(['index']);
	}
    /**
     * Creates a new AmprahGudangatk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AmprahGudangatk();

        if ($model->load(Yii::$app->request->post()) ) {
			$model->genKode();
			$model->idasal = 10;
			$model->tgl_penyerahan = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$model->status = 1;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AmprahGudangatk model.
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
     * Deletes an existing AmprahGudangatk model.
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
     * Finds the AmprahGudangatk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AmprahGudangatk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AmprahGudangatk::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
