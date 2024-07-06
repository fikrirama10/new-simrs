<?php

namespace backend\controllers;

use Yii;
use common\models\ObatBacth;
use common\models\ObatBacthSearch;
use common\models\AmprahGudangobatDetail;
use common\models\AmprahGudangobat;
use common\models\AmprahGudangobatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
			$obat = ObatBacth::findOne($d->idbacth);
			Yii::$app->kazo->kartuStok($d->idobat,$d->idbacth,1,$d->jumlah_diserahkan,2);	
			Yii::$app->kazo->mutasiStok($d->idobat,$d->idbacth,1,5,$d->jumlah_diserahkan,$d->id,$obat->stok_gudang,1,'Amprah Gudang Ke '.$model->ruangan->ruangan.'');
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->jumlah_diserahkan,$tgl,1);
			if($model->gudang == 1){
				Yii::$app->kazo->kartuStok($d->idobat,$d->idbacth,2,$d->jumlah_diserahkan,1);	
				Yii::$app->kazo->mutasiStok($d->idobat,$d->idbacth,2,6,$d->jumlah_diserahkan,$d->id,$obat->stok_apotek,2,'Amprah Dari Gudang Ke Apotek');
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
		$searchModel = new ObatBacthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$barang = ObatBacth::find()->where(['>','stok_gudang',0])->all();
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
		$barang = ObatBacth::findOne($id);
		$model->idamprah = $idso;
		$model->idbacth = $id;
		$model->idobat = $barang->idobat;
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
