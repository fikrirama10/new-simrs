<?php

namespace backend\controllers;

use Yii;
use common\models\Pasien;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class RujukanFaskesController extends Controller
{
	public function actionIndex(){
		return $this->render('index');
	}
	public function actionShowData($id){
		$model = Pasien::find()->where(['no_rm'=>$id])->one();
		return $this->renderAjax('show-data',[
			'model'=>$model,
		]);
	}
	
	public function actionBuatRujukan($id){
		$sep_pasien = Yii::$app->bpjs->cari_sep($id);
		
		if($sep_pasien['metaData']['code'] != 200){
			Yii::$app->session->setFlash('danger', $sep_pasien['metaData']['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}
		$peserta = Yii::$app->bpjs->get_pesertanobpjs($sep_pasien['response']['peserta']['noKartu'],$sep_pasien['response']['tglSep']);
		$post = Yii::$app->request->post();
		$request = Yii::$app->request;
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
						'noSep'=>$id,
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
			$posting_rujukan = Yii::$app->bpjs->post_rujukan($post_rujukan);
			if($posting_rujukan['metaData']['code'] == '200'){
				Yii::$app->session->setFlash('success', 'No rujukan : '.$posting_rujukan['response']['rujukan']['noRujukan']);
				return $this->redirect(['/rujukan-faskes']);
			}else{
				Yii::$app->session->setFlash('danger', $posting_rujukan['metaData']['message']);
				return $this->refresh();
			}
		}
		return $this->render('buat-rujukan',[
			'peserta'=>$peserta,
			'sep'=>$sep_pasien,
		]);
	}
	public function actionHapus($norujukan){
		$response =Yii::$app->bpjs->detail_rujukan_keluar($norujukan);
		$post_rujukan = array(
			'request'=>array(
				't_rujukan'=>array(
					'noRujukan'=>$norujukan,
					'user'=>"Coba Ws",
				),
			),
		);
		$posting_rujukan = Yii::$app->bpjs->hapus_rujukan($post_rujukan);
		if($posting_rujukan['metaData']['code'] == '200'){
			Yii::$app->session->setFlash('success', 'Berhasil Hapus No rujukan : '.$posting_rujukan['response']);
			return $this->redirect(['/rujukan-faskes']);
		}else{
			Yii::$app->session->setFlash('danger', $posting_rujukan['metaData']['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}
		
	}
	public function actionEditRujukan($norujukan){
		$response =Yii::$app->bpjs->detail_rujukan_keluar($norujukan);
		if($response['metaData']['code'] != 200){
			return $this->redirect(['/rujukan-faskes']);
		}
		$peserta = Yii::$app->bpjs->get_pesertanobpjs($response['response']['rujukan']['noKartu'],$response['response']['rujukan']['tglSep']);
		$sep_pasien = Yii::$app->bpjs->cari_sep($response['response']['rujukan']['noSep']);
		$post = Yii::$app->request->post();
		$request = Yii::$app->request;
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
			$post_rujukan = array(
				'request'=>array(
					't_rujukan'=>array(
						'noRujukan'=>$norujukan,
						'tglRujukan'=>$response['response']['rujukan']['tglRujukan'],
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
			$posting_rujukan = Yii::$app->bpjs->edit_rujukan($post_rujukan);
			if($posting_rujukan['metaData']['code'] == '200'){
				Yii::$app->session->setFlash('success', 'Berhasil Update No rujukan : '.$posting_rujukan['response']);
				return $this->redirect(['/rujukan-faskes']);
			}else{
				Yii::$app->session->setFlash('danger', $posting_rujukan['metaData']['message']);
				return $this->refresh();
			}
		}
		return $this->render('edit-rujukan',[
			'rujukan'=>$response,
			'peserta'=>$peserta,
			'sep'=>$sep_pasien,
		]);
	}
	public function actionShowList($awal,$akhir){
		$response= Yii::$app->bpjs->list_rujukan_keluarrs($awal,$akhir);
		// return print_r($response);		
		// $data_json=json_decode($response, true);
		$data = $response['response']['list'];
			// $provider = $$response['response']['list']['sep'][' noSep']->search(Yii::$app->request->get());
			$dataProvider = new ArrayDataProvider([
				  'allModels' => $data,
				  'pagination' => [
				  'pageSize' => 100,
				],
			 
			]);
		return $this->renderAjax('show-list',[
			'dataProvider'=>$dataProvider,
			// 'filter'=>$filter,
			'response'=>$response,
		]);
	}
	public function actionGetFaskes($kode){
	
		$model = Yii::$app->bpjs->get_faskes($kode,2);
		// return print_r($model);
		echo "<option value=''>--- Silahkan Pilih ---</option>";
		foreach($model['faskes'] as $k){
			echo "<option value='".$k['kode'].",".$k['nama']."'>".$k['nama']."</option>";
		}	
	}
	public function actionGetFasilitas($id,$tgl){
		$model = Yii::$app->bpjs->get_rujukan_spesialistik($id,$tgl);
		// return print_r($model);
		return $this->renderAjax('fasilitas',[
			'model'=>$model['response']['list'],
		]);
	}
}