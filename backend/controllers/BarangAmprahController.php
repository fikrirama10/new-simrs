<?php

namespace backend\controllers;

use Yii;
use common\models\DataBarang;
use common\models\DataBarangSearch;
use common\models\BarangAmprah;
use common\models\BarangAmprahDetail;
use common\models\BarangAmprahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * BarangAmprahController implements the CRUD actions for BarangAmprah model.
 */
class BarangAmprahController extends Controller
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
     * Lists all BarangAmprah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BarangAmprahSearch();
		$where = ['unit_peminta'=>Yii::$app->user->identity->userdetail->idunit];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionEditAmprah($id){
		$model = $this->findModel($id);
		$model->status = 1;
		$model->total = 0;
		$model->save(false);
		return $this->redirect(Yii::$app->request->referrer);		
	}
	public function actionPrintSetuju($id) {
		$model = BarangAmprah::find()->where(['id' =>$id ])->one();
		$model_list = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->andwhere(['status'=>2])->all();
		$content = $this->renderPartial('form-setuju',['model' => $model,'model_list'=>$model_list]);
	  
	  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'format' => Pdf::FORMAT_LEGAL, 
		   'content' => $content,  
		   
		   'cssFile' => '@frontend/web/css/paper.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],

			
		]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}
	public function actionPrintRekap($start,$end) {
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/barang-amprah?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('rekap',['model' => $json,'start'=>$start]);
	  
	  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'format' => Pdf::FORMAT_LEGAL, 
		   'format' => Pdf::FORMAT_A4, 
		   'orientation' => Pdf::ORIENT_LANDSCAPE,
		   'marginTop' => '10',
		   'marginLeft' => '20',
		   'marginRight' => '0',
		   'marginBottom' => '20',
		   'content' => $content,  
		   
		   'cssFile' => '@frontend/web/css/paperbarang.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],

			
		]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}
	public function actionPrintPermintaan($id) {
	  //tampilkan bukti proses
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$model = BarangAmprah::find()->where(['id' =>$id ])->one();
		$model_list = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		$content = $this->renderPartial('form-permintaan',['model' => $model,'model_list'=>$model_list]);
	  
	  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'format' => Pdf::FORMAT_LEGAL, 
		   'content' => $content,  
		   
		   'cssFile' => '@frontend/web/css/paper.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],

			
		]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}

    /**
     * Displays a single BarangAmprah model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionEditHarga($id,$jumlah){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->harga = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				$detail->total_setuju = $detail->harga * $detail->qty_setuju;
				$detail->total= $detail->harga * $detail->qty;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionEditSetuju($id,$jumlah){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->qty_setuju = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				$detail->total_setuju = $detail->harga * $jumlah;
				$detail->status = 2;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionEditTolak($id){
		$pl = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		if($pl->load($this->request->post())){
			$pl->status = 6;
			$pl->qty_setuju = 0;
			if($pl->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
    public function actionPersetujuanJangkes($id){
		$model = BarangAmprah::findOne($id);
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		return $this->render('persetujuan-jangkes',[
			'model'=>$model,
			'detail'=>$detail,
		]);
	}
	public function actionSelesaiPengajuan($id){
		$model = $this->findModel($id);
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		$total = 0;
		foreach($detail as $d){
			$total += $d->total_setuju;
		}
		$model->status = 3;
		$model->total_setuju = $total;
		if($model->save()){
			Yii::$app->session->setFlash('success', 'Pengajuan permintaan obat telah di ajukan ke bagian Pengadaan');
			return $this->redirect(['/barang-amprah/list-permintaan']);
		}
	}
    public function actionView($id)
    {
		$model = $this->findModel($id);
		if($model->unit_peminta != Yii::$app->user->identity->userdetail->idunit){
			if(Yii::$app->user->identity->userdetail->idunit == 16){
				return $this->redirect(['/barang-amprah/persetujuan-jangkes?id='.$id]);
			}else{
				return $this->redirect(['index']);
			}
			
		}else if(Yii::$app->user->identity->userdetail->idunit == 16 && $model->status > 1){
		    	return $this->redirect(['/barang-amprah/persetujuan-jangkes?id='.$id]);
		}
		$barang = DataBarang::find()->all();
		$searchModel = new DataBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$permintaan = new BarangAmprahDetail();
		$permintaan_list = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		if ($permintaan->load(Yii::$app->request->post())) {

		    if($permintaan->idbarang == null){
				$permintaan->baru = 1;
		    }
			$data_permintaan = BarangAmprahDetail::find()->where(['idbarang'=>$permintaan->idbarang])->andwhere(['idamprah'=>$model->id])->one();
			if($data_permintaan){
				$data_permintaan->delete();
			}
		
			$permintaan->idamprah = $model->id;
			$permintaan->status = 1;
			$permintaan->total = $permintaan->harga  * $permintaan->qty;
			if($permintaan->save()){
				return $this->refresh();
			}
		}
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'permintaan' => $permintaan,
            'permintaan_list' => $permintaan_list,
            'barang' => $barang,
        ]);
    }
	public function actionListPermintaan()
    {
        $searchModel = new BarangAmprahSearch();
		$where = ['status'=>2];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    	public function actionListPermintaanSetuju()
    {
        $searchModel = new BarangAmprahSearch();
		$where = ['status'=>3];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionGetBarang()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = DataBarang::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionAjukan($id){
		$model = BarangAmprah::findOne($id);
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		$total = 0;
		foreach($detail as $d){
			$total += $d->total;
		}
		$model->total = $total;
		$model->status = 2;
		if($model->save()){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionHapusItem($id){
		$model = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionBatalkan($id){
		$model = BarangAmprah::find()->where(['id'=>$id])->one();
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		foreach($detail as $d){
		    $d->delete();
		}
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	
    /**
     * Creates a new BarangAmprah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BarangAmprah();
		
        if ($model->load(Yii::$app->request->post())) {
			$model->unit_peminta = Yii::$app->user->identity->userdetail->idunit;
			$model->idruangan = Yii::$app->user->identity->userdetail->idruangan;
			$model->iduser = Yii::$app->user->identity->id;
			$model->status = 1;
			$model->genKode();
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BarangAmprah model.
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
     * Deletes an existing BarangAmprah model.
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
     * Finds the BarangAmprah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BarangAmprah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BarangAmprah::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
