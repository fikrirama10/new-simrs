<?php

namespace backend\controllers;

use Yii;
use common\models\Rawat;
use common\models\RawatKunjungan;
use common\models\Pasien;
use common\models\RawatSpri;
use common\models\Transaksi;
use common\models\Tarif;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\RuanganBed;
use common\models\RawatRuangan;
use common\models\RawatPermintaanPindahSearch;
use common\models\RawatPermintaanPindah;
use common\models\RawatSpriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class SuratKontrolController extends Controller
{
	public function actionIndex(){
		// $response= Yii::$app->bpjs->data_surat_kontrol(date('Y-m-d',strtotime('+7hour')),date('Y-m-d',strtotime('+7hour')),1);		
		// $data_json=json_decode($response, true);
		// $data = $response['list'];
		// $dataProvider = new ArrayDataProvider([
		  // 'allModels' => $data,
		  // 'pagination' => [
		  // 'pageSize' => 25,
		// ],
		 
		// ]);
		
		return $this->render('index');
	}
	public function actionPostUpdate($id){
		$post = Yii::$app->request->post();
		if($post){
			$kodepoli = Yii::$app->request->post('kodepoli'.$id);
			$kodedpjp = Yii::$app->request->post('kodedpjp'.$id);
			$cbpelayanan = Yii::$app->request->post('cbpelayanan'.$id);
			$rencana = Yii::$app->request->post('rencana'.$id);
			$nosep = Yii::$app->request->post('nosep'.$id);
			$sep_kontrol = array(
				'request'=>array(
					'noSuratKontrol'=>$id,
					'noSEP'=>$nosep,
					'kodeDokter'=>$kodedpjp,
					'poliKontrol'=>$kodepoli,
					'tglRencanaKontrol'=>$rencana,
					'user'=>'RM Sulaiman',
				),
			);
			$post_kontrol = Yii::$app->bpjs->update_surat_kontrol($sep_kontrol);
			if($post_kontrol['metaData']['code'] == 200){
				Yii::$app->session->setFlash('success', 'No Surat '.$post_kontrol['response']['noSuratKontrol']); 
				return $this->redirect(['/surat-kontrol']);
			}else{
				Yii::$app->session->setFlash('danger', $post_kontrol['metaData']['message']); 
				return $this->redirect(['/surat-kontrol']);
			}
		}
	}
	public function actionDeleteKontrol($id){
		$post = array(
			'request'=>array(
				't_suratkontrol'=>array(
					'noSuratKontrol'=>$id,
					'user'=>'Fikri Ramadhan',
				),
			),
		);
		$response= Yii::$app->bpjs->delete_surat_kontrol($post);
		if($response['metaData']['code'] == 200){
			Yii::$app->session->setFlash('success', $response['metaData']['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}else{
			Yii::$app->session->setFlash('danger', $response['metaData']['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}
		
	}
	public function actionBuatSurat($id){
		$sep_pasien = Yii::$app->bpjs->cari_sep($id);
		
		if($sep_pasien['metaData']['code'] != 200){
			Yii::$app->session->setFlash('danger', $sep_pasien['metaData']['message']);
			return $this->redirect(Yii::$app->request->referrer);
		}
		$peserta = Yii::$app->bpjs->get_pesertanobpjs($sep_pasien['response']['peserta']['noKartu'],$sep_pasien['response']['tglSep']);
		$rujukan = Yii::$app->bpjs->rujukan_nomor($sep_pasien['response']['noRujukan']);
		if($rujukan['metaData']['code'] != 200){
			$rujukan = Yii::$app->bpjs->rujukan_nomor_rs($sep_pasien['response']['noRujukan']);
		}
		$post = Yii::$app->request->post();
		if($post){
			$kodepoli = Yii::$app->request->post('kodepoli');
			$kodedpjp = Yii::$app->request->post('kodedpjp');
			$cbpelayanan = Yii::$app->request->post('cbpelayanan');
			$rencana = Yii::$app->request->post('rencana');
			$sep_kontrol = array(
				'request'=>array(
					'noSEP'=>$sep_pasien['response']['noSep'],
					'kodeDokter'=>$kodedpjp,
					'poliKontrol'=>$kodepoli,
					'tglRencanaKontrol'=>$rencana,
					'user'=>'RM Sulaiman',
				),
			);
			// return print_r($sep_kontrol);
			$post_kontrol = Yii::$app->bpjs->insert_surat_kontrol($sep_kontrol);
			if($post_kontrol['metaData']['code'] == 200){
				Yii::$app->session->setFlash('success', 'No Surat '.$post_kontrol['response']['noSuratKontrol']); 
				return $this->redirect(['/surat-kontrol']);
			}else{
				Yii::$app->session->setFlash('danger', $post_kontrol['metaData']['message']); 
				return $this->refresh();
			}
		}
		return $this->render('buat-surat',[
			'sep'=>$sep_pasien,
			'peserta'=>$peserta,
			'rujukan'=>$rujukan,
		]);
	}
	public function actionShowDokter($poli,$tgl,$kontrol=''){
		$dataDokter = Yii::$app->bpjs->data_dokter_kontrol(2,$poli,$tgl);
		return $this->renderAjax('show-dokter',[
			'dataDokter'=>$dataDokter,
			'tgl'=>$tgl,
			'kontrol'=>$kontrol,
		]);		
	}
	public function actionShowPoli($tgl,$nosep,$kontrol=''){
		$datapoli = Yii::$app->bpjs->data_poli_kontrol(2,$nosep,$tgl);
		return $this->renderAjax('show-poli',[
			'datapoli'=>$datapoli,
			'tgl'=>$tgl,
			'kontrol'=>$kontrol,
		]);
	}
	public function actionShowData($id){
		$model = Pasien::find()->where(['no_rm'=>$id])->one();
		return $this->renderAjax('show-data',[
			'model'=>$model,
		]);
	}
	public function actionShowList($awal,$akhir,$filter){
		$response= Yii::$app->bpjs->data_surat_kontrol($awal,$akhir,$filter);
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
}
