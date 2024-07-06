<?php
namespace backend\controllers;
use Yii;
use yii\base\Model;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Rawat;
use common\models\RawatRujukan;
use common\models\RawatRujukanSearch;
use common\models\Pasien;

class RujukanController extends Controller
{
	public function actionIndex(){
		$searchModel = new RawatRujukanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}
	// public function actionIndexUmum(){
		// return $this->render('index-umum');
	// }
	public function actionHapusRujukan($id){
		$model = RawatRujukan::findOne($id);
		if($model->no_rujukan == null){
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}else{
			return $this->redirect(['/rujukan/hapaus-bpjs?id='.$id]);
		}
	
	}
	public function actionHapusBpjs($id){
		$model = RawatRujukan::findOne($id);
		$post_rujuk = array(
			 "request"=>array (
				"t_rujukan"=>array (
					"noRujukan"=> $model->no_rujukan,
					"user"=> "User Ws"
				)
			)
		);
		$response = Yii::$app->rujukan->delete_rujukan($post_rujuk);
		if($response['metaData']['code'] == 200){
			$model->delete();
			return $this->redirect(['index']);
		}
	}
		
	public function actionShowData($id){
		$model = Rawat::find()->where(['no_rm'=>$id])->andwhere(['<>','status',5])->orderBy(['tglmasuk'=>SORT_DESC])->all();
		return $this->renderAjax('show-data',[
			'model'=>$model,
		]);
	}
	public function actionShowList($awal,$akhir){
		$searchModel = new RawatRujukanSearch();
		$where = ['between','tgl_kunjungan',$awal,$akhir];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		return $this->renderAjax('show-listrujukan',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
	}
	public function actionViewRujukan($id){
		$rujukan = RawatRujukan::findOne($id);
		$model = Rawat::findOne($rujukan->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$peserta= Yii::$app->vclaim->get_peserta($pasien->no_bpjs,date('Y-m-d'));
		$get_rujukan= Yii::$app->rujukan->get_rujukanrs($rujukan->no_rujukan);
		// return print_r($get_rujukan);
		return $this->render('view',[
			'rujukan'=>$rujukan,
			'model'=>$model,
			'pasien'=>$pasien,
			'peserta'=>$peserta,
			'get_rujukan'=>$get_rujukan,
		]);
	}
	public function actionRujukanUmum($id){
		$model = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$request = Yii::$app->request;
		$rujukan = new RawatRujukan();
		$post = $request->post();
		if($post){
			$tglrujukan = $request->post('tglrujukan');
			$icdx = $request->post('icdx');
			$namappk = $request->post('txtnmppkjadwal');
			$catatanrujukan = $request->post('catatanrujukan');
			$rujuk = explode(",", $namappk);
			$icdxx = explode(" - ", $icdx);
			$rujukan->faskes_tujuan = $rujuk[1];
			$rujukan->idrawat = $model->id;
			$rujukan->idjenisrawat = $model->idjenisrawat;
			$rujukan->idbayar = 1;
			$rujukan->idpoli = $model->idpoli;
			$rujukan->iddokter = $model->iddokter;
			$date = date('Y-m-d',strtotime('+7 hour'));
			$pf = date('mY-',strtotime(($date)));
			$rujukan->genKoderujuk($pf);
			$rujukan->alasan_rujuk = $catatanrujukan;
			$rujukan->tgl_rujuk = $tglrujukan;
			$rujukan->tgl_kunjungan	= $tglrujukan;
			$rujukan->icd10 = $icdx;
			$rujukan->diagnosa_klinis = $icdxx[1];
			$rujukan->no_rm = $model->no_rm;
			
			$rujukan->save(false);
			return $this->redirect(['/rujukan']);
		}
		return $this->render('rujukan-umum',[
			'model'=>$model,
			'pasien'=>$pasien,
		]);
	}
	public function actionRujukan($id){
		$model = Rawat::findOne($id);
		$rujukan = new RawatRujukan();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$cari_peserta= Yii::$app->vclaim->get_peserta($pasien->no_bpjs,date('Y-m-d'));
		$cari_sep= Yii::$app->sep->cari_sep($model->no_sep);
		$request = Yii::$app->request;
		$post = $request->post();
		if($post){
			$tglrujukan = $request->post('tglrujukan');
			$jenisrujuk = $request->post('rbrujukan');
			$jenislayanan = $request->post('jpelayanan');
			$icdx = $request->post('icdx');
			$tglrencanaRujuk = $request->post('tglrencanaRujuk');
			$kodepoli = $request->post('kodepolii');
			$kodeppk = $request->post('kodeppk');
			$namapoli = $request->post('namapoli');
			$namappk = $request->post('namappk');
			$catatanrujukan = $request->post('catatanrujukan');
			$icdxx = explode(" - ", $icdx);
			$kode = $icdxx[0];
			$nama = $icdxx[1];
			$post_array = array();
			$poliklinik =  explode(" ", $kodepoli);;
			$poli = $poliklinik[0];
			// return $poliklinik;
			$post_rujukan = array(
				'request'=>array(
					't_rujukan'=>array(
						'noSep'=>$model->no_sep,
						'tglRujukan'=>$tglrujukan,
						'tglRencanaKunjungan'=>$tglrencanaRujuk,
						'ppkDirujuk'=>$kodeppk,
						'jnsPelayanan'=>$jenislayanan,
						'catatan'=>$catatanrujukan,
						'diagRujukan'=>$kode,
						'tipeRujukan'=>$jenisrujuk,
						'poliRujukan'=> $poli,
						'user'=>"Coba Ws",
					),
				),
			);
			// return print_r($post_rujukan);
			$posting_rujukan = Yii::$app->rujukan->post_rujukan($post_rujukan);
			if($posting_rujukan['metaData']['code'] == '200'){
				$rujukan->idrawat = $model->id;
				$rujukan->idjenisrawat = $model->idjenisrawat;
				$rujukan->idbayar = 2;
				$rujukan->idpoli = $model->idpoli;
				$rujukan->iddokter = $model->iddokter;
				$rujukan->kode_tujuan = $kodeppk;
				$rujukan->kode_asal = $cari_peserta['peserta']['provUmum']['kdProvider'];
				$rujukan->faskes_tujuan = $posting_rujukan['response']['rujukan']['tujuanRujukan']['nama'];
				$rujukan->faskes_asal = $cari_peserta['peserta']['provUmum']['nmProvider'];
				$rujukan->no_rujukan = $posting_rujukan['response']['rujukan']['noRujukan'];
				$date = date('Y-m-d',strtotime('+7 hour'));
				$pf = date('mY-',strtotime(($date)));
	    		$rujukan->genKoderujuk($pf);
				$rujukan->alasan_rujuk = $catatanrujukan;
				$rujukan->tgl_rujuk = $tglrencanaRujuk;
				$rujukan->tgl_kunjungan = $tglrujukan;
				$rujukan->idspesialis = $poli;
				$rujukan->no_sep = $model->no_sep;
				$rujukan->icd10 = $icdx;
				$rujukan->diagnosa_klinis = $nama;
				$rujukan->no_rm = $model->no_rm;
				$rujukan->save();
				Yii::$app->session->setFlash('success', 'No rujukan : '.$posting_rujukan['response']['rujukan']['noRujukan']);
				return $this->redirect(['/rujukan/view-rujukan?id='.$rujukan->id]);

			}
			return print_r($posting_rujukan);
		}
		return $this->render('rujukan',[
			'model'=>$model,
			'pasien'=>$pasien,
			'peserta'=>$cari_peserta,
			'sep'=>$cari_sep,
		]);
	}
	public function actionPrintRujukan($id) {
		if(Yii::$app->user->isGuest){
		return $this->redirect(['site/logout']);
		}
		$rujukan = RawatRujukan::findOne($id);
		$get_rujukan= Yii::$app->rujukan->get_rujukanrs($rujukan->no_rujukan);
		// return print_r($get_rujukan);
		//$cari_peserta= Yii::$app->vclaim->get_peserta($pasien->no_bpjs,date('Y-m-d'));
		$content = $this->renderPartial('print-rujukan',['model' => $rujukan,'rujukan'=>$get_rujukan]);
		  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'marginTop' => '3',
			'marginLeft' => '10',
		   'marginRight' => '10',
		   'marginBottom' => '0',
		   // 'format' => [210,97],
		   'format' => Pdf::FORMAT_A4,
		   'orientation' => Pdf::ORIENT_PORTRAIT, 
		   'content' => $content,  
		   'cssFile' => '@frontend/web/css/paper-sep.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

	// return the pdf output as per the destination setting
	return $pdf->render(); 
	}
	public function actionPrintLabel($id) {
		if(Yii::$app->user->isGuest){
		return $this->redirect(['site/logout']);
		}
		$rujukan = RawatRujukan::findOne($id);
		// $get_rujukan= Yii::$app->rujukan->get_rujukanrs($rujukan->no_rujukan);
		// return print_r($get_rujukan);
		//$cari_peserta= Yii::$app->vclaim->get_peserta($pasien->no_bpjs,date('Y-m-d'));
		$content = $this->renderPartial('print-label',['model' => $rujukan]);
		  // setup kartik\mpdf\Pdf component
			  $pdf = new Pdf([
				   'mode' => Pdf::MODE_CORE,
				   'destination' => Pdf::DEST_BROWSER,
				   'format' => [70,34],
				   'marginTop' => '5',
				   'orientation' => Pdf::ORIENT_PORTRAIT, 
				   'marginLeft' => '3',
				   'marginRight' => '3',
				   'marginBottom' => '3',
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
	public function actionGetFaskes($kode){
		
			$model = Yii::$app->vclaim->get_faskes($kode,2);
			echo "<option value=''>--- Silahkan Pilih ---</option>";
			foreach($model['faskes'] as $k){
				echo "<option value='".$k['kode'].",".$k['nama']."'>".$k['nama']."</option>";
			}	
	}
	public function actionGetFasilitas($id,$tgl){
		$model = Yii::$app->rujukan->get_spesialistik($id,$tgl);
		return $this->renderAjax('fasilitas',[
			'model'=>$model['list'],
		]);
	}
}