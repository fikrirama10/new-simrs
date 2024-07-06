<?php

namespace backend\controllers;

use Yii;
use common\models\ObatBacth;
use common\models\ObatBacthSearch;
use common\models\ObatMutasi;
use common\models\ObatStokopname;
use common\models\ObatStokopnameDetail;
use common\models\ObatStokopnameDetailSearch;
use common\models\ObatStokOpnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObatStokopnameController implements the CRUD actions for ObatStokOpname model.
 */
class ObatStokopnameController extends Controller
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
     * Lists all ObatStokOpname models.
     * @return mixed
     */
    public function actionIndex()
    {
		$where = ['idgudang'=>Yii::$app->user->identity->userdetail->idgudang];
        $searchModel = new ObatStokOpnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObatStokOpname model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionPrintList($awal,$akhir){
		$model = ObatMutasi::find()->where(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$awal,$akhir])->groupBy('idobat')->all();
		return $this->render('list',['model'=>$model]);
	}
    public function actionHapus($id){
		$model = ObatStokOpnameDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionKlarifikasi($id){
		$model = ObatStokOpnameDetail::findOne($id);
		if ($model->load(Yii::$app->request->post())) {
			if($model->save(false)){
				return $this->redirect(['obat-stokopname/view?id='.$model->id_so]);
			}
		}
		return $this->render('klarifikasi',[
			'model'=>$model,
		]);
	}
    public function actionEdit($id){
		$model = ObatStokOpnameDetail::findOne($id);
		$so = ObatStokOpname::find()->where(['id'=>$model->id_so])->one();
		if ($model->load(Yii::$app->request->post())) {
			$bobat = ObatBacth::findOne($model->idbatch);
			if($so->idgudang == 1){
				$model->stok_asal = $bobat->stok_gudang;
			}else{
				$model->stok_asal = $bobat->stok_apotek;
			}
			$model->selisih = $model->jumlah - $model->stok_asal ;
			
			$model->total = $model->harga * $model->selisih;
			if($model->selisih < 0){
				$model->keterangan = 'Barang Hilang';
				$model->status = 3;
			}else if($model->selisih > 0){
				$model->keterangan = 'Barang Lebih';
				$model->status = 2;
			}else{
				$model->keterangan = 'Cocok';
				$model->status = 1;
			}
			if($model->save()){
				return $this->redirect(['obat-stokopname/view?id='.$so->id]);
			}
		}
		return $this->render('edit',[
			'model'=>$model,
			'so'=>$so,
		]);
	}

    public function actionStokAdjustmen($id){
		$model = $this->findModel($id);
		$detail = ObatStokOpnameDetail::find()->where(['id_so'=>$model->id])->andwhere(['<>','status',1])->all();
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		foreach($detail as $d):
			$obat = ObatBacth::find()->where(['id'=>$d->idbatch])->one();
			if($model->idgudang == 1){
				if($d->status == 2){
					$jumlah = $d->jumlah - $d->stok_asal;
					Yii::$app->kazo->kartuStok($d->idbarang,$d->idbatch,$model->idgudang,$jumlah,2);	
					Yii::$app->kazo->mutasiStok($d->idbarang,$d->idbatch,3,7,$jumlah,0,$obat->stok_gudang,$model->idgudang);
					$obat->stok_gudang = $obat->stok_gudang + $jumlah;
					Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idbarang,$jumlah,$tgl,2);
				}else{
					$jumlah = $d->stok_asal - $d->jumlah;
					Yii::$app->kazo->kartuStok($d->idbarang,$d->idbatch,$model->idgudang,$jumlah,1);	
					Yii::$app->kazo->mutasiStok($d->idbarang,$d->idbatch,5,8,$jumlah,0,$obat->stok_gudang,$model->idgudang);
					$obat->stok_gudang = $obat->stok_gudang - $jumlah;
					Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idbarang,$jumlah,$tgl,1);
				}
			}else{
				if($d->status == 2){
					$jumlah = $d->jumlah - $d->stok_asal;
					Yii::$app->kazo->kartuStok($d->idbarang,$d->idbatch,$model->idgudang,$jumlah,2);	
					Yii::$app->kazo->mutasiStok($d->idbarang,$d->idbatch,3,7,$jumlah,0,$obat->stok_apotek,$model->idgudang);
					$obat->stok_apotek = $obat->stok_apotek + $jumlah;
					Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idbarang,$jumlah,$tgl,2);
				}else{
					$jumlah = $d->stok_asal - $d->jumlah;
					Yii::$app->kazo->kartuStok($d->idbarang,$d->idbatch,$model->idgudang,$jumlah,1);	
					Yii::$app->kazo->mutasiStok($d->idbarang,$d->idbatch,5,8,$jumlah,0,$obat->stok_apotek,$model->idgudang);
					$obat->stok_apotek = $obat->stok_apotek - $jumlah;
					Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idbarang,$jumlah,$tgl,1);
				}
			}
			$obat->save();
		endforeach;
		$model->status = 2;
		$model->save();
		return $this->redirect(['/obat-stokopname']);
		
	}
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$detail = new ObatStokopnameDetail();
		$searchModel = new ObatStokopnameDetailSearch();
		$where = ['id_so'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$searchBacth = new ObatBacthSearch();
        $dataBatch = $searchBacth->search(Yii::$app->request->queryParams);
		$obat = ObatBacth::find()->all();
		if ($detail->load(Yii::$app->request->post())) {
			$cek_so = ObatStokopnameDetail::find()->where(['id_so'=>$model->id])->andwhere(['idbarang'=>$detail->idbarang])->andwhere(['idbatch'=>$detail->idbatch])->count();
			if($cek_so > 0){
				Yii::$app->session->setFlash('danger', 'Barang sudah masuk stokopname');
				return $this->refresh();
			}
			$bobat = ObatBacth::findOne($detail->idbatch);
			if($model->idgudang == 1){
				$detail->stok_asal = $bobat->stok_gudang;
			}else{
				$detail->stok_asal = $bobat->stok_apotek;
			}
			
			$detail->id_so = $model->id;
			$detail->selisih = $detail->jumlah - $detail->stok_asal ;
			
			$detail->total = $detail->harga * $detail->selisih;
			if($detail->selisih < 0){
				$detail->keterangan = 'Barang Hilang';
				$detail->status = 3;
			}else if($detail->selisih > 0){
				$detail->keterangan = 'Barang Lebih';
				$detail->status = 2;
			}else{
				$detail->keterangan = 'Cocok';
				$detail->status = 1;
			}
			if($detail->save()){
				return $this->refresh();
			}
		}
        return $this->render('view2', [
            'model' => $this->findModel($id),
            'detail' => $detail,
            'obat' => $obat,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataBatch' => $dataBatch,
            'searchBacth' => $searchBacth,
        ]);
    }
	public function actionShowBacth($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->andwhere(['status'=>1])->all();
		return $this->renderAjax('show-bacth',[
			'model'=>$model,
		]);
	}

    /**
     * Creates a new ObatStokOpname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObatStokopname();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode($model->idperiode);
			$model->status = 1;
			$model->idgudang = Yii::$app->user->identity->userdetail->idgudang;
			$model->iduser = Yii::$app->user->identity->id;
			$model->tgl_so = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$model->jam_mulai = date('H:i:s',strtotime('+6 hour',strtotime(date('H:i:s'))));
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ObatStokOpname model.
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
     * Deletes an existing ObatStokOpname model.
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
     * Finds the ObatStokOpname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObatStokOpname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObatStokOpname::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
