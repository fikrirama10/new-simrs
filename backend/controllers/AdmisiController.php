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
use common\models\RawatJaminanBpjs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\LZCompressor\LZString;

/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class AdmisiController extends Controller
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
	 * Lists all RawatBayar models.
	 * @return mixed
	 */
	function getstatpasien($anggota = '', $idpekerjaan = '', $idbayar = '')
	{
		if ($anggota == 1) {
			if ($idpekerjaan == 1) {
				return 1;
			} else if ($idpekerjaan == 2) {
				return 2;
			} else {
				return 3;
			}
		} else {
			if ($idpekerjaan == 3) {
				return 4;
			} else if ($idpekerjaan == 4) {
				return 5;
			} else if ($idpekerjaan == 17) {
				return 6;
			} else {
				if ($idbayar == 2) {
					return 7;
				} else {
					return 8;
				}
			}
		}
	}
	public function actionPulang($id){
		$rawat = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		return $this->render('pulang',[
			'rawat'=>$rawat,
			'pasien'=>$pasien,
		]); 
	}
	public function actionCekDokter()
	{
		$response = Yii::$app->hfis->get_dokter('BED', '2022-09-28');
		//$cek = Yii::$app->hfis->searchForId('24332',$response);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionListKamar(){
		$antrian = Yii::$app->vclaim->listkamar();
		$json = json_encode($antrian, true);
		return $json;
	}
	public function actionUpdateKamar()
	{
		$antrian = Yii::$app->vclaim->updateKamar();
		$json = json_encode($antrian, true);
		return $json;
	}
	public function actionTes()
	{
		$data = '29855';
		$secretKey = '3rU307868B';
		$useKey = 'e0762860a54de319a55e481f31294d91q';
		date_default_timezone_set('UTC');
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		// $nokartu ='0001103361131';
		// $tgl = date('Y-m-d');
		// $response= Yii::$app->vclaim->get_peserta($nokartu,$tgl);
		// $json = json_encode($response, true);
		return $this->render('tester', [
			// 'response'=>$response,
			'tStamp' => $tStamp,
			'encodedSignature' => $encodedSignature,
		]);
	}
	public function actionTesing()
	{
		$addAntri = array(
			"kodebooking" => "16032021A001",
			"jenispasien" => "NON JKN",
			"nomorkartu" => "00012345678",
			"nik" => "3212345678987654",
			"nohp" => "085635228888",
			"kodepoli" => "BED",
			"namapoli" => "Bedah",
			"pasienbaru" => 0,
			"norm" => "123345",
			"tanggalperiksa" => "2022-02-07",
			"kodedokter" => 444567,
			"namadokter" => "Kapten Kes dr. Andreas , C.N. Sp.B",
			"jampraktek" => "08:00-16:00",
			"jeniskunjungan" => 1,
			"nomorreferensi" => "0",
			"nomorantrean" => "1",
			"angkaantrean" => "1",
			"estimasidilayani" => 1644205991,
			"sisakuotajkn" =>  5,
			"kuotajkn" => 30,
			"sisakuotanonjkn" => 5,
			"kuotanonjkn" => 30,
			"keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi.",
		);

		$antrian = Yii::$app->hfis->add_antrian($addAntri);
		// $response= Yii::$app->hfis->get_dokter();
		// $json = json_encode($response, true);
		$json = json_encode($antrian, true);
		return $json;
	}
	public function actionPesertaNik()
	{
		$response = Yii::$app->vclaim->get_peserta_nik('3204112508670002', date('Y-m-d'));

		return $response;
	}
	public function actionTesPoli()
	{
		$response = Yii::$app->sep->suplesi('0001226487317', '2022-03-20');
		$json = json_encode($response, true);
		return $json;
	}
	public function actionCariSpri()
	{
		$cari_spri = Yii::$app->kontrol->get_spri('0171R0010322K000020');
		$json = json_encode($cari_spri, true);
		return $json;
	}
	public function actionTesDokter()
	{
		$response = Yii::$app->hfis->get_dokter();
		$json = json_encode($response, true);
		return $json;
	}
	public function actionTesJadwal($kode, $tgl)
	{
		$response = Yii::$app->hfis->get_jadwaldokter($kode, $tgl);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionTesMonitoring()
	{
		$response = Yii::$app->monitoring->get_historipel('0001226487317', '2022-02-02', '2022-03-28');
		$json = json_encode($response, true);
		return $json;
	}
	public function actionTesRujukan()
	{
		$response = Yii::$app->rujukan->get_noka('0001226487317');
		$cek = Yii::$app->rujukan->cek_rujukan($response['rujukan']['tglKunjungan']);
		if ($cek == 0) {
			return 'Rujukan Habis';
		} else {
			$jumlah_sep = Yii::$app->rujukan->total_sep($response['asalFaskes'], $response['rujukan']['noKunjungan']);
			$json = json_encode($jumlah_sep, true);
			return $json;
			// return $response['asalFaskes'].'-'.$response['rujukan']['noKunjungan'];
		}
	}
     public function actionTesKamar(){
		$kamar = Yii::$app->vclaim->updateKamar2();
		return $kamar;
		// $model = Ruangan::find()->where(['id'=>11])->one();
		// foreach($model as $model){
			// $bed = RuanganBed::find()->where(['idruangan'=>11])->andwhere(['terisi'=>0])->andwhere(['status'=>1])->count();
			// $arrdip= json_encode(array(	
				// "kodekelas"=>$model->kode_kelas, 
				// "koderuang"=>$model->kode_ruangan, 
				// "namaruang"=>"Ruang ". $model->nama_ruangan, 
				// "kapasitas"=>$model->kapasitas, 
				// "tersedia"=>$bed,
				// "tersediapria"=>"0", 
				// "tersediawanita"=>"0", 
				// "tersediapriawanita"=>$bed,
			// ));
			// $update= Yii::$app->bpjs->updateKamar($arrdip);
			// return $update['metadata']['message'];
		// }
	}
	public function actionTesKontrol()
	{
		$response = Yii::$app->kontrol->get_kontrol_noka('03', '2022', '0001226487317', 1);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionGetKab($kode)
	{
		if ($kode == 0) {
			echo "<option value=''>--- Silahkan Pilih ---</option>";
		} else {
			$model = Yii::$app->vclaim->get_kabupaten($kode);
			echo "<option value=''>--- Silahkan Pilih ---</option>";
			foreach ($model['list'] as $k) {
				echo "<option value='" . $k['kode'] . "'>" . $k['nama'] . "</option>";
			}
		}
	}
	public function actionGetKec($kode)
	{
		if ($kode == 0) {
			echo "<option value=''>--- Silahkan Pilih ---</option>";
		} else {
			$model = Yii::$app->vclaim->get_kecamatan($kode);
			echo "<option value=''>--- Silahkan Pilih ---</option>";
			foreach ($model['list'] as $k) {
				echo "<option value='" . $k['kode'] . "'>" . $k['nama'] . "</option>";
			}
		}
	}

	public function actionTesSpri()
	{
		$spri = array(
			'request' => array(
				"noKartu" => "0001226487317",
				"kodeDokter" => "227186",
				"poliKontrol" => "INT",
				"tglRencanaKontrol" => "2022-03-13",
				"user" => "sss"
			),
		);
		$response = Yii::$app->sep->post_spri($spri);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionTesSep()
	{
		$sep = array(
			"request" => array(
				"t_sep" => array(
					"noKartu" => "0001226487317",
					"tglSep" => "2022-02-24",
					"ppkPelayanan" => "0171R001",
					"jnsPelayanan" => "2",
					"klsRawat" => array(
						"klsRawatHak" => "1",
						"klsRawatNaik" => "",
						"pembiayaan" => "",
						"penanggungJawab" => ""
					),
					"noMR" => "065012",
					"rujukan" => array(
						"asalRujukan" => "2",
						"tglRujukan" => "2022-02-24",
						"noRujukan" => "",
						"ppkRujukan" => ""
					),
					"catatan" => "testinsert RI",
					"diagAwal" => "A01",
					"poli" => array(
						"tujuan" => "IGD",
						"eksekutif" => "0"
					),
					"cob" => array(
						"cob" => "0"
					),
					"katarak" => array(
						"katarak" => "0"
					),
					"jaminan" => array(
						"lakaLantas" => "0",
						"noLP" => "12345",
						"penjamin" => array(
							"tglKejadian" => "",
							"keterangan" => "",
							"suplesi" => array(
								"suplesi" => "0",
								"noSepSuplesi" => "",
								"lokasiLaka" => array(
									"kdPropinsi" => "",
									"kdKabupaten" => "",
									"kdKecamatan" => ""
								)
							)
						)
					),
					"tujuanKunj" => "0",
					"flagProcedure" => "",
					"kdPenunjang" => "",
					"assesmentPel" => "",
					"skdp" => array(
						"noSurat" => "",
						"kodeDPJP" => ""
					),
					"dpjpLayan" => "227186",
					"noTelp" => "081111111101",
					"user" => "Coba Ws"

				),

			)
		);

		$response = Yii::$app->sep->post_sep_online($sep);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionCariSep()
	{
		$sep = '0171R0010322V000001';
		$response = Yii::$app->sep->cari_sep($sep);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionDeleteSep()
	{
		$sep = array(
			"request" => array(
				"t_sep" => array(
					"noSep" => '0171R0010322V000024',
					"user" => "sss"
				),
			)
		);
		$response = Yii::$app->sep->delete_sep($sep);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionListPulang()
	{
		$response = Yii::$app->sep->list_data_pulang('3', '2022', '');
		$json = json_encode($response, true);
		return $json;
	}
	public function actionUpdateSepPulang()
	{
		$sep = array(
			"request" => array(
				"t_sep" => array(
					"noSep" => "0171R0010322V000011",
					"statusPulang" => "1",
					"noSuratMeninggal" => "",
					"tglMeninggal" => "",
					"tglPulang" => "2022-03-27",
					"noLPManual" => "",
					"user" => "coba Ws"
				),
			),
		);
		$response = Yii::$app->sep->sep_update_pulang($sep);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionTesPolii()
	{
		$nokartu = 'Heryu';
		// $tgl = date('Y-m-d');
		// $response= Yii::$app->vclaim->get_poli('Gawat');
		$response = Yii::$app->monitoring->get_historipel('0001226487317', '2022-02-20', '2022-03-28');
		$json = json_encode($response, true);
		return $json;
		// return $this->render('tester',[
		// 'response'=>$response,
		// ]);
	}
	public function actionDeleteSpri()
	{
		$sep = array(
			"request" => array(
				"t_suratkontrol" => array(
					"noSuratKontrol" => '0171R0010322K000012',
					"user" => "sss"
				),
			)
		);
		$response = Yii::$app->kontrol->delete_spri($sep);
		$json = json_encode($response, true);
		return $json;
	}
	public function actionCreate()
	{
		$spri = new RawatSpri();
		if ($spri->load(Yii::$app->request->post())) {
			$spri->status = 1;
			$spri->idpoli = $spri->dokter->idpoli;
			$spri->kode_dokter = $spri->dokter->kode_dpjp;
			$spri->genKode();
			$spri->iduser = Yii::$app->user->identity->id;
			// $arrdip= json_encode(array(
			// "request" => array(
			// "noKartu"=>$spri->pasien->no_bpjs,
			// "kodeDokter"=>$spri->kode_dokter,
			// "poliKontrol"=>$spri->poli->kode,
			// "tglRencanaKontrol"=>$spri->tgl_rawat,
			// "user"=>"sss"
			// )));
			// $response= Yii::$app->kazo->bpjs_content(Yii::$app->params['baseUrlBpjs'].'RencanaKontrol/InsertSPRI',$arrdip);
			// $data_json=json_decode($response, true);
			// return print_r($data_json);
			if ($spri->idbayar == 2) {
				if ($spri->sep == 1) {
					$sep = array(
						"request" => array(
							"noKartu" => $spri->pasien->no_bpjs,
							"kodeDokter" => $spri->kode_dokter,
							"poliKontrol" => $spri->poli->kode,
							"tglRencanaKontrol" => $spri->tgl_rawat,
							"user" => "sss"
						)
					);
					$response = Yii::$app->kontrol->post_spri($sep);
					//$json = json_encode($response, true);
					if ($response['metaData']['code'] == '200') {
						$spri->spri_bpjs = $response['response']['noSPRI'];
						// return $spri->spri_bpjs;
					} else {
						Yii::$app->session->setFlash('danger', $response['metaData']['message']);
						return $this->refresh();
					}
				}
			}

			if ($spri->save(false)) {
				//Yii::$app->vclaim->post_spri($spri->id);
				Yii::$app->session->setFlash('success', "Data tersimpan pasien dijadwalkan masuk rawat inap Tgl " . $spri->tgl_rawat . "Nomor Spri : " . $spri->spri_bpjs);
				return $this->redirect(['admisi/index']);
			}
		}
		return $this->render('create', [
			'spri' => $spri,
		]);
	}
	public function actionIndex()
	{
		$url = Yii::$app->params['baseUrl'] . "dashboard/rest/bed";
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		$tgl = date('Y-m-d', strtotime('+6 hour', strtotime(date('Y-m-d H:i:s'))));
		$where = ['tgl_rawat' => $tgl];
		$andWhere = ['status' => 1];
		$searchModel = new RawatSpriSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $where, $andWhere);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'bed' => $json,
		]);
	}
	public function actionIndexSelesai()
	{

		$url = Yii::$app->params['baseUrl'] . "dashboard/rest/bed";
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		$tgl = date('Y-m-d');
		$tgl = date('Y-m-d');
		$where = ['status' => 2];
		$searchModel = new RawatSpriSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $where);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'bed' => $json,
		]);
	}
	public function actionIndexSemua()
	{

		$url = Yii::$app->params['baseUrl'] . "dashboard/rest/bed";
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		$tgl = date('Y-m-d');
		$tgl = date('Y-m-d');
		$searchModel = new RawatSpriSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'bed' => $json,
		]);
	}
	public function actionPindahRuangan()
	{
		$where = ['status' => 1];
		$searchModel = new RawatPermintaanPindahSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $where);

		return $this->render('pindah-ruangan', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionPindah($id)
	{
		$model = RawatPermintaanPindah::findOne($id);
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$rawat = Rawat::findOne($model->idrawat);
		$transaksi = Transaksi::find()->where(['idkunjungan' => $rawat->kunjungans->id])->one();
		$tgl = date('Y-m-d G:i:s', strtotime('+5 hour', strtotime(date('Y-m-d G:i:s'))));
		$ruangan = new RawatRuangan();
		$ruangan_asal = RawatRuangan::find()->where(['idrawat' => $rawat->id])->andwhere(['status' => 1])->one();
		$bed = RuanganBed::findOne($rawat->idbed);
		if ($rawat->load($this->request->post()) && $ruangan->load($this->request->post())) {
			$ruangan_asal->status = 2;
			$ruangan_asal->tgl_keluar = date('Y-m-d G:i:s', strtotime('+5 hour', strtotime(date('Y-m-d G:i:s'))));
			$bed2 = RuanganBed::findOne($rawat->idbed);
			$date1 = date_create($ruangan_asal->tgl_keluar);
			$date2 = date_create($ruangan_asal->tgl_masuk);
			$diff = date_diff($date1, $date2);
			$ruangan_asal->los = $diff->format("%d") + 1;
			$bed->terisi = 0;
			$bed2->terisi = 1;
			// if($ruangan_asal->idkelas == 1){
			// $tindakan = Tindakan::findOne(38);
			// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$ruangan_asal->idbayar])->one();
			// }else if($ruangan_asal->idkelas == 2){
			// $tindakan = Tindakan::findOne(39);
			// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$ruangan_asal->idbayar])->one();
			// }else{
			// $tindakan = Tindakan::findOne(40);
			// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$ruangan_asal->idbayar])->one();
			// }
			$tarif_ruangan = Tarif::find()->where(['kat_tindakan' => 8])->andwhere(['idkelas' => $ruangan_asal->idkelas])->andwhere(['idruangan' => $ruangan_asal->idruangan])->one();
			if ($ruangan_asal->save(false)) {
				$bed->save(false);
				Yii::$app->kazo->tranruanganrinci($transaksi->id, $rawat->id, $ruangan_asal->tgl_keluar, $tarif_ruangan->id, $tarif_ruangan->tarif, $ruangan_asal->idbayar, $ruangan_asal->los + 1);
				Yii::$app->kazo->updateBed($ruangan_asal->idbed);
				$model->status = 2;
				$model->save();
				$ruangan->status = 1;
				$ruangan->idbed = $rawat->idbed;
				$ruangan->idkelas = $rawat->idkelas;
				$ruangan->idruangan = $rawat->idruangan;
				$ruangan->tgl_masuk = date('Y-m-d G:i:s', strtotime('+5 hour', strtotime(date('Y-m-d G:i:s'))));
				$ruangan->asal = 'Rawat Inap';
				if ($ruangan->save(false)) {
					$rawat->save(false);
					$bed2->save(false);
					return $this->redirect(['admisi/index']);
				}
			}
		}
		// return $ruangan_asal->status;
		return $this->render('pindah', [
			'model' => $model,
			'pasien' => $pasien,
			'rawat' => $rawat,
			'ruangan' => $ruangan,
		]);
	}
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$rawat = new Rawat();
		$jaminanbpjs = new RawatJaminanBpjs();
		$new_kunjungan = new RawatKunjungan();
		$ruangan = new RawatRuangan();
		$transaksi = new Transaksi();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$time = date('Y-m-d');
		$response = Yii::$app->vclaim->get_peserta($pasien->no_bpjs, $time);
		if ($rawat->load($this->request->post())) {

				//return $rawat->penanggungjawab;
				$kunjungan = RawatKunjungan::find()->where(['no_rm' => $model->no_rm])->andwhere(['tgl_kunjungan' => $model->tgl_rawat])->one();
				if ($kunjungan) {
					$rawat->idkunjungan = $kunjungan->idkunjungan;
					$ruangan->idkunjungan = $kunjungan->id;
				} else {
					$new_kunjungan->genKode();
					$new_kunjungan->iduser = Yii::$app->user->identity->id;
					$new_kunjungan->usia_kunjungan = $pasien->usia_tahun;
					$new_kunjungan->no_rm = $model->no_rm;
					$new_kunjungan->tgl_kunjungan = $model->tgl_rawat;
					$new_kunjungan->jam_kunjungan = date('G:i:s', strtotime('+5 hour', strtotime(date('G:i:s'))));
					$new_kunjungan->status = 1;

					if ($new_kunjungan->save(false)) {
						$ruangan->idkunjungan = $new_kunjungan->id;
						$transaksi->genKode();
						$transaksi->idkunjungan = $new_kunjungan->id;
						$transaksi->kode_kunjungan = $new_kunjungan->idkunjungan;
						$transaksi->no_rm = $model->no_rm;
						$transaksi->tgltransaksi = $new_kunjungan->tgl_kunjungan . ' ' . $new_kunjungan->jam_kunjungan;
						$transaksi->tgl_masuk = $new_kunjungan->tgl_kunjungan;
						$transaksi->status = 1;
						$transaksi->save(false);
					}
					$rawat->idkunjungan = $new_kunjungan->idkunjungan;
				}
				if ($rawat->idbed == null) {
					Yii::$app->session->setFlash('warning', 'Tempat Tidur belum di pilih silahkan pilih tempat tudur terlebih dahulu');
					return $this->refresh();
				}
				$rawat->genKode(2);
				$rawat->tglmasuk = $model->tgl_rawat;
				$rawat->status = 2;
				$rawat->kat_pasien = $this->getstatpasien($rawat->anggota, $pasien->idpekerjaan, $rawat->idbayar);
				$rawat->iduser = Yii::$app->user->identity->id;

				if ($model->sep == 1) {
					$icdx = explode(" - ", $rawat->icdx);
					$kode = $icdx[0];
					$nama = $icdx[1];
					if ($rawat->katarak == 1) {
						$katarak = 1;
					} else {
						$katarak = 0;
					}
					if ($rawat->naik_kelas == 1) {
						$naik = $rawat->kelasnaik->naik;
					} else {
						$naik = '';
					}
					$sep = array(
						"request" => array(
							"t_sep" => array(
								"noKartu" => $pasien->no_bpjs,
								"tglSep" => date('Y-m-d', strtotime($model->tgl_rawat)),
								"ppkPelayanan" => "0120R012",
								"jnsPelayanan" => "1",
								"klsRawat" => array(
									"klsRawatHak" => $response['peserta']['hakKelas']['kode'],
									"klsRawatNaik" => $naik,
									"pembiayaan" => $rawat->pembiayaan,
									"penanggungJawab" => $rawat->penanggungjawab
								),
								"noMR" => $pasien->no_rm,
								"rujukan" => array(
									"asalRujukan" => "2",
									"tglRujukan" => $model->tgl_rawat,
									"noRujukan" => $rawat->no_rujukan,
									"ppkRujukan" => "0120R012"
								),
								"catatan" => "-",
								"diagAwal" => $kode,
								"poli" => array(
									"tujuan" => "",
									"eksekutif" => "0"
								),
								"cob" => array(
									"cob" => "0"
								),
								"katarak" => array(
									"katarak" => $katarak,
								),
								"jaminan" => array(
									"lakaLantas" => $rawat->jaminan,
									"noLP" => $jaminanbpjs->noLp,
									"penjamin" => array(
										"tglKejadian" => $jaminanbpjs->tglkejadian,
										"keterangan" => $jaminanbpjs->keterangan,
										"suplesi" => array(
											"suplesi" => $jaminanbpjs->suplesi,
											"noSepSuplesi" => $jaminanbpjs->nosep_suplesi,
											"lokasiLaka" => array(
												"kdPropinsi" => $jaminanbpjs->propinsi,
												"kdKabupaten" => $jaminanbpjs->kabupaten,
												"kdKecamatan" => $jaminanbpjs->kecamatan
											)
										)
									)
								),
								"tujuanKunj" => "0",
								"flagProcedure" => "",
								"kdPenunjang" => "",
								"assesmentPel" => "",
								"skdp" => array(
									"noSurat" => $model->spri_bpjs,
									"kodeDPJP" => $rawat->dokter->kode_dpjp
								),
								"dpjpLayan" => "",
								"noTelp" => $pasien->nohp,
								"user" => "Coba Ws"

							),

						)
					);
					//	 return print_r($sep);
					$response = Yii::$app->sep->post_sep_online($sep);
					// return print_r($response);
					if ($response['metaData']['code'] != 200) {
						Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $response['metaData']['message']);
						return $this->refresh();
					}
					$rawat->no_sep = $response['response']['sep']['noSep'];
				}
				if ($rawat->save(false)) {
					if ($rawat->jaminan > 0) {
						$jaminanbpjs->idpasien = $pasien->id;
						$jaminanbpjs->idrawat = $rawat->id;
						$jaminanbpjs->save(false);
					}
					if ($rawat->pembiayaan == 1) {
						$rawat->penanggungjawab = 'Pribadi';
					} else {
						$rawat->penanggungjawab = $rawat->penanggungjawab;
					}
					$model->idrawat = $rawat->id;
					$model->status = 2;
					$model->save(false);
					$bed = RuanganBed::find()->where(['id' => $rawat->idbed])->one();
					$bed->terisi = 1;
					if ($bed->save(false)) {
						Yii::$app->vclaim->updateKamar2();
						// Yii::$app->vclaim->updateKamar();
						//Yii::$app->kazo->updateBed($rawat->idbed);
						$ruangan->idbed = $rawat->idbed;
						$ruangan->idkelas = $rawat->idkelas;
						$ruangan->idrawat = $rawat->id;
						$ruangan->no_rm = $pasien->no_rm;
						$ruangan->idruangan = $rawat->idruangan;
						$ruangan->idbayar = $rawat->idbayar;
						$ruangan->tgl_masuk = date('Y-m-d G:i:s', strtotime('+6 hour', strtotime(date('Y-m-d G:i:s'))));
						$ruangan->status = 1;
						$ruangan->asal = $rawat->jenisrawat->jenis;
						$ruangan->save(false);
						if ($model->sep == 1) {
							if ($response['metaData']['code'] == 200) {
								Yii::$app->session->setFlash('success', 'No SEP : ' . $response['response']['sep']['noSep']);
							} else {
								Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $response['metaData']['message']);
							}
						}

						return $this->redirect(['pasien/' . $model->pasien->id]);
					}
				}
				
			}
			if ($model->sep == 1) {
				return $this->render('view', [
					'model' => $model,
					'pasien' => $pasien,
					'rawat' => $rawat,
					'jaminanbpjs' => $jaminanbpjs,
					'bpjs' => $response,
				]);
			} else {
				return $this->render('view-umum', [
					'model' => $model,
					'pasien' => $pasien,
					'rawat' => $rawat,
					'jaminanbpjs' => $jaminanbpjs,
					'bpjs' => $response,
				]);
			}
		}
	
	public function actionPrintSpri($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = RawatSpri::find()->where(['id' => $id])->one();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$cari_spri = Yii::$app->kontrol->get_spri($model->spri_bpjs);
		$content = $this->renderPartial('spri-ranap', ['model' => $model, 'spri' => $cari_spri, 'pasien' => $pasien]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'marginTop' => '3',
			'marginLeft' => '10',
			'marginRight' => '10',
			'marginBottom' => '0',
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
	public function actionPrintSpri2($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$rawat = Rawat::findOne($id);
		$model = RawatSpri::find()->where(['idrawat' => $id])->one();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$cari_spri = Yii::$app->kontrol->get_spri($model->spri_bpjs);
		$content = $this->renderPartial('spri-ranap', ['model' => $model, 'spri' => $cari_spri, 'pasien' => $pasien,]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'marginTop' => '3',
			'marginLeft' => '10',
			'marginRight' => '10',
			'marginBottom' => '0',
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
	public function actionShowRuangan($id, $bayi)
	{
		$pelayanan = new Rawat();
		if ($bayi == 1) {
			$ruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->andwhere(['bayi' => 1])->all();
			$cruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->andwhere(['bayi' => 1])->count();
		} else {

			$ruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->all();
			$cruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->count();
		}
		return $this->renderAjax('show-ruangan', [
			'ruangan' => $ruangan,
			'pelayanan' => $pelayanan,
			'cruangan' => $cruangan,
		]);
	}
	public function actionShowPasien($id)
	{
		$spri = new RawatSpri();
		$pasien = Pasien::find()->where(['no_rm' => $id])->one();

		return $this->renderAjax('show-pasien', [
			'pasien' => $pasien,
			'spri' => $spri,
		]);
	}
	/**
	 * Deletes an existing RawatBayar model.
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
	 * Finds the RawatBayar model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return RawatBayar the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = RawatSpri::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
