<?php

namespace backend\controllers;

use Yii;
use common\models\ObatDropingTransaksi;
use common\models\ObatDropingTransaksiDetailSearch;
use common\models\ObatDropingBatch;
use common\models\ObatDropingTransaksiDetail;
use common\models\ObatDropingTransaksiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObatDropingTransaksiController implements the CRUD actions for ObatDropingTransaksi model.
 */
class ObatDropingTransaksiController extends Controller
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
     * Lists all ObatDropingTransaksi models.
     * @return mixed
     */
	public function actionShowBatch($id){
		$model = ObatDropingBatch::find()->where(['idobat'=>$id])->all();
		$barang = new ObatDropingTransaksiDetail();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
			'id'=>$id,
			'barang'=>$barang,
		]);
	}
	public function actionShowMerk($id){
		$model = ObatDropingBatch::find()->where(['idobat'=>$id])->all();
		$bacth = new ObatDropingBatch();
		return $this->renderAjax('show-merk',[
			'model'=>$model,
			'id'=>$id,
			'bacth'=>$bacth,
			
		]);
	}
    public function actionIndex()
    {
        $searchModel = new ObatDropingTransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObatDropingTransaksi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
        if($model->idjenis == 1){
				return $this->redirect(['barang-masuk', 'id' => $model->id]);
			}else if($model->idjenis == 2){
				return $this->redirect(['barang-keluar', 'id' => $model->id]);
			}else{
				return $this->redirect(['barang-keluar', 'id' => $model->id]);
			}
    }
	public function actionBarangMasukStok($id){
		$model = ObatDropingTransaksi::findOne($id);
		$detail = ObatDropingTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		foreach($detail as $d){
			$bacth = ObatDropingBatch::find()->where(['id'=>$d->idbatch])->one();
			Yii::$app->kazo->dropingmutasiStok($d->idobat,$d->id,2,6,$d->jumlah,'Penerimaan Barang',$bacth->stok);
			Yii::$app->kazo->dropingkartuStok($d->idobat,$d->idbatch,$d->jumlah,1);
			$bacth->stok = $bacth->stok + $d->jumlah;
			$bacth->save();
		}
		$model->status = 2;
		$model->save(false);
		return $this->redirect(['/obat-droping-transaksi']);
	}
	public function actionBarangKeluarStok($id){
		$model = ObatDropingTransaksi::findOne($id);
		$detail = ObatDropingTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		if($model->idjenis == 2){
			$keterangan = 'Pengeluaran Barang';
		}else if($model->idjenis == 3){
			$keterangan = 'Pengeluaran Barang ED';
		}
		foreach($detail as $d){
			$bacth = ObatDropingBatch::find()->where(['id'=>$d->idbatch])->one();
			Yii::$app->kazo->dropingmutasiStok($d->idobat,$d->id,1,5,$d->jumlah,$keterangan,$bacth->stok);
			Yii::$app->kazo->dropingkartuStok($d->idobat,$d->idbatch,$d->jumlah,2);
			$bacth->stok = $bacth->stok - $d->jumlah;
			$bacth->save();
		}
		$model->status = 2;
		$model->save(false);
		return $this->redirect(['/obat-droping-transaksi']);
	}

	public function actionBarangMasuk($id)
    {
		$searchModel = new ObatDropingTransaksiDetailSearch();
		$model = $this->findModel($id);
		$where = ['idtrx'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$bacth = new ObatDropingBatch();
		$barang = new ObatDropingTransaksiDetail();
		if($barang->load(Yii::$app->request->post()) && $bacth->load(Yii::$app->request->post())) {

			$cek_bacth = ObatDropingBatch::findOne($barang->idbatch);
			$barang->status = 1;
			if($cek_bacth){
				if($barang->save()){
					$cek_bacth->save();
					return $this->refresh();
				}
			}else{
					$bacth->idobat = $barang->idobat;
					$bacth->stok = 0;
					if($bacth->save(false)){
					$barang->idbatch = $bacth->id;
						if($barang->save()){
							return $this->refresh();
						}
					}
				}
		}
        return $this->render('barang-masuk', [
            'model' => $model,
            'barang' => $barang,
            'bacth' => $bacth,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	public function actionEditItem($id){
		$model = ObatDropingTransaksiDetail::findOne($id);
		$trx = ObatDropingTransaksi::findOne($model->idtrx);
		if($trx->status == 1){
			return $this->render('edit-item',[
				'model'=>$model,
			]);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionHapus($id){
		$model = ObatDropingTransaksiDetail::findOne($id);
		$trx = ObatDropingTransaksi::findOne($model->idtrx);
		if($trx->status == 1){
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer);
		}else{
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionBarangKeluar($id)
    {
        $searchModel = new ObatDropingTransaksiDetailSearch();
		$model = $this->findModel($id);
		$where = ['idtrx'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$barang = new ObatDropingTransaksiDetail();
		if($barang->load(Yii::$app->request->post())) {
			$obat = ObatDropingTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idobat'=>$barang->idobat])->andwhere(['idbatch'=>$barang->idbatch])->one();
			$bacth = ObatDropingBatch::find()->where(['id'=>$barang->idbatch])->one();
			if($obat){
				Yii::$app->session->setFlash('warning', 'Barang sudah ada di list');
				return $this->refresh();
			}
			if($bacth->stok < $barang->jumlah){
				Yii::$app->session->setFlash('warning', 'Stok Barang tidak cukup');
				return $this->refresh();
			}
			$barang->status = 1;
			if($barang->save()){
				return $this->refresh();
			}
			
		}
		return $this->render('barang-keluar',[
			'barang'=>$barang,
			'model'=>$model,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionBarangEd($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ObatDropingTransaksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObatDropingTransaksi();

         if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->status = 1;
			if($model->save(false)){
			if($model->idjenis == 1){
				return $this->redirect(['barang-masuk', 'id' => $model->id]);
			}else if($model->idjenis == 2){
				return $this->redirect(['barang-keluar', 'id' => $model->id]);
			}else{
				return $this->redirect(['barang-ed', 'id' => $model->id]);
			}
            
			}
		}
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ObatDropingTransaksi model.
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
     * Deletes an existing ObatDropingTransaksi model.
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
     * Finds the ObatDropingTransaksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObatDropingTransaksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObatDropingTransaksi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
