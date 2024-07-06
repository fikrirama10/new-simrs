<?php

namespace backend\controllers;

use Yii;
use common\models\BarangStokopname;
use common\models\BarangStokopnameDetail;
use common\models\BarangStokopnameDetailSearch;
use common\models\DataBarangSearch;
use common\models\DataBarang;
use common\models\BarangStokopnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BarangStokopnameController implements the CRUD actions for BarangStokopname model.
 */
class BarangStokopnameController extends Controller
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
     * Lists all BarangStokopname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BarangStokopnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BarangStokopname model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	 public function actionEdit($id){
		$model = BarangStokopnameDetail::findOne($id);
		if($model->status == 1){
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
		if ($model->load(Yii::$app->request->post())) {
			$model->selisih = $model->stokasal - $model->stokreal;
			$model->nilaiselisih = $model->harga * $model->selisih;
			if($model->save()){
				return $this->redirect(['barang-stokopname/view?id='.$model->idso]);
			}
		}
		return $this->render('edit',[
			'model'=>$model,
		]);
	}
	public function actionCocokStok($id){
		$model = BarangStokopname::findOne($id);
		$detail = BarangStokopnameDetail::find()->where(['idso'=>$model->id])->andwhere(['status'=>0])->all();
		foreach($detail as $d){
			$barang = DataBarang::findOne($d->idbarang);
			if($d->selisih != 0){
				$barang->stok = $d->stokreal;
				$keterangan = 'Koreksi Barang Stok Opname';
				Yii::$app->kazo->mutasiamprah($d->idbarang,2,7,$barang->stok,$d->stokreal,Yii::$app->user->identity->userdetail->idunit,Yii::$app->user->identity->userdetail->idunit,$keterangan);
				$barang->save();
			}
			$d->status = 1;
			$d->save();
		}
		$model->status = 2;
		if($model->save()){
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	
		
	}
    public function actionBarangSo($id,$jumlah,$idso){
		$model = new BarangStokopnameDetail();
		$barang = DataBarang::findOne($id);
		$model->idso = $idso;
		$model->idbarang = $id;
		$model->stokasal = $barang->stok;
		$model->harga = $barang->harga;
		$model->status = 0;
		$model->selisih = $barang->stok - $jumlah;
		$model->nilaiselisih = $barang->harga * $model->selisih;
		$model->stokreal = $jumlah;
		if($model->save()){
			$detail = 'Success';
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);		}
		return \yii\helpers\Json::encode($detail);
	}
    public function actionHapus($id){
		$model = BarangStokopnameDetail::findOne($id);
		if($model->status == 1){
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}else{
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer);
		}
		
	}
    public function actionView($id)
    {
		$where = ['idso'=>$id];
		$searchModel = new DataBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$searchModel2 = new BarangStokopnameDetailSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams,$where);
		$model =  $this->findModel($id);
		$barang = DataBarang::find()->all();
        return $this->render('view', [
            'model' => $model,
            'barang' => $barang,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
        ]);
    }

    /**
     * Creates a new BarangStokopname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BarangStokopname();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->status = 1;
			$model->tgl_so = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$model->iduser = Yii::$app->user->identity->id;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BarangStokopname model.
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
     * Deletes an existing BarangStokopname model.
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
     * Finds the BarangStokopname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BarangStokopname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BarangStokopname::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
