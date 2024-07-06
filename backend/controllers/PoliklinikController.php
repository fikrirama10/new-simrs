<?php

namespace backend\controllers;

use Yii;
use common\models\Operasi;
use common\models\Poli;
use kartik\mpdf\Pdf;
use common\models\Rawat;
use common\models\RawatRujukan;
use common\models\Pasien;
use common\models\SoapRajaldokter;
use common\models\TransaksiDetailRinci;
use common\models\SoapRajalperawat;
use common\models\SoapRajalicdx;
use common\models\SoapRajalobat;
use common\models\SoapRajaltindakan;
use common\models\Tarif;
use common\models\ObatBacth;
use common\models\Dokter;
use common\models\SoapRadiologi;
use common\models\SoapLab;
use common\models\RawatResep;
use common\models\RawatResepDetail;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\Transaksi;
use common\models\TransaksiDetail;
use common\models\RawatSearch;
use common\models\RawatSpri;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class PoliklinikController extends Controller
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
     * Lists all Poli models.
     * @return mixed
     */
	function milliseconds() {
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
	public function actionLaporan(){
		return $this->render('laporan');
	}
	public function actionShowLaporan($bulan,$tahun,$poli){
		$poliklinik = Poli::findOne($poli);
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/statistik-perpoli?bulan='.$bulan.'&tahun='.$tahun.'&poli='.$poli;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$array_bulan = array('01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET', '04'=>'APRIL', '05'=>'MEI', '06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER', '11'=>'NOVEMBER','12'=>'DESEMBER');
		$bulann = $array_bulan[$bulan];
		$searchRajal = new RawatSearch();
		$where = ['<>','idjenisrawat',2];
		$andWhere = ['<>','status',5];
		$andWhere2 = ['idpoli'=>$poli];
		$andWhere3 = ['hide'=>0];
		$andWhereTgl = ['YEAR(tglmasuk)'=>$tahun];
		$andwhereBulan = ['MONTH(tglmasuk)'=>$bulan];
        $dataRajal = $searchRajal->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhere2,$andWhere3,$andWhereTgl,$andwhereBulan);
		return $this->renderAjax('show-laporan',[
			'model'=>$json,
			'poliklinik'=>$poliklinik->poli,
			'polii'=>$poli,
			'bulan'=>$bulann,
			'bulann'=>$bulan,
			'tahun'=>$tahun,
			'searchRajal'=>$searchRajal,
			'dataRajal'=>$dataRajal,
		]);
	}
	
	public function actionPrintLaporan($bulan,$tahun,$poli){
	    $poliklinik = Poli::findOne($poli);
		$array_bulan = array('01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET', '04'=>'APRIL', '05'=>'MEI', '06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER', '11'=>'NOVEMBER','12'=>'DESEMBER');
		$bulann = $array_bulan[$bulan];
		$rawat_umum = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>1])->orderBy(['tglmasuk'=>SORT_ASC,'iddokter'=>SORT_ASC,'kat_pasien'=>SORT_DESC])->all();
		$rawat_bpjs = Rawat::find()->where(['MONTH(tglmasuk)'=>$bulan])->andwhere(['YEAR(tglmasuk)'=>$tahun])->andwhere(['hide'=>0])->andwhere(['<>','idjenisrawat',2])->andwhere(['idpoli'=>$poli])->andwhere(['<>','status',5])->andwhere(['idbayar'=>2])->orderBy(['tglmasuk'=>SORT_ASC,'iddokter'=>SORT_ASC,'kat_pasien'=>SORT_DESC])->all();
		$content = $this->renderPartial('print-laporan',[
			'rawat_umum'=>$rawat_umum,
			'rawat_bpjs'=>$rawat_bpjs,	
			'kunjungan'=>$poliklinik,	
			'bulann'=>$bulann,	
			'tahun'=>$tahun,	
		]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'orientation' => 'L', 
			'marginTop'=>10,
			'content' => $content,  
			'cssFile' => '@frontend/web/css/paperbarang.css',
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
	public function actionHistoryPasien(){
		if(Yii::$app->user->identity->userdetail->iddokter == null){
			return $this->redirect(['/poliklinik']);
		}
		$tgl = date('Y-m',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$start = $tgl.'-01';
		$end = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$tgli = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$url_rajal = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=1&jenisbayar=2';
		$content_rajal = file_get_contents($url_rajal);
		$json_rajal = json_decode($content_rajal, true);
		
		$url_ugd = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=3&jenisbayar=2';
		$content_ugd = file_get_contents($url_ugd);
		$json_ugd = json_decode($content_ugd, true);
		
		$url_ranap = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=2&jenisbayar=2';
		$content_ranap = file_get_contents($url_ranap);
		$json_ranap = json_decode($content_ranap, true);
		return $this->render('history-pasien',[
			'rajal'=>$json_rajal,
			'ranap'=>$json_ranap,
			'ugd'=>$json_ugd,
			'start'=>$start,
			'end'=>$end,
			'judul'=>'Pasien BPJS',
			'jenis'=>2,
		]);
	}
	public function actionHapusObat($id){
		$model = RawatResepDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionEditRujuk($id){
		$model = RawatRujukan::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		if($model->load(Yii::$app->request->post())){
			if($model->save(false)){
				return $this->redirect(['poliklinik/'.$model->idrawat]);
			}
		}
		return $this->render('edit-rujuk',[
			'model'=>$model,
			'rawat'=>$rawat,
			'pasien'=>$pasien,
		]);
	}
	public function actionHistoryPasienUmum(){
		$tgl = date('Y-m',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$start = $tgl.'-01';
		$tgli = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$end = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$url_rajal = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=1&jenisbayar=1';
		$content_rajal = file_get_contents($url_rajal);
		$json_rajal = json_decode($content_rajal, true);
		
		$url_ugd = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=3&jenisbayar=1';
		$content_ugd = file_get_contents($url_ugd);
		$json_ugd = json_decode($content_ugd, true);
		
		$url_ranap = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$tgl.'-01&end='.$tgli.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=2&jenisbayar=1';
		$content_ranap = file_get_contents($url_ranap);
		$json_ranap = json_decode($content_ranap, true);
		return $this->render('history-pasien',[
			'rajal'=>$json_rajal,
			'ranap'=>$json_ranap,
			'ugd'=>$json_ugd,
			'start'=>$start,
			'end'=>$end,
			'judul'=>'Pasien UMUM',
			'jenis'=>1,
		]);
	}
	public function actionIndexAll(){
		$where = ['idjenisrawat'=>1];
		$judul = 'Pasien Rawat Jalan';
		$searchModel = new RawatSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'judul' => $judul,
        ]);
	}
	public function actionIndexOnline(){
		$where = ['antrian_online'=>1];
		$judul = 'Pasien Rawat Jalan Online';
		$searchModel = new RawatSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->render('index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'judul' => $judul,
        ]);
	}
	public function actionShowHistory($start='',$end='',$jenis=''){
		$url_rajal = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$start.'&end='.$end.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=1&jenisbayar='.$jenis;
		$content_rajal = file_get_contents($url_rajal);
		$json_rajal = json_decode($content_rajal, true);
		
		$url_ugd = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$start.'&end='.$end.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=3&jenisbayar='.$jenis;
		$content_ugd = file_get_contents($url_ugd);
		$json_ugd = json_decode($content_ugd, true);
		
		$url_ranap = Yii::$app->params['baseUrl'].'dashboard/rest/histori-pasien-rajal?start='.$start.'&end='.$end.'&iddokter='.Yii::$app->user->identity->userdetail->iddokter.'&jenisrawat=2&jenisbayar='.$jenis;
		$content_ranap = file_get_contents($url_ranap);
		$json_ranap = json_decode($content_ranap, true);
        return $this->renderAjax('show-history',
			[
			'rajal'=>$json_rajal,
			'ranap'=>$json_ranap,
			'ugd'=>$json_ugd,
			]
		);
	}
    public function actionIndex()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		if(Yii::$app->user->identity->userdetail->idpoli == 1){
			return $this->redirect(['/poliklinik/ugd']);
		}else if(Yii::$app->user->identity->userdetail->idpoli == null){
			return $this->redirect(['/site/index']);
		}
        $searchModel = new RawatSearch();
		$tgl = date('Y-m-d',strtotime('+6 hours',strtotime(date('Y-m-d H:i:s'))));
		$where = ['idjenisrawat'=>1];
		if(Yii::$app->user->identity->userdetail->dokter == 1){
			$andWhere = ['iddokter'=>Yii::$app->user->identity->userdetail->iddokter];
		}else{
			$andWhere = ['idpoli'=>Yii::$app->user->identity->userdetail->idpoli];
		}
		$andWhere2 = ['<>','status',5];
		$andWhereTgl = ['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhere2,$andWhereTgl);
		if(Yii::$app->user->identity->userdetail->idpoli == null){
			$judul = 'Poliklinik';
		}else{
			$judul = Poli::find()->where(['id'=>Yii::$app->user->identity->userdetail->idpoli])->one();
		}
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'judul' => $judul,
            'tgl' => $tgl,
        ]);
    }
	public function actionAllPasien()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		if(Yii::$app->user->identity->userdetail->idpoli == 1){
			return $this->redirect(['/poliklinik/all-pasien-ugd']);
		}else if(Yii::$app->user->identity->userdetail->idpoli == null){
			return $this->redirect(['/site/index']);
		}
        $searchModel = new RawatSearch();
		$tgl = date('Y-m-d',strtotime('+6 hours',strtotime(date('Y-m-d H:i:s'))));
		$where = ['idjenisrawat'=>1];
		if(Yii::$app->user->identity->userdetail->dokter == 1){
			$andWhere = ['iddokter'=>Yii::$app->user->identity->userdetail->iddokter];
		}else{
			$andWhere = ['idpoli'=>Yii::$app->user->identity->userdetail->idpoli];
		}
		$andWhere2 = ['<>','status',5];
		$andWhereTgl = ['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhere2);
		if(Yii::$app->user->identity->userdetail->idpoli == null){
			$judul = 'Poliklinik';
		}else{
			$judul = Poli::find()->where(['id'=>Yii::$app->user->identity->userdetail->idpoli])->one();
		}
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'judul' => $judul,
        ]);
    }
	public function actionAllPasienUgd()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		if(Yii::$app->user->identity->userdetail->idpoli == null){
			return $this->redirect(['/site/index']);
		}
        $searchModel = new RawatSearch();
		$tgl = date('Y-m-d',strtotime('+6 hours',strtotime(date('Y-m-d H:i:s'))));
		$where = ['idjenisrawat'=>3];
		$andWhere = ['idpoli'=>Yii::$app->user->identity->userdetail->idpoli];
		$andWhere2 = ['<>','status',5];
		$andWhereTgl = ['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhere2);
		if(Yii::$app->user->identity->userdetail->idpoli == null){
			$judul = 'Poliklinik';
		}else{
			$judul = Poli::find()->where(['id'=>Yii::$app->user->identity->userdetail->idpoli])->one();
		}
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'judul' => $judul,
        ]);
    }
	public function actionUgd()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		if(Yii::$app->user->identity->userdetail->idpoli != 1){
			return $this->redirect(['/poliklinik']);
		}else if(Yii::$app->user->identity->userdetail->idpoli == null){
			return $this->redirect(['/site/index']);
		}
        $searchModel = new RawatSearch();
		$tgl = date('Y-m-d',strtotime('+6 hours',strtotime(date('Y-m-d H:i:s'))));
		$where = ['idjenisrawat'=>3];
		$andWhere = ['idpoli'=>Yii::$app->user->identity->userdetail->idpoli];
		$andWhere2 = ['<>','status',5];
		$andWhereTgl = ['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere,$andWhere2,$andWhereTgl);
		if(Yii::$app->user->identity->userdetail->idpoli == null){
			$judul = 'Poliklinik';
		}else{
			$judul = Poli::find()->where(['id'=>Yii::$app->user->identity->userdetail->idpoli])->one();
		}
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'judul' => $judul,
        ]);
    }

    /**
     * Displays a single Poli model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionTindakan($id){
		$model = $this->findModel($id);
		$tindakan = SoapRajaltindakan::find()->where(['idrawat'=>$model->id])->all();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$transaksi = Transaksi::find()->where(['kode_kunjungan'=>$model->idkunjungan])->one();
		$tarif_trx = new TransaksiDetailRinci();
		$soaptindakan = new SoapRajaltindakan();
		$list_tarif_trx = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idjenis'=>1])->all();
		if($soaptindakan->load(Yii::$app->request->post())){
			$bayar2 = Yii::$app->request->post('bayar2');	
			$dokter2 = Yii::$app->request->post('dokter2');	
			if($bayar2 == 0){
					$soaptindakan->idbayar = $model->idbayar;
				}else{
					$soaptindakan->idbayar = $bayar2;
				}		
			
			if($soaptindakan->idtindakan == null){
				Yii::$app->session->setFlash('danger', "Tindakan Kosong ."); 
				return $this->refresh();
			}
			if($soaptindakan->save(false)){
			
				$tarif = Tarif::find()->where(['id'=>$soaptindakan->idtindakan])->one();
				if($tarif->paket == 1){
					$tarif_trx->idpaket = 1;
				}else{
					$tarif_trx->idpaket = 0;
				}
				if($bayar2 == 0){
					$tarif_trx->idbayar = $model->idbayar;
				}else{
					$tarif_trx->idbayar = $bayar2;
				}		
				if($dokter2 == 0){
					$tarif_trx->iddokter = $model->iddokter;
				}else{
					$tarif_trx->iddokter = $dokter2;
				}				
				$tarif_trx->idjenis = 0;
				$tarif_trx->idrawat = $model->id;
				$tarif_trx->idtransaksi = $transaksi->id;
				$tarif_trx->idtarif = $tarif->id;
				$tarif_trx->tarif = $tarif->tarif;
				$tarif_trx->idtindakan = $tarif->kat_tindakan;
				$tarif_trx->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$tarif_trx->save(false);
				return $this->refresh();
			}
			
		}else if($tarif_trx->load(Yii::$app->request->post())){
			$bayar = Yii::$app->request->post('bayar');	
				$dokter = Yii::$app->request->post('dokter');	
				$tarif = Tarif::find()->where(['id'=>$tarif_trx->idtarif])->one();
				if($bayar == 0){
					$tarif_trx->idbayar = $model->idbayar;
				}else{
					$tarif_trx->idbayar = $bayar;
				}		
				if($dokter == 0){
					$tarif_trx->iddokter = $model->iddokter;
				}else{
					$tarif_trx->iddokter = $dokter;
				}				
				$tarif_trx->idjenis = 1;
				$tarif_trx->tarif = $tarif->tarif;
				$tarif_trx->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$tarif_trx->idpaket = 0;
				$tarif_trx->idrawat = $model->id;
				$tarif_trx->idtransaksi = $transaksi->id;
				$tarif_trx->save(false);
				return $this->refresh();
		}
		return $this->render('tindakan',[
			'model'=>$model,
			'pasien'=>$pasien,
			'tindakan'=>$tindakan,
			'tarif_trx'=>$tarif_trx,
			'soaptindakan'=>$soaptindakan,
			'list_tarif_trx'=>$list_tarif_trx,
		]);
	}

    public function actionEditSoap($id,$jenis=''){
		if($jenis == 1){
			$model = SoapRajaldokter::find()->where(['id'=>$id])->one();
		}else{
			$model = SoapRajalperawat::find()->where(['id'=>$id])->one();
		}
		$rawat = Rawat::find()->where(['id'=>$model->idrawat])->one();
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		if ($model->load(Yii::$app->request->post()) && $rawat->load(Yii::$app->request->post())) {
			$model->edit = 1;
			$model->tgl_edit=date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			if($model->save(false)){
				$rawat->save(false);
				Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 5"); 
				return $this->redirect(['/poliklinik/'.$rawat->id]);
			}
		}
		return $this->render('edit-soap',[
			'model'=>$model,
			'rawat'=>$rawat,
			'pasien'=>$pasien,
			'jenis'=>$jenis,
		]);
	}
	public function actionHapusTindakan($id){
		$soap = SoapRajaltindakan::findOne($id);
		$trx = TransaksiDetailRinci::find()->where(['idrawat'=>$soap->idrawat])->andWhere(['idtarif'=>$soap->idtindakan])->one();
		
		$soap->delete();
		$trx->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusPenunjang($id){
		$trx = TransaksiDetailRinci::find()->where(['id'=>$id])->one();
		
		$trx->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusRad($id){
		$soap = SoapRadiologi::findOne($id);		
		$soap->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusLab($id){
		$soap = SoapLab::findOne($id);		
		$soap->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
    public function actionViewPasien($id){
		$rawat = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		return $this->redirect(['pasien/'.$pasien->id]);
	}
    public function actionView($id)
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		$operasi = new Operasi();
		
		$tarif_trx = new TransaksiDetailRinci();
		$model = $this->findModel($id);
		$trxdetail = new TransaksiDetail();
		$transaksi = Transaksi::find()->where(['kode_kunjungan'=>$model->idkunjungan])->one();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$rawat = Rawat::find()->where(['no_rm'=>$model->no_rm])->andwhere(['<>','status',5])->all();
		$icdx = new SoapRajalicdx();
		$soapradiologilist = SoapRadiologi::find()->where(['idrawat'=>$model->id])->all();
		$soapobatlist = SoapRajalobat::find()->where(['idrawat'=>$model->id])->all();
		$soapradiologi = new SoapRadiologi();
		$soaplablist = SoapLab::find()->where(['idrawat'=>$model->id])->all();;
		$soaplab = new SoapLab();
		$soapobat = new SoapRajalobat();
		$rujukan = new RawatRujukan();
		$spri = new RawatSpri();
		$soaptindakanlist = SoapRajaltindakan::find()->where(['idrawat'=>$model->id])->all();;
		$soaptindakan = new SoapRajaltindakan();
		$resep = new RawatResep();
		$operasi_list = Operasi::find()->where(['idrawat'=>$model->id])->all();
		$operasi = new Operasi();
		$resep_list = RawatResep::find()->where(['idrawat'=>$model->id])->all();
		
		
		// if(Yii::$app->user->identity->userdetail->dokter == 1){
			// $soap = new SoapRajaldokter();
			// $soapcount = SoapRajaldokter::find()->where(['idrawat'=>$model->id])->one();
			// $soapperawat = SoapRajalperawat::find()->where(['idrawat'=>$model->id])->one();
		// }else{
			// $soap = new SoapRajalperawat();
			// $soapcount = SoapRajalperawat::find()->where(['idrawat'=>$model->id])->one();
		// }
		$soapdokter = new SoapRajaldokter();
		$soapdoktercount = SoapRajaldokter::find()->where(['idrawat'=>$model->id])->one();
		$soapperawat = SoapRajalperawat::find()->where(['idrawat'=>$model->id])->one();
		$perawatsoap = new SoapRajalPerawat();
		$perawatsoapcount = SoapRajalPerawat::find()->where(['idrawat'=>$model->id])->one();
		$list_tarif_trx = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andwhere(['idjenis'=>1])->all();
		if ($soapdokter->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {
			$model->cara_keluar = $model->rawatstatus->status;
			$model->tglpulang = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$tanggal = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$date1=date_create(date('Y-m-d',strtotime($model->tglmasuk)));
			$date2=date_create($tanggal);
			$diff=date_diff($date1,$date2);
			$model->los = $diff->format("%d");
			$model->save(false);
			$soapdokter->usia = '["'.$pasien->usia_tahun.'","'.$pasien->usia_bulan.'","'.$pasien->usia_hari.'"],';
			if($soapdokter->save(false)){
				if($model->taksid == 4){
					$taks = array(
						"kodebooking"=> $model->idrawat,
						"taskid"=> 5,
						"waktu"=>  $this->milliseconds(),
					);
					$taksid = Yii::$app->hfis->update_taks($taks);
					if($taksid['metadata']['code'] == 200){
						$model->taksid = 5;
						$model->save(false);
						
					}else{
						Yii::$app->session->setFlash('success', $taksid['metadata']['message']); 
						return $this->redirect(Yii::$app->request->referrer);
					}
				}
				Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 5"); 
				return $this->refresh();
			}
            
			}else if ($perawatsoap->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {
			
			$model->tglpulang = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$tanggal = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$date1=date_create(date('Y-m-d',strtotime($model->tglmasuk)));
			$date2=date_create($tanggal);
			$diff=date_diff($date1,$date2);
			$model->los = $diff->format("%d");
			$model->save(false);
			$perawatsoap->usia = '["'.$pasien->usia_tahun.'","'.$pasien->usia_bulan.'","'.$pasien->usia_hari.'"],';
			if($perawatsoap->save(false)){
				return $this->refresh();
			}
            
        }else if($icdx->load(Yii::$app->request->post())){

				$icdx->kat_pasien = $model->kat_pasien;
				$icdx->save();
			
		}else if($soaptindakan->load(Yii::$app->request->post())){
			if($soaptindakan->save(false)){
				$bayar = Yii::$app->request->post('bayar');	
				$tarif = Tarif::find()->where(['id'=>$soaptindakan->idtindakan])->one();
				if($tarif->paket == 1){
					$tarif_trx->idpaket = 1;
				}
				$tarif_trx->idbayar = $bayar;
				$tarif_trx->idrawat = $model->id;
				$tarif_trx->idtransaksi = $transaksi->id;
				$tarif_trx->idtarif = $tarif->id;
				$tarif_trx->tarif = $tarif->tarif;
				$tarif_trx->iddokter = $model->iddokter;
				$tarif_trx->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$tarif_trx->save(false);
				// $trxdetail->idtransaksi = $transaksi->id;
				// $trxdetail->idtindakan = $tarif->id;
				// if($tarif->idtarif == 2){
				// $trxdetail->iddokter = $model->iddokter;
				// $trxdetail->persentase_dokter = ($tarif->persentase_dokter / 100) * $tarif->tarif;
				// }
				// $trxdetail->nama_tindakan = $tarif->tindakans->nama_tindakan;
				// $trxdetail->jenis = $model->idjenisrawat;
				// $trxdetail->idrawat = $soaptindakan->idrawat;
				// $trxdetail->idkunjungan = $transaksi->idkunjungan;
				// $trxdetail->idpelayanan = $soaptindakan->idtindakan;
				// $trxdetail->tgl = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				// $trxdetail->tarif = $tarif->tarif;
				// $trxdetail->total = $tarif->tarif;
				// $trxdetail->jumlah = 1;
				// $trxdetail->idbayar = $model->idbayar;
				// $trxdetail->status = 1;
				// $trxdetail->idjenispelayanan = 6;
				// $trxdetail->save(false);
				return $this->refresh();
			}
			
		}else if($soapradiologi->load(Yii::$app->request->post())){
		    if($soapradiologi->idtindakan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan radiologi"); 
				return $this->refresh();
		    }
			$soapradiologi->no_rm = $model->no_rm;
			if($soapradiologi->save(false)){
				return $this->refresh();
			}
			
		}else if($soaplab->load(Yii::$app->request->post())){
		    if($soaplab->idpemeriksaan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan Lab"); 
				return $this->refresh();
		    }
		    $soaplab->save();
			return $this->refresh();
		}else if($rujukan->load(Yii::$app->request->post())){
			$rujukan->genKode();
			if($rujukan->save()){
				return $this->refresh();
			}
			
		}else if($soapobat->load(Yii::$app->request->post())){
			$soapobat->status = 1;
			if($soapobat->save()){
				return $this->refresh();
			}
			
		}else if($spri->load(Yii::$app->request->post())){
			$spri->status = 1;
			$spri->genKode();
			$spri->iduser = Yii::$app->user->identity->id;
			if($spri->save(false)){
				Yii::$app->session->setFlash('success', "Terkirim ke admisi , silahkan arahkan penanggung jawab pasien ke ruangan admisi."); 
				return $this->refresh();
			}
			
		}else if($resep->load(Yii::$app->request->post())){
			$resep->genKode();
			$resep->status = 1;
			if($resep->tgl_resep == null){
				$resep->tgl_resep = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			}
			$resep->jam_resep = date('G:i:s',strtotime('+6 hour',strtotime(date('G:i:s'))));
			if($resep->save(false)){
				return $this->redirect(['tambah-obat?id='.$resep->id]);
			}
		}else if($operasi->load(Yii::$app->request->post())){
			$model->ok = 1;
			$operasi->genKode();
			$operasi->idasal = $model->idruangan;
			$operasi->no_rm = $model->no_rm;
			$operasi->idrawat = $model->id;
			$operasi->status = 1;
			if($operasi->save(false)){
				$model->save(false);
				Yii::$app->session->setFlash('success', 'Pasien Sudah masuk antrian OK');
				return $this->refresh();
			}
		}
		if($model->taksid == 3){
			$taks = array(
				"kodebooking"=> $model->idrawat,
				"taskid"=> 4,
				"waktu"=>  $this->milliseconds(),
			);
			$taksid = Yii::$app->hfis->update_taks($taks);
			if($taksid['metadata']['code'] == 200){
				$model->taksid = 4;
				$model->save(false);
				Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 4");
			}else{
				Yii::$app->session->setFlash('success', $taksid['metadata']['message']); 
				 //return $this->redirect(Yii::$app->request->referrer);
			}
			
		}
		
        return $this->render('view', [
            'model' => $model,
            'pasien' => $pasien,
            'operasi' => $operasi,
            'soapdokter' => $soapdokter,
            'soapdoktercount' => $soapdoktercount,
            'perawatsoap' => $perawatsoap,
            'perawatsoapcount' => $perawatsoapcount,
            'rawat' => $rawat,
            'soapperawat' => $soapperawat,
            'icdx' => $icdx,
            'list_tarif_trx' => $list_tarif_trx,
            'soaptindakan' => $soaptindakan,
            'soaptindakanlist' => $soaptindakanlist,
            'soapradiologilist' => $soapradiologilist,
            'soapradiologi' => $soapradiologi,
            'soaplab' => $soaplab,
            'soaplablist' => $soaplablist,
            'rujukan' => $rujukan,
            'soapobat' => $soapobat,
            'soapobatlist' => $soapobatlist,
            'spri' => $spri,
            'resep' => $resep,
            'resep_list' => $resep_list,
            'tarif_trx' => $tarif_trx,
            'operasi_list' => $operasi_list,
        ]);
    }
	public function actionHapusResep($id){
		$model = RawatResep::findOne($id);
		$list_resep = RawatResepDetail::find()->where(['idresep'=>$model->id])->all();
	
			foreach($list_resep as $lr):
				$lr->delete();
			endforeach;
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionTambahObat($id){
		$model = RawatResep::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$obat_list = RawatResepDetail::find()->where(['idresep'=>$model->id])->all();
		$resep_obat = new RawatResepDetail();
		if($resep_obat->load(Yii::$app->request->post())){
			$resep_obat->idresep = $model->id;
			$resep_obat->status = 1;
			if($resep_obat->save(false)){
				return $this->refresh();
			}
		}
		return $this->render('tambah-obat',[
			'model'=>$model,
			'pasien'=>$pasien,
			'rawat'=>$rawat,
			'resep_obat'=>$resep_obat,
			'obat_list'=>$obat_list,
		]);
	}
	public function actionShowBatch($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->andwhere(['>','stok_apotek',0])->all();
		$resep_obat = new RawatResepDetail();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
			'resep_obat'=>$resep_obat,
		]);
	}
	
	public function actionShowDokter($id){
		$dokter = Dokter::find()->where(['idpoli'=>$id])->all();
		return $this->renderAjax('show-dokter',[
			'dokter'=>$dokter,			
		]);
	}
	public function actionGetDokter()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = Dokter::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionGetBatch()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = ObatBacth::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }

    /**
     * Creates a new Poli model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
    /**
     * Updates an existing Poli model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
  
    /**
     * Deletes an existing Poli model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
    public function actionDeleteDiagnosa($id){
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		$diagnosa = SoapRajalicdx::find()->where(['id'=>$id])->one();
		$diagnosa->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Poli model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Poli the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rawat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
