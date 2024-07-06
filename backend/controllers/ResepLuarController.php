<?php

namespace backend\controllers;

use Yii;
use common\models\ObatFarmasi;
use common\models\ObatBacth;
use common\models\ObatFarmasiDetail;
use common\models\ObatFarmasiSearch;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ResepLuarController implements the CRUD actions for ObatFarmasi model.
 */
class ResepLuarController extends Controller
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
     * Lists all ObatFarmasi models.
     * @return mixed
     */
	public function actionBatalkan($id){
		$model = ObatFarmasiDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
		public function actionFaktur($id) {
		//tampilkan bukti proses
		$model = ObatFarmasi::find()->where(['id' => $id])->one();
		$resep = ObatFarmasiDetail::find()->where(['idresep'=>$model->id])->all();
		$content = $this->renderPartial('faktur',['model' => $model ,'resep'=>$resep]);

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'orientation' => Pdf::ORIENT_PORTRAIT, 
		'format' => [148,210],
		'marginTop' => '5',
		'marginRight' => '2',
		'marginLeft' => '2',
		'marginBottom' => '5',
		'content' => $content,  
		'cssFile' => '@frontend/web/css/etiket.css',
		//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
    public function actionIndex()
    {
        $searchModel = new ObatFarmasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObatFarmasi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$detail = ObatFarmasiDetail::find()->where(['idresep'=>$model->id])->all();
		$resep_detail = new ObatFarmasiDetail();
		if($resep_detail->load(Yii::$app->request->post())){
			$obat = ObatBacth::findOne($resep_detail->idbacth);
			$resep_detail->total = $resep_detail->harga * $resep_detail->jumlah + 3000;
			$resep_detail->tuslah = 3000;
			$resep_detail->status = 1;
			$resep_detail->keuntungan = ($resep_detail->harga - $obat->harga_beli) * $resep_detail->jumlah ;
			if($resep_detail->save(false)){
				return $this->refresh();
			}
		}
        return $this->render('view', [
            'model' => $this->findModel($id),
            'detail' => $detail,
            'resep_detail' => $resep_detail,
        ]);
    }
	public function actionSelesai($id){
		$model = ObatFarmasi::findOne($id);
		$detail = ObatFarmasiDetail::find()->where(['idresep'=>$model->id])->all();
		$total = 0;
		$totalu = 0;
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		foreach($detail as $d){
			$obat = ObatBacth::findOne($d->idbacth);
			Yii::$app->kazo->kartuStok($d->idobat,$d->idbacth,2,$d->jumlah,1);	
			Yii::$app->kazo->mutasiStok($d->idobat,$d->idbacth,1,1,$d->jumlah,$d->id,$obat->stok_apotek,2);
			$obat->stok_apotek = $obat->stok_apotek - $d->jumlah;
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->jumlah,$tgl,1);
			$obat->save(false);
			$total += $d->total;
			$totalu += $d->keuntungan;
		}

		$model->total_harga = $total;
		$model->keuntungan = $totalu;
		$model->status = 2;
			
		if($model->save(false)){
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}

    /**
     * Creates a new ObatFarmasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObatFarmasi();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			if($model->obat_racik == 1){
				$model->jasa_racik = 10000;
			}else{
				$model->jasa_racik = 0;
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
     * Updates an existing ObatFarmasi model.
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
     * Deletes an existing ObatFarmasi model.
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
     * Finds the ObatFarmasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObatFarmasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObatFarmasi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
