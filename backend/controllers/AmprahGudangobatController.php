<?php

namespace backend\controllers;

use Yii;
use common\models\ObatBacth;
use common\models\ObatBacthSearch;
use common\models\AmprahGudangobatDetail;
use common\models\AmprahGudangobat;
use common\models\AmprahGudangobatSearch;
use common\models\ObatSeacrh;
use common\models\Obat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\AmprahGudangobatTarik;

/**
 * AmprahGudangobatController implements the CRUD actions for AmprahGudangobat model.
 */
class AmprahGudangobatController extends Controller
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
     * Lists all AmprahGudangobat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AmprahGudangobatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AmprahGudangobat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTarikSemua($id){
		$model = AmprahGudangobat::findOne($id);
		$detail = AmprahGudangobatDetail::find()->where(['idamprah'=>$model->id])->all();
		foreach($detail as $d){
			$tarik = new AmprahGudangobatTarik();
			$tarik_detail = AmprahGudangobatTarik::find()->where(['idobat'=>$d->idobat])->andwhere(['idamprah'=>$model->id])->andwhere(['status'=>1])->all();
			if(count($tarik_detail) < 1){
				$tarik = new AmprahGudangobatTarik();
				$tarik->idamprah = $model->id;
				$tarik->iddetail = $d->id;
				$tarik->idobat = $d->idobat;
				$tarik->idbacth = $d->idbacth;
				$tarik->nama_obat = $d->obat->nama_obat;
				$tarik->jumlah_asal = $d->jumlah_diserahkan;
				$tarik->jumlah = $d->jumlah_diserahkan;
				$tarik->tgl = date('Y-m-d',strtotime('+7 hours'));
				$tarik->status = 1;
				$tarik->save();
			}
		}
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionHapusTarik($id){
		$detail_tarik = AmprahGudangobatTarik::findOne($id);
		$detail_tarik->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionTarikObat($id){
		$model = AmprahGudangobat::findOne($id);
		$detail = AmprahGudangobatDetail::find()->where(['idamprah'=>$model->id])->all();
		$detail_tarik = AmprahGudangobatTarik::find()->where(['idamprah'=>$model->id])->andwhere(['status'=>1])->all();
		$tgl = date('Y-m-d H:i:s');
		foreach($detail_tarik as $dt){
			$dt->status = 2;
			$detail = AmprahGudangobatDetail::find()->where(['id'=>$dt->iddetail])->one();
			$detail->jumlah_diserahkan = $detail->jumlah_diserahkan - $dt->jumlah;
			
			$obat_bacth = Obat::find()->where(['id'=>$dt->idobat])->one();
			//$obat_bacth_gudang = ObatBacth::find()->where(['idobat'=>$dt->idobat])->all();
			Yii::$app->kazo->kartuStok($dt->idobat,0,1,$dt->jumlah,2);	
			Yii::$app->kazo->mutasiStok($dt->idobat,0,2,4,$dt->jumlah,$dt->id,$obat_bacth->stok_gudang,1,'Retur Gudang');
			$obat_bacth->stok_gudang = $obat_bacth->stok_gudang + $dt->jumlah;
			// $ob = 0;
			// foreach($obat_bacth_gudang as $obg){
				// $ob += $obg->stok_gudang;
			// }
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$dt->idobat,$dt->jumlah,$tgl,2);
			$obat_bacth->save(false);
			$dt->save();
			$detail->save();
		}
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionTarik($id){
		$model = AmprahGudangobat::findOne($id);
		$detail = AmprahGudangobatDetail::find()->where(['idamprah'=>$model->id])->all();
		$detail_tarik = AmprahGudangobatTarik::find()->where(['idamprah'=>$model->id])->all();
		$tarik = new AmprahGudangobatTarik();
		if($model->idpeminta == 13){
			Yii::$app->session->setFlash('warning', "Mohon maaf barang dari farmasi belum bisa di tarik");
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
		if ($tarik->load(Yii::$app->request->post()) ) {
			$tarik_detail = AmprahGudangobatTarik::find()->where(['idbacth'=>$tarik->idbacth])->andwhere(['idamprah'=>$model->id])->andwhere(['status'=>1])->all();
			if(count($tarik_detail) > 0){
				Yii::$app->session->setFlash('warning', "Data sudah ada");
				return $this->refresh();
			}
			$tarik->tgl = date('Y-m-d',strtotime('+7 hours'));
			$tarik->status = 1;
			if($tarik->save()){
				return $this->refresh();
			}
		}
		return $this->render('tarik',[
			'model'=>$model,
			'detail'=>$detail,
			'tarik'=>$tarik,
			'detail_tarik'=>$detail_tarik,
		]);
	}
	public function actionBatalkan($id){
		$model = $this->findModel($id);
		$detail = AmprahGudangobatDetail::find()->where(['idamprah'=>$model->id])->all();
		foreach($detail as $d){
			$d->delete();
		}
		$model->delete();
		return $this->redirect(['index']);
	}
	public function actionHapus($id){
		$model = AmprahGudangobatDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
    public function actionSerahkan($id){
		$model = $this->findModel($id);
		$detail = AmprahGudangobatDetail::find()->where(['idamprah'=>$model->id])->all();
		$tgl = date('Y-m-d');
		foreach($detail as $d){
			$d->status = 1;
			$obat = Obat::findOne($d->idobat);
			Yii::$app->kazo->kartuStok($d->idobat,0,1,$d->jumlah_diserahkan,1);	
			Yii::$app->kazo->mutasiStok($d->idobat,0,1,5,$d->jumlah_diserahkan,$d->id,$obat->stok_gudang,1,'Amprah Gudang Ke '.$model->ruangan->ruangan.'');
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->jumlah_diserahkan,$tgl,1);
			if($model->gudang == 1){
				Yii::$app->kazo->kartuStok($d->idobat,0,2,$d->jumlah_diserahkan,2);	
				Yii::$app->kazo->mutasiStok($d->idobat,0,2,6,$d->jumlah_diserahkan,$d->id,$obat->stok_apotek,2,'Amprah Dari Gudang Ke Apotek');
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->jumlah_diserahkan,$tgl,1);
				$obat->stok_apotek = $obat->stok_apotek + $d->jumlah_diserahkan;
			}
			$obat->stok_gudang = $obat->stok_gudang - $d->jumlah_diserahkan;
			$obat->save();
			$d->save();
		}
		$model->status = 2;
		$model->save();
		return $this->redirect(['index']);
	}
    public function actionView($id)
    {
		$searchModel = new ObatSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$barang = Obat::find()->where(['>','stok_gudang',0])->all();
		$amprah = AmprahGudangobatDetail::find()->where(['idamprah'=>$id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'barang' => $barang,
            'amprah' => $amprah,
        ]);
    }

    /**
     * Creates a new AmprahGudangobat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AmprahGudangobat();

        if ($model->load(Yii::$app->request->post()) ) {
			$model->genKode();
			$model->idasal = 10;
			$model->tgl_penyerahan = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			if($model->idpeminta == 13){
				$model->gudang = 1;
			}else{
				$model->gudang = 0;
			}
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
     * Updates an existing AmprahGudangobat model.
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
     * Deletes an existing AmprahGudangobat model.
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
	public function actionBarangKeluar($id,$jumlah,$idso){
		$model = new AmprahGudangobatDetail();
		$barang = Obat::findOne($id);
		$model->idamprah = $idso;
		$model->idobat = $barang->id;
		$model->jumlah_diserahkan = $jumlah;
		$model->status = 0;
		if($barang->stok_gudang < $jumlah){
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

    /**
     * Finds the AmprahGudangobat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AmprahGudangobat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AmprahGudangobat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
