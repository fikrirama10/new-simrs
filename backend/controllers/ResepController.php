<?php

namespace backend\controllers;

use Yii;
use common\models\ObatTransaksi;
use common\models\TransaksiSearch;
use common\models\SoapRajalobat;
use kartik\mpdf\Pdf;
use common\models\Pasien;
use common\models\ObatTransaksiSearch;
use common\models\Transaksi;
use common\models\Rawat;
use common\models\TindakanTarif;
use common\models\ObatBacth;
use common\models\ObatTransaksiRetur;
use common\models\TransaksiDetail;
use common\models\ObatTransaksiDetail;
use common\models\RawatResep;
use common\models\RawatResepDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ResepController implements the CRUD actions for ObatTransaksi model.
 */
class ResepController extends Controller
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
     * Lists all ObatTransaksi models.
     * @return mixed
     */
	function milliseconds() {
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
	public function actionDataResep()
    {
        $searchModel = new ObatTransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data-resep', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndex()
    {
		//$where = ['status'=>1];
		$orderBy = ['tgltransaksi'=>SORT_DESC];
		$searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionPenerimaanResep(){
		$tgl = date('Y-m',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$tgli = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$url = Yii::$app->params['baseUrl'].'dashboard/rest/transaksi-apotek?start='.$tgl.'-01&end='.$tgli;;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('penerimaan-resep',[
			'json'=>$json
		]);
	}
	public function actionShow($id){
		$model = Transaksi::find()->where(['no_rm'=>$id])->all();		
		return $this->renderAjax('show',[
			'model'=>$model,
		]);
	}
	public function actionShowBatch($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->andwhere(['>','stok_apotek',0])->all();
		$resep_obat = new ObatTransaksiDetail();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
			'resep_obat'=>$resep_obat,
		]);
	}
	public function actionShowResep($id){
		$model = RawatResep::findOne($id);
		$list = RawatResepDetail::find()->where(['idresep'=>$model->id])->all();
		return $this->renderAjax('show-resep',[
			'list'=>$list,
		]);
	}
	
	public function actionListResep(){
		$searchModel = new ObatTransaksiSearch();
		$where = ['status'=>2];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->render('list-resep',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}
    /**
     * Displays a single ObatTransaksi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBatalkan($id){
		$model = ObatTransaksiDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionSelesai($id){
		$model = ObatTransaksi::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$resep = RawatResep::findOne($model->idresep);
		$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		$detail_umum = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idbayar'=>1])->all();
		$total = 0;
		$totalu = 0;
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		foreach($detail as $d){
			$obat = ObatBacth::findOne($d->idbatch);
			Yii::$app->kazo->kartuStok($d->idobat,$d->idbatch,2,$d->qty,1);	
			Yii::$app->kazo->mutasiStok($d->idobat,$d->idbatch,1,1,$d->qty,$d->id,$obat->stok_apotek,2);
			$obat->stok_apotek = $obat->stok_apotek - $d->qty;
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->qty,$tgl,1);
			$obat->save(false);
			$total += round($d->total);
		}
		if(count($detail_umum) > 0){
			foreach($detail_umum as $du){
				$totalu += round($du->total);
			}
		}
		$model->total_harga = $total;
		$model->total_bayar = $totalu;
		$model->iduser = Yii::$app->user->identity->id;
		$model->status = 2;
			
		if($model->save(false)){
			if($rawat->taksid == 6){
				$taks = array(
					"kodebooking"=> $rawat->idrawat,
					"taskid"=> 7,
					"waktu"=>  $this->milliseconds(),
				);
				$taksid = Yii::$app->hfis->update_taks($taks);
				if($taksid['metadata']['code'] == 200){
					$rawat->taksid = 7;
					$rawat->save(false);
					
				}else{
					Yii::$app->session->setFlash('success', $taksid['metadata']['message']); 
					 return $this->redirect(Yii::$app->request->referrer);
				}
				Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 7"); 
			}
			if($resep){
				$resep->status = 2;
				$resep->save(false);
			}	
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}
	public function actionBatalkanResep($id){
		$model = ObatTransaksi::findOne($id);
		$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		if(count($detail) > 0){
			foreach($detail as $d):
				$d->delete();
			endforeach;
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}else{
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}
    public function actionAddResep($id,$idrawat){
		$resep = new ObatTransaksi();
		$resep_detail = new ObatTransaksiDetail();
		$model = Transaksi::find()->where(['id'=>$id])->one();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$obat = SoapRajalobat::find()->where(['idrawat'=>$idrawat])->andwhere(['status'=>1])->all();
		$rawat = Rawat::findOne($idrawat);
		
		$tranobat = ObatTransaksi::find()->where(['idrawat'=>$idrawat])->all();
		if($resep->load(Yii::$app->request->post())){
			$hitung_resep = ObatTransaksi::find()->where(['idrawat'=>$idrawat])->andwhere(['status'=>1])->count();
			if($hitung_resep > 0){
				Yii::$app->session->setFlash('warning', 'Silahkan selesaikan resep ');
				return $this->refresh(); 
			}
			$resep->genKode();
			$resep->jam = date('H:i:s',strtotime('+6 hour',strtotime(date('H:i:s'))));
			$resep->idjenis = 1;
			$resep->idjenisrawat = $rawat->idjenisrawat;
			$resep->no_rm = $rawat->no_rm;
			$resep->idrawat = $rawat->id;
			$resep->idtrx = $id;
			$resep->status = 1;
			if($resep->obat_racikan == 1){
				$resep->jasa_racik = 10000 * $resep->jumlahracik;
			}else{
				$resep->jasa_racik = 0;
			}
			if($resep->save(false)){
				return $this->refresh();
			}
			
		}else if($resep_detail->load(Yii::$app->request->post())){
			$idsoap = Yii::$app->request->post('idsoap');
			// return $idsoap;
			$soap = RawatResepDetail::findOne($idsoap);
			$transaksi = ObatTransaksi::find()->where(['idrawat'=>$idrawat])->andwhere(['status'=>1])->one();
			$resep_detail->idtrx = $transaksi->id;
			$resep_detail->idtransaksi = $id;
			$bacth = ObatBacth::findOne($resep_detail->idbatch);
			if($bacth->stok_apotek < $resep_detail->qty){
			    Yii::$app->session->setFlash('warning', 'Jumlah Stok tidak cukup');
			    return $this->refresh();
			}
			if($resep_detail->idbayar == 1){
				$obat = ObatBacth::findOne($resep_detail->idbatch);
				$resep_detail->total = round($resep_detail->harga * $resep_detail->qty + 3000);
				$resep_detail->tuslah = 3000;
				$resep_detail->keuntungan = round(($resep_detail->harga - $obat->harga_beli) * $resep_detail->qty) ;
			}else{
				$resep_detail->total = round($resep_detail->harga * $resep_detail->qty);
			}
			
			
			if($resep_detail->save(false)){
				return $this->refresh();
			}
		}
		if($rawat->taksid == 5){
			$taks = array(
				"kodebooking"=> $rawat->idrawat,
				"taskid"=> 6,
				"waktu"=>  $this->milliseconds(),
			);
			$taksid = Yii::$app->hfis->update_taks($taks);
			if($taksid['metadata']['code'] == 200){
				$rawat->taksid = 6;
				$rawat->save(false);
				
			}else{
				Yii::$app->session->setFlash('success', $taksid['metadata']['message']); 
				 return $this->redirect(Yii::$app->request->referrer);
			}
			Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 6"); 
		}

		return $this->render('add-resep',[
			'model'=>$model,
			'pasien'=>$pasien,
			'rawat'=>$rawat,
			'obat'=>$obat,
			'resep_detail'=>$resep_detail,
			'resep'=>$resep,
			'tranobat'=>$tranobat,
		]);
	}
	public function actionRetur($id){
		$model = ObatTransaksiDetail::findOne($id);
		$trx = ObatTransaksi::findOne($model->idtrx);
		$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$model->idtrx])->all();
		$retur = new ObatTransaksiRetur();
		$obat = ObatBacth::findOne($model->idbatch);
		$tgl = date('Y-m-d H:i:s');
		if($retur->load(Yii::$app->request->post())){
			if($retur->jumlah > $model->qty){
				Yii::$app->session->setFlash('warning', 'Jumlah Retur Lebih dari jumlah barang');
				return $this->refresh(); 
			}else if($retur->jumlah < 1){
				Yii::$app->session->setFlash('warning', 'Jumlah Retur Kurang dari 1');
				return $this->refresh(); 
			}
			$model->qty = $model->qty - $retur->jumlah;
			$obat = ObatBacth::findOne($model->idbatch);
			if($model->idbayar == 1){
				if($model->qty == 0){
					$model->total = 0;
					$model->tuslah = 0;
					$model->keuntungan = 0;
				}else{
					$model->total = $model->harga * $model->qty + 3000;
					$model->tuslah = 3000;
					$model->keuntungan = round(($model->harga - $obat->harga_beli) * $model->qty) ;
				}				
			}else{
				$model->total = round($model->harga * $model->qty);
			}
			if($model->save()){
				$obat->stok_apotek = $obat->stok_apotek + $retur->jumlah;
				Yii::$app->kazo->kartuStok($model->idobat,$model->idbatch,2,$retur->jumlah,2);	
				Yii::$app->kazo->mutasiStok($model->idobat,$model->idbatch,2,4,$retur->jumlah,$trx->idtrx,$obat->stok_apotek,2);
				
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$model->idobat,$retur->jumlah,$tgl,2);
				$total = 0;
				foreach($detail as $d){
					$total += round($d->total);
				}
				$trx->total_harga = $total;
				$trx->total_bayar = $total;
				$trx->save();
				$retur->save();
				$obat->save();
				return $this->redirect(['add-resep?id='.$trx->idtrx.'&idrawat='.$trx->idrawat]);
			}
			
			
		}
		
		
		return $this->render('retur',[
			'model'=>$model,
			'retur'=>$retur,
			'trx'=>$trx,
		]);
	}
	public function actionEditItem($id,$jumlah){
		$detail = ObatTransaksiDetail::find()->where(['id'=>$id])->one();
		$detail->qty = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				$obat = ObatBacth::findOne($detail->idbatch);
				if($obat->stok_apotek < $jumlah){
					$model = 400;
				}else{
					$detail->total = $detail->harga * $jumlah;
					if($detail->save(false)){	
						if($detail->qty == 0){
							$detail->delete();
						}
						$model = "Sukses";
					}
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
    public function actionView($id)
    {
		$model = Transaksi::find()->where(['id'=>$id])->one();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$rawat = Rawat::find()->where(['idkunjungan'=>$model->kode_kunjungan])->andwhere(['<>','status',5])->all();
		$resep = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
        return $this->render('view', [
            'model' => $model,
            'rawat' => $rawat,
            'pasien' => $pasien,
            'resep' => $resep,
        ]);
    }
	
	public function actionViewResep($id)
    {
		$model = ObatTransaksi::find()->where(['id'=>$id])->one();
		$transaksi = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
        return $this->render('view-resep', [
            'model' => $model,
            'transaksi' => $transaksi,
            'pasien' => $pasien,
        ]);
    }

    /**
     * Creates a new ObatTransaksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObatTransaksi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ObatTransaksi model.
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
	public function actionEtiket($id) {
		//tampilkan bukti proses
		$model = ObatTransaksi::find()->where(['id' => $id])->one();
		$resep_kronis = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idbayar'=>3])->all();
		$resep = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		
		$content = $this->renderPartial('etiket',['model' => $model ,'resep'=>$resep,'resep_kronis'=>$resep_kronis]);

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'format' => [60,38],
		'marginTop' => '0',
		'orientation' => Pdf::ORIENT_PORTRAIT, 
		'marginLeft' => '1',
		'marginRight' => '1',
		'marginBottom' => '2',
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
	public function actionEtiketRanap($id) {
		//tampilkan bukti proses
		$model = ObatTransaksi::find()->where(['id' => $id])->one();
		$resep_kronis = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idbayar'=>3])->all();
		$resep = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		
		$content = $this->renderPartial('etiket-ranap',['model' => $model ,'resep'=>$resep,'resep_kronis'=>$resep_kronis]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'format' => [120,80],
		'marginTop' => '2',
		'orientation' => Pdf::ORIENT_PORTRAIT, 
		'marginLeft' => '1',
		'marginRight' => '1',
		'marginBottom' => '2',
		'content' => $content,  
		'cssFile' => '@frontend/web/css/etiket-ranap.css',
		//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	public function actionFaktur($id) {
		//tampilkan bukti proses
		$model = ObatTransaksi::find()->where(['id' => $id])->one();
		$resep_kronis = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idbayar'=>3])->all();
		$resep = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['<>','idbayar',3])->all();
		$content = $this->renderPartial('faktur',['model' => $model ,'resep'=>$resep,'resep_kronis'=>$resep_kronis]);

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'orientation' => Pdf::ORIENT_PORTRAIT, 
		'format' => [114,279],
		'marginTop' => '2',
		'marginRight' => '5',
		'marginLeft' => '5',
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
    /**
     * Deletes an existing ObatTransaksi model.
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
     * Finds the ObatTransaksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObatTransaksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObatTransaksi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
