<?php

namespace backend\controllers;

use Yii;
use common\models\Obat;
use common\models\ObatKartustok;
use common\models\ObatBacth;
use common\models\ObatMutasi;
use common\models\GudangInventori;
use common\models\ObatSeacrh;
use common\models\PermintaanObat;
use common\models\BarangAmprahSearch;
use common\models\PenerimaanBarangSearch;
use common\models\PenerimaanBarangDetailSearch;
use common\models\BarangPenerimaan;
use common\models\BarangPenerimaanDetail;
use common\models\BarangPenerimaanSearch;
use common\models\BarangPenerimaanDetailSearch;
use common\models\PermintaanObatdetail;
use common\models\PenerimaanBarang;
use common\models\PenerimaanBarangDetail;
use common\models\PermintaanObatRequest;
use common\models\PermintaanObatRequestSearch;
use common\models\PermintaanObatdetailSearch;
use common\models\PermintaanObatSearch;
use common\models\BarangAmprah;
use common\models\DataBarang;
use common\models\BarangAmprahDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ObatController implements the CRUD actions for Obat model.
 */
class GudangController extends Controller
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
     * Lists all Obat models.
     * @return mixed
     */
    public function actionRekapGudangobat(){
		return $this->render('rekap-obat');
	}
	public function actionShowRekapObat($start,$end){
		return $this->renderAjax('show-rekap-obat',[
			'start'=>$start,
			'end'=>$end,
		]);
	}
	public function actionRekapGudangatk(){
		return $this->render('rekap-atk');
	}
	public function actionShowRekapAtk($start,$end){
		return $this->renderAjax('show-rekap-atk',[
			'start'=>$start,
			'end'=>$end,
		]);
	}
	
	
		public function actionRekapObatRuangan($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-obat-ruangan?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('obat-rekap-ruangan',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	public function actionRekapObatTanggal($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-obat-tanggal?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('obat-rekap-tanggal',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	
	public function actionRekapAtkRuangan($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-atk-ruangan?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('atk-rekap-ruangan',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	public function actionRekapAtkTanggal($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-atk-tanggal?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('atk-rekap-tanggal',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	public function actionRekapAtk($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-atk?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('atk-rekap',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	
	
	public function actionRekapObat($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-pjk/amprah-obat?awal='.$start.'&akhir='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		
		$content = $this->renderPartial('obat-rekap',['model'=>$json,'end'=>$end]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL, 
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paper-si.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [ 
			]	   
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
    public function actionMasukStokAtk($id){
		$model = BarangPenerimaan::findOne($id);
		$tgl = date('Y-m-d H:i:s');
		$penerimaan = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		foreach($penerimaan as $pr){
			if($pr->diterima != 1){
				$pr->diterima = 1;
				$pr->qty_diterima = $pr->qty;
			}
			$barang = DataBarang::find()->where(['id'=>$pr->idbarang])->one();
			Yii::$app->kazo->mutasiamprah($pr->idbarang,2,2,$barang->stok,$pr->qty_diterima,0,10,'Barang Masuk Pembelian');
			$barang->stok = $barang->stok + $pr->qty_diterima;
			$barang->save(false);
			$pr->save();
		}
		$model->status = 2;
		$model->save(false);
		Yii::$app->session->setFlash('success', 'Penerimaan Obat Selesai');
		return $this->redirect(['/gudang/barang-terima-atk']);	
	}
	public function actionMasukStok($id){
		$model = PenerimaanBarang::findOne($id);
		$tgl = date('Y-m-d H:i:s');
		$penerimaan = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		foreach($penerimaan as $pr){
			if($pr->diterima != 1){
				$pr->diterima = 1;
				$pr->jumlah_diterima = $pr->jumlah;
			}
			$obat_bacth = Obat::find()->where(['id'=>$pr->idbarang])->one();
			Yii::$app->kazo->kartuStok($pr->idbarang,0,1,$pr->jumlah_diterima,2);	
			Yii::$app->kazo->mutasiStok($pr->idbarang,0,2,2,$pr->jumlah_diterima,$pr->id,$obat_bacth->stok_gudang,1);
			$obat_bacth->stok_gudang = $obat_bacth->stok_gudang + $pr->jumlah_diterima;
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$pr->idbarang,$pr->jumlah_diterima,$tgl,2);
			$obat_bacth->save(false);
			$pr->save();
		}
		$model->status = 2;
		$model->save(false);
		Yii::$app->session->setFlash('success', 'Penerimaan Obat Selesai');
		return $this->redirect(['/gudang/barang-terima']);	
	}
	public function actionViewPenerimaan($id){
		$model = PenerimaanBarang::findOne($id);
		$where = ['idpenerimaan'=>$model->id];
		$searchModel = new PenerimaanBarangDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$penerimaan = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		return $this->render('view',[
			'model'=>$model,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
			'penerimaan'=>$penerimaan,
		]);
	}
	public function actionViewAtk($id){
		$model = BarangPenerimaan::findOne($id);
		$where = ['idpenerimaan'=>$model->id];
		$searchModel = new BarangPenerimaanDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		$penerimaan = BarangPenerimaanDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		return $this->render('view-atk',[
			'model'=>$model,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
			'penerimaan'=>$penerimaan,
		]);
	}
	public function actionTerimaBarang($id,$jumlah){
		$detail = PenerimaanBarangDetail::find()->where(['id'=>$id])->one();
		$detail->jumlah_diterima = $jumlah;
		$detail->diterima = 1;
			if($jumlah < 0){
				$model = 404;
			}else{
				//$detail->total_setuju = $detail->harga * $jumlah;
				//$detail->status = 2;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionTerimaAtk($id,$jumlah){
		$detail = BarangPenerimaanDetail::find()->where(['id'=>$id])->one();
		$detail->qty_diterima = $jumlah;
		$detail->diterima = 1;
		$detail->total_diterima = $detail->harga * $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				//$detail->total_setuju = $detail->harga * $jumlah;
				//$detail->status = 2;
				if($detail->save(false)){	
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionBarangTerimaAtk(){
		$where = ['status'=>1];
		$searchModel = new BarangPenerimaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		
		return $this->render('terima-barang-atk', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	public function actionBarangTerimaAtkSelesai(){
		$where = ['status'=>1];
		$searchModel = new BarangPenerimaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		
		return $this->render('terima-barang-atk', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
    public function actionBarangTerima(){
		$where = ['status'=>1];
		$searchModel = new PenerimaanBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		
		return $this->render('terima-barang', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	public function actionBarangTerimaSelesai(){
		$where = ['status'=>2];
		$searchModel = new PenerimaanBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		
		return $this->render('terima-barang', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
    public function actionPenerimaanBarang()
    {
        $searchModel = new ObatSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionListAmprah(){
		 $searchModel = new BarangAmprahSearch();
		 $where = ['status'=>4];
		 $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		 return $this->render('list-amrah',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		 ]);
	}
	public function actionViewAmprah($id){
		$model = BarangAmprah::findOne($id);
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$model->id])->all();
		return $this->render('view-amprah',[
			'model'=>$model,
			'detail'=>$detail,
		]);
	}
		public function actionAmprahBerikan($id){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$barang = DataBarang::findOne($detail->idbarang);
		if($barang->stok < $detail->qty_setuju){
			Yii::$app->session->setFlash('danger', 'stok kurang');
			return $this->redirect(Yii::$app->request->referrer);
		}
		$detail->status = 4;
		if($detail->save()){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionAmprahKoreksi($id){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->status = 3;
		if($detail->save()){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionAmprahSelesai($id){
		$model = BarangAmprah::find()->where(['id'=>$id])->one();
		$detail = BarangAmprahDetail::find()->where(['idamprah'=>$id])->andwhere(['status'=>4])->all();
		$model->status = 5;
		foreach($detail as $d){
			$barang = DataBarang::findOne($d->idbarang);
			if($barang->stok < $d->qty_setuju){
				Yii::$app->session->setFlash('danger', 'stok kurang');
				return $this->redirect(Yii::$app->request->referrer);
			}else{
				Yii::$app->kazo->mutasiamprah($d->idbarang,1,5,$barang->stok,$d->qty_setuju,12,Yii::$app->user->identity->userdetail->idunit);
				$barang->stok = $barang->stok - $d->qty_setuju;
				$barang->save(false);
			}
			
		}
		if($model->save(false)){
			Yii::$app->session->setFlash('success', 'Pengajuan Di Selesai');
			return $this->redirect(['gudang/list-amprah']);
		}
	}
	public function actionEditItemAmprah($id,$jumlah){
		$detail = BarangAmprahDetail::find()->where(['id'=>$id])->one();
		$detail->qty_setuju = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				if($detail->save(false)){	
					if($detail->qty_setuju == 0){
						$detail->save();
					}
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
    /**
     * Displays a single Obat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$obat = new ObatBacth();
		$model = $this->findModel($id);
		 if ($obat->load(Yii::$app->request->post()) && $obat->save()) {
            return $this->refresh();
        }
        return $this->render('view', [
            'model' => $model,
            'obat' => $obat,
        ]);
    }

    /**
     * Creates a new Obat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Obat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Obat model.
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
     * Deletes an existing Obat model.
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
     * Finds the Obat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Obat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Obat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
