<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\models\TransaksiDetailBill;
use common\models\ObatTransaksi;
use common\models\Tarif;
use common\models\TransaksiDetailRinci;
use common\models\ObatTransaksiDetail;
use common\models\Rawat;
use common\models\Transaksi;
use common\models\TransaksiSearch;
use common\models\TindakanTarif;
use common\models\TransaksiDetail;
use common\models\RawatKunjungan;
use common\models\Pasien;
use kartik\mpdf\Pdf;
class BillingController extends Controller
{

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
	public function actionFaktur($id) {

	$model = Transaksi::find()->where(['id'=>$id])->one();
	$url_bpjs = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-bill-bayar?id='.$model->id.'&idbayar=2';
	$content_bpjs = file_get_contents($url_bpjs);
	$json_bpjs = json_decode($content_bpjs, true);
	
	$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-bill-bayar?id='.$model->id.'&idbayar=1';
	$content = file_get_contents($url);
	$json = json_decode($content, true);
	$model_detail = TransaksiDetailBill::find()->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>2])->all();
	$model_detail_umum = TransaksiDetailBill::find()->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>1])->all();
	$obat_detail_umum = ObatTransaksiDetail::find()->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>1])->all();
	$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
	$content = $this->renderPartial('print-faktur',['model' => $model,'pasien'=>$pasien,'model_detail'=>$json_bpjs,'model_detail_umum'=>$json,'obat_detail_umum'=>$obat_detail_umum]);
	  
	  // setup kartik\mpdf\Pdf component
	$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
	'format' => ['110','210'], 
		'marginTop'=>2,
		'marginLeft'=>4,
		'marginRight'=>4,
		'content' => $content,  
		'cssFile' => '@frontend/web/css/paper-faktur.css',
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
	public function actionEditFaktur($id){
		$model = Transaksi::findOne($id);
		$model->status = 1;
		$model->save();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionHapusRincian($idtrx,$idtarif){
		$model = TransaksiDetailBill::find()->where(['idtransaksi'=>$idtrx])->andwhere(['tindakan'=>$idtarif])->all();
		foreach($model as $m){
			$m->delete();
		}
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
    public function actionIndexHide(){
		$where = ['>','total_bayar',0];
		$searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->render('index-hide',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}
	public function actionHide($id){
		$model = Transaksi::findOne($id);
		$rawat_kunjungan = RawatKunjungan::findOne($model->idkunjungan);
		$resep = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
		$bill = TransaksiDetailBill::find()->where(['idtransaksi'=>$model->id])->all();
		$rawat = Rawat::find()->where(['idkunjungan'=>$rawat_kunjungan->idkunjungan])->all();
		
		$model->hide = 1;
		$rawat_kunjungan->hide = 1;
		// return count($rawat);
		if($model->save()){
			$rawat_kunjungan->save();
			foreach($bill as $b){
				$b->hide = 1;
				$b->save();
			}
			foreach($resep as $re){
				$re->hide = 1;
				$re->save();
			}
			foreach($rawat as $r){
				$r->hide = 1;
				$r->save();
			}
			
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionUnhide($id){
		$model = Transaksi::findOne($id);
		$rawat_kunjungan = RawatKunjungan::findOne($model->idkunjungan);
		$resep = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
		$bill = TransaksiDetailBill::find()->where(['idtransaksi'=>$model->id])->all();
		$rawat = Rawat::find()->where(['idkunjungan'=>$model->idkunjungan])->all();
		
		$model->hide = 0;
		$rawat_kunjungan->hide = 0;
		
		if($model->save()){
			$rawat_kunjungan->save();
			foreach($bill as $b){
				$b->hide = 0;
				$b->save();
			}
			foreach($resep as $re){
				$re->hide = 0;
				$re->save();
			}
			foreach($rawat as $r){
				$r->hide = 0;
				$r->save();
			}
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
    public function actionIndex()
    {
		$where = ['<','hide',1];
		$andwhere = ['<>','status',3];
		$orderBy = ['tgltransaksi'=>SORT_DESC];
		$searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andwhere);
        return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionListSelesai()
    {
		$where = ['status'=>2];
		$orderBy = ['tgltransaksi'=>SORT_DESC];
		$searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
        return $this->render('list-selesai',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
public function actionRincian($id){
		$transaksi = Transaksi::findOne($id);
		$rincian_normal = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->all();
		// $rincian_paket_kecil = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>1])->count();
		// $rincian_paket_kecild = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>1])->all();
		
		// $rincian_paket_sedang = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>2])->count();
		// $rincian_paket_sedangd = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>2])->all();
		
		// $rincian_paket_besar = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>3])->count();
		// $rincian_paket_besard = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idpaket'=>1])->andwhere(['idtindakan'=>3])->all();
		
		$rincian_tarif = TransaksiDetailBill::find()->where(['idtransaksi'=>$transaksi->id])->all();
		foreach($rincian_tarif as $rt){
			$rt->delete();
		}
		// $rincian2 = new TransaksiDetailBill();
		// $rincian3 = new TransaksiDetailBill();
		// $rincian4 = new TransaksiDetailBill();
		foreach($rincian_normal as $rn){
			$rincian = new TransaksiDetailBill();
			$tarif = Tarif::findOne($rn->idtarif);
			$rincian->idrawat = $rn->idrawat;
			$rincian->idtransaksi = $transaksi->id;
			$rincian->tarif = $rn->tarif;
			$rincian->idbayar = $rn->idbayar;
			$rincian->iddokter = $rn->iddokter;
			$rincian->idtarif = $rn->idtarif;
			$rincian->jumlah = $rn->jumlah;
			if($tarif->kat_tindakan == 8){
				$rincian->tindakan = $tarif->nama_tarif.' '.$tarif->ruangan->nama_ruangan;
			}else{
				$rincian->tindakan = $tarif->nama_tarif;
			}
			
			$rincian->save(false);
		}
		// if($rincian_paket_kecil > 0){
		// if($rincian_paket_kecil < 3){
			// foreach($rincian_paket_kecild as $rp):
				
			// endforeach;
			// $rincian2->tindakan = 'Tindakan Ringan';
			// $rincian2->tarif = '150000';
			// $rincian2->idtarif = '24';
			// $rincian2->idtransaksi = $transaksi->id;
			// $rincian2->idrawat = $rp->idrawat;
			// $rincian2->idbayar = $rp->idbayar;
			// $rincian2->iddokter = $rp->iddokter;
			// $rincian2->save(false);
		// }else if($rincian_paket_kecil < 6){
			// foreach($rincian_paket_kecild as $rp):
				
			// endforeach;
			// $rincian2->tindakan = 'Tindakan Sedang';
			// $rincian2->tarif = '350000';
			// $rincian2->idtarif = '28';
			// $rincian2->idtransaksi = $transaksi->id;
			// $rincian2->idrawat = $rp->idrawat;
			// $rincian2->idbayar = $rp->idbayar;
			// $rincian2->iddokter = $rp->iddokter;
			// $rincian2->save(false);
		// }else if($rincian_paket_kecil > 5){
			// foreach($rincian_paket_kecild as $rp):
				
			// endforeach;
			// $rincian2->tindakan = 'Tindakan Besar';
			// $rincian2->tarif = '500000';
			// $rincian2->idtarif = '31';
			// $rincian2->idtransaksi = $transaksi->id;
			// $rincian2->idrawat = $rp->idrawat;
			// $rincian2->idbayar = $rp->idbayar;
			// $rincian2->iddokter = $rp->iddokter;
			// $rincian2->save(false);
		// }
		// }
		
		// if($rincian_paket_sedang > 0){
		// if($rincian_paket_sedang < 3){
			// foreach($rincian_paket_sedangd as $rs):
				
			// endforeach;
			// $rincian3->tindakan = 'Tindakan Sedang';
			// $rincian3->tarif = '350000';
			// $rincian3->idtarif = '26';
			// $rincian3->idtransaksi = $transaksi->id;
			// $rincian3->idrawat = $rs->idrawat;
			// $rincian3->idbayar = $rs->idbayar;
			// $rincian3->iddokter = $rs->iddokter;
			// $rincian3->save(false);
		// }else if($rincian_paket_sedang > 2){
			// foreach($rincian_paket_sedangd as $rs):
				
			// endforeach;
			// $rincian3->tindakan = 'Tindakan Besar';
			// $rincian3->tarif = '500000';
			// $rincian3->idtarif = '31';
			// $rincian3->idtransaksi = $transaksi->id;
			// $rincian3->idrawat = $rs->idrawat;
			// $rincian3->idbayar = $rs->idbayar;
			// $rincian3->iddokter = $rs->iddokter;
			// $rincian3->save(false);
		// }
		// }
		// if($rincian_paket_besar > 0){
		// if($rincian_paket_besar > 2){
			// foreach($rincian_paket_besard as $rb):
				
			// endforeach;
			// $rincian4->tindakan = 'Tindakan Besar';
			// $rincian4->tarif = '500000';
			// $rincian4->idtarif = '31';
			// $rincian4->idtransaksi = $transaksi->id;
			// $rincian4->idrawat = $rb->idrawat;
			// $rincian4->idbayar = $rb->idbayar;
			// $rincian4->iddokter = $rb->iddokter;
			// $rincian4->save(false);
		// }
		// }
		return $this->redirect(Yii::$app->request->referrer);
		
	}
	public function actionTarifManual($id){
		$model = Transaksi::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$tambah_dua = new TransaksiDetailBill();
		if($tambah_dua->load(Yii::$app->request->post())){
			$tambah_dua->save(false);
			return $this->redirect(['billing/'.$model->id]);
		}
		return $this->render('tarif-manual',[
			'model'=>$model,
			'tambah_dua'=>$tambah_dua,
			'pasien'=>$pasien,
		]);
	}
	public function actionView($id){
		
		// $trx = new TransaksiDetail();
		
		// $resep = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
		// $pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		// $url = 'http://localhost/simrs2021/dashboard/rest/tagihan?id='.$id;
		// $content = file_get_contents($url);
		// $json = json_decode($content, true);
		// $trxdetail = TransaksiDetail::find()->where(['idtransaksi'=>$model->id])->groupBy(['DATE_FORMAT(tgl,"%Y-%m-%d")'])->all();
		// if($trx->load(Yii::$app->request->post())){
			// $tindakan = TindakanTarif::find()->where(['idtindakan'=>$trx->idpelayanan])->andwhere(['idbayar'=>$trx->idbayar])->one();
			// $trx->nama_tindakan = $tindakan->tindakans->nama_tindakan;
			// $trx->jenis = 4;
			// $trx->idjenispelayanan = 10;
			// $trx->total = $trx->tarif * $trx->jumlah;
			// $trx->status = 1;
			// if($trx->save()){
				// $model->save();
				// return $this->refresh();
			// }
		// }
		$model = Transaksi::findOne($id);
		$model_obat = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
		$model_obat_harga = ObatTransaksi::find()->where(['idtrx'=>$model->id])->sum('total_harga');
		$model_obat_bayar = ObatTransaksi::find()->where(['idtrx'=>$model->id])->sum('total_bayar');
		$model_obat_bpjs = $model_obat_harga - $model_obat_bayar;
		$tarif_trx = TransaksiDetailRinci::find()->where(['idtransaksi'=>$model->id])->all();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-bill?id='.$model->id;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$rincian_tarif = TransaksiDetailBill::find()->where(['idtransaksi'=>$model->id])->all();
		$rincian_tarif_umum = TransaksiDetailBill::find()->where(['idtransaksi'=>$model->id])->andwhere(['idbayar'=>1])->sum('tarif');
		$rincian_tarif_bpjs = TransaksiDetailBill::find()->where(['idtransaksi'=>$model->id])->andwhere(['idbayar'=>2])->sum('tarif');
		$tambahan = new TransaksiDetailBill();
		$tambah_dua = new TransaksiDetailBill();
		if($model->load(Yii::$app->request->post())){
			$model->status = 2;
			//$model->tgl_keluar = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));;
			$model->iduser = Yii::$app->user->identity->id;
			if($model->save(false)){
				return $this->refresh();
			}	 
		}else if($tambahan->load(Yii::$app->request->post())){
			
				
				$tarif = Tarif::findOne($tambahan->idtarif);
				if($tambahan->tindakan == 'Kamar'){
					$tambahan->tindakan = $tarif->nama_tarif.' '.$tarif->ruangan->nama_ruangan;
				}else{
					$tambahan->tindakan = $tarif->nama_tarif;
				}
				$tambahan->tarif = $tambahan->tarif ;
				for ($x = 0; $x < $tambahan->jumlah; $x++) {
					$tambahan3 = new TransaksiDetailBill();
					$tambahan3->idtransaksi = $tambahan->idtransaksi;
					$tambahan3->iddokter =$tambahan->iddokter;
					$tambahan3->idbayar =$tambahan->idbayar;
					$tambahan3->tindakan =$tambahan->tindakan;
					$tambahan3->tarif =$tambahan->tarif;
					$tambahan3->jumlah =1;
					$tambahan3->idtarif =$tambahan->idtarif;
					$tambahan3->save();
				}
				
				
			return $this->refresh();
		}
		return $this->render('view2',[
			'model'=>$model,
			'model_obat'=>$model_obat,
			'model_obat_bayar'=>$model_obat_bayar,
			'model_obat_bpjs'=>$model_obat_bpjs,
			'pasien'=>$pasien,
			'tambahan'=>$tambahan,
			'tambah_dua'=>$tambah_dua,
			'tarif_trx'=>$tarif_trx,
			'rincian_tarif'=>$json,
			'rincian_tarif_umum'=>$rincian_tarif_umum,
			'rincian_tarif_bpjs'=>$rincian_tarif_bpjs,
		]);
	}
	public function actionShow($id){
		$model = Transaksi::find()->where(['no_rm'=>$id])->andwhere(['status'=>1])->all();		
		return $this->renderAjax('show',[
			'model'=>$model,
		]);
	}
	
public function actionGetTarif()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = Tarif::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionSelesai($id){
		$transaksi = Transaksi::findOne($id);
		$detail = TransaksiDetail::find()->where(['idtransaksi'=>$transaksi->id])->all();
		$total = 0;
		foreach($detail as $d){
			$total += $d->total;
		}
		$transaksi->status = 2;
		$transaksi->total = $total;
		$transaksi->iduser = Yii::$app->user->identity->id;
		if($transaksi->save(false)){
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
    
}
