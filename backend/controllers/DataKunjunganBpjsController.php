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
class DataKunjunganBpjsController extends Controller
{
	public function actionView($id,$poli){
		$data_sep= Yii::$app->bpjs->cari_sep($id);
		if($data_sep['metaData']['code'] != 200){
			Yii::$app->session->setFlash('danger', $data_sep['metaData']['message']);
			return $this->redirect(['/data-kunjungan-bpjs']);
		}
		$peserta = Yii::$app->bpjs->get_pesertanobpjs($data_sep['response']['peserta']['noKartu'],$data_sep['response']['tglSep']);
		if($data_sep['response']['jnsPelayanan'] == 'Rawat Inap'){
			$layanan = 1;
		}else{
			$layanan =2;
		}
		if($poli != 'IGD'){
			$dpjp = Yii::$app->bpjs->get_dpjp($poli,$layanan,$data_sep['response']['tglSep']);
		}else{
			$dpjp = Yii::$app->bpjs->get_dpjp('',1,$data_sep['response']['tglSep']);
		}
		$rujukan = Yii::$app->bpjs->rujukan_nomor($data_sep['response']['noRujukan']);
		$kecelakaan = [
			['id' => '0', 'data' => 'Bukan Kecelakaan lalu lintas'],
			['id' => '1', 'data' => 'Kecelakaan Lalu lintas dan bukan kecelakaan Kerja'],
			['id' => '2', 'data' => 'Kecelakaan Lalu lintas dan kecelakaan Kerja'],
			['id' => '3', 'data' => 'Kecelakaan Kerja'],
		];
		$pembiayaan = [
			['id'=>'1' , 'data'=>'Pribadi'],
			['id'=>'2' , 'data'=>'Pemberi Kerja'],
			['id'=>'3' , 'data'=>'Asuransi Kesehatan Tambahan'],
		];
		$post = Yii::$app->request->post();
		$request = Yii::$app->request;
		if($post){
			$nomr = $request->post('nomr');
			$nohp = $request->post('nohp');
			$catatan = $request->post('catatan');
			$icdx = $request->post('icdx');
			$pembiayaan = $request->post('pembiayaan');
			$kelas_naik = $request->post('kelas_naik');
			$penanggungjawab = $request->post('penanggungjawab');
			$icdxx = explode(" - ", $icdx);
			$kode = $icdxx[0];
			$nama = $icdxx[1];
			$jaminan = $request->post('jaminan');
			if($jaminan == 0){
				$tglkejadian = '';
				$provinsi = '';
				$kabupaten ='';
				$kecamatan = '';
				$keterangan = '';

			}else{
				$tglkejadian = $request->post('tglkejadian');
				$provinsi = $request->post('propinsi');
				$kabupaten = $request->post('kabupaten');
				$kecamatan = $request->post('kecamatan');
				$keterangan = $request->post('keterangan');
			}			
			$dpjp = $request->post('dpjp');		
			
			$sep_update = array(
				"request"=>array(
					"t_sep"=>array(
							"noSep"=>$data_sep['response']['noSep'],
							"klsRawat"=>array(
									"klsRawatHak"=>$data_sep['response']['klsRawat']['klsRawatHak'],
									"klsRawatNaik"=>$kelas_naik,
									"pembiayaan"=>$pembiayaan,
									"penanggungJawab"=>$penanggungjawab
								  ),
							"noMR"=>$nomr,
							"catatan"=>$catatan,
							"diagAwal"=>$kode,
							"poli"=>array(
									"tujuan"=>$poli,
									"eksekutif"=>"0"
							),
							"cob"=>array(
									"cob"=>"0"
							),
							"katarak"=>array(
									"katarak"=>"0"
							),
							"jaminan"=>array(
									"lakaLantas"=>$jaminan,
									"penjamin"=>array(
											"tglKejadian"=>$tglkejadian,
											"keterangan"=>$keterangan,
											"suplesi"=>array(
													"suplesi"=>"0",
													"noSepSuplesi"=>"",
													"lokasiLaka"=>array(
															"kdPropinsi"=>$provinsi,
															"kdKabupaten"=>$kabupaten,
															"kdKecamatan"=>$kecamatan
													)
											)
									)
							),
							"dpjpLayan"=>$dpjp,
							"noTelp"=>$nohp,
							"user"=>"FikriRama"
					)
				  )
			);
			$update_sep = Yii::$app->bpjs->update_sep($sep_update);
			// return print_r($sep_update);
			if($update_sep['metaData']['code'] == 200){
				Yii::$app->session->setFlash('success', 'Update SEP : '.$update_sep['metaData']['message']);
				return $this->redirect(['/data-kunjungan-bpjs']);
			}else{
				Yii::$app->session->setFlash('danger', $update_sep['metaData']['message']);
				return $this->refresh();
			}
		}
		return $this->render('view',[
			'data_sep'=>$data_sep,
			'peserta'=>$peserta,
			'dpjp'=>$dpjp,
			'rujukan'=>$rujukan,					
			'kecelakaan'=>$kecelakaan,
			'pembiayaan'=>$pembiayaan,
		]);
	}
	public function actionIndex(){
		return $this->render('index');
	}
	public function actionShowList($awal,$akhir,$filter){
		$response= Yii::$app->bpjs->data_kunjungan($awal,$filter);
		// return print_r($response);		
		// $data_json=json_decode($response, true);
		$data = $response['response']['sep'];
			// $provider = $$response['response']['list']['sep'][' noSep']->search(Yii::$app->request->get());
			$dataProvider = new ArrayDataProvider([
				  'allModels' => $data,
				  'pagination' => [
				  'pageSize' => 100,
				],
			 
			]);
		return $this->renderAjax('show-data',[
			'dataProvider'=>$dataProvider,
			// 'filter'=>$filter,
			'response'=>$response,
		]);
	}
}