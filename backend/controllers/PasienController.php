<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use kartik\mpdf\Pdf;
use common\models\Kelurahan;
use common\models\Kecamatan;
use common\models\Provinsi;
use common\models\RawatPermintaanPindah;
use common\models\Rawat;
use common\models\Pasien;
use common\models\Poli;
use common\models\RawatRuangan;
use common\models\RawatJaminanBpjs;
use common\models\Ruangan;
use common\models\Tindakan;
use common\models\RawatRujukan;
use common\models\TindakanTarif;
use common\models\RuanganBed;
use common\models\SoapRajaldokter;
use common\models\SoapRajalperawat;
use common\models\SoapRajalicdx;
use common\models\SoapLab;
use common\models\SoapRadiologi;
use common\models\SoapRajalobat;
use common\models\PasienAlamat;
use common\models\PasienStatus;
use common\models\Dokter;
use common\models\DokterJadwal;
use common\models\DokterKuota;
use common\models\PasienSearch;
use common\models\RawatKunjungan;
use common\models\Transaksi;
use common\models\Tarif;
use common\models\Undangan;
use common\models\RawatKontrol;
use common\models\RawatSearch;
use common\models\TransaksiDetail;
use common\models\RawatSpri;
use common\models\TransaksiDetailRinci;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class PasienController extends Controller
{
	/**
	 * @inheritDoc
	 */
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			]
		);
	}
	// public function beforeAction($action){
	// if(parent::beforeAction($action)){
	// $headers = Yii::$app->user->identity->idpriv;
	// if($headers == 8 ||  $headers == 17){

	// return true;

	// }else{
	// return $this->redirect(['/site']);
	// }
	// }
	// throw new \yii\web\UnauthorizedHttpException('Error');
	// return false;
	// }
	function milliseconds()
	{
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
	function konversi_jam($date)
	{
		$existing_time = $date;
        
        // Ubah waktu menjadi timestamp
        $timestamp = strtotime($existing_time);
        
        // Ambil waktu saat ini dalam format microtime
        list($microseconds, $seconds) = explode(' ', microtime());
        $current_microtime = $seconds . substr($microseconds, 2);
        
        // Gabungkan nilai timestamp dan microtime
        $milliseconds = ($timestamp * 1000) + round($current_microtime * 1000);
        return $milliseconds;
	}
	public function actionBarcodeUndangan()
	{
		$undangan = Undangan::find()->all();
		$content = $this->renderPartial('barcode-undangan', [
			'undangan' => $undangan
		]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [60, 36],
			'marginTop' => '3',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '1',
			'marginRight' => '1',
			'marginBottom' => '3',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-undangan.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');
		return $pdf->render();
	}
	public function actionTesRest()
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/pasien-rest/pasien-sex';
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);

		return print_r($json['agama']);
	}
	public function actionCreateRujukan($id)
	{
		$rujukan = RawatRujukan::find()->where(['id' => $id])->one();
		$rawat = Rawat::find()->where(['id' => $rujukan->idrawat])->one();
		$pasien = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		return $this->render('create-rujukan', [
			'rujukan' => $rujukan,
			'rawat' => $rawat,
			'pasien' => $pasien,
		]);
	}
	public function actionRujukan()
	{
		return $this->render('rujukan');
	}
	public function actionViewPasien($id)
	{
		$rawat = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		return $this->redirect(['/pasien/' . $pasien->id]);
	}
	public function actionShowRujuk($id)
	{
		$rujukan = RawatRujukan::find()->where(['no_rm' => $id])->andWhere(['status' => 1])->all();
		return $this->renderAjax('show-rujuk', [
			'rujukan' => $rujukan,
		]);
	}
	public function actionShowHistoriBpjs($noka, $awal, $akhir)
	{
		$response = Yii::$app->monitoring->get_historipel($noka, $awal, $akhir);
		return $this->renderAjax('show-hostori', [
			'response' => $response,
		]);
	}
	public function actionPrintSepNosep($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$cari_sep = Yii::$app->bpjs->cari_sep($id);
		$cari_peserta = Yii::$app->bpjs->get_pesertanobpjs($cari_sep['response']['peserta']['noKartu'], date('Y-m-d'));
		$content = $this->renderPartial('print_sep_no', ['sep' => $cari_sep, 'peserta' => $cari_peserta]);
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
	public function actionIndex()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$searchModel = new PasienSearch();
		$dataProvider = $searchModel->search($this->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionListPoliklinik()
	{
		$searchModel = new RawatSearch();
		$dataProvider = $searchModel->search($this->request->queryParams);
		return $this->render('list-poli', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionShowAntrian(){
		$content = Yii::$app->kazo->fetchApiData('http://192.168.5.8:8081/api/get-antrian/1/1');
		$json = json_decode($content, true);
		return print_r($content);
	}
	public function actionShowRujukan($id)
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/pasien-rest/find-pasien?rm=' . $id;
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		if ($json['kode'] == 200) {
			return $this->redirect(['pasien/' . $json['id']]);
		} else {
			return $this->renderAjax('show-rujukan', [
				'json' => $json,
			]);
		}
	}
	public function actionTesRujukan()
	{
		$id = '0002067700149';
		$response = Yii::$app->rujukan->get_nokas($id);
		return print_r($response);
	}
	public function actionShowListrujukan($id, $idrawat, $faskes)
	{

		if ($faskes == 1) {
			$data = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Rujukan/List/Peserta/' . $id, 2);
			$data_json = json_decode($data, true);
		} else {
			$data = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Rujukan/RS/List/Peserta/' . $id, 2);
			$data_json = json_decode($data, true);
		}
		$rawat = Rawat::find()->where(['id' => $idrawat])->one();
		return $this->renderAjax('show-listrujukan', [
			'json' => $data_json,
			'rawat' => $rawat,
			'faskes' => $faskes,
			'norujukan' => $id,
		]);
	}
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
	function jenjang_usia($barulahir = '', $usia_tahun)
	{
		if ($usia_tahun < 1) {
			if ($barulahir == 1) {
				return 1;
			} else {
				return 2;
			}
		} else if ($usia_tahun > 0 && $usia_tahun < 4) {
			return 3;
		} else if ($usia_tahun > 3 && $usia_tahun < 11) {
			return 4;
		} else if ($usia_tahun > 10 && $usia_tahun < 20) {
			return 5;
		} else if ($usia_tahun > 19 && $usia_tahun < 41) {
			return 6;
		} else if ($usia_tahun > 40 && $usia_tahun < 60) {
			return 7;
		} else {
			return 8;
		}
	}
	public function actionKuotaPasien()
	{
		$tgl = date('Y-m-d', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
		$url = Yii::$app->params['baseUrl'] . 'dashboard/pasien-rest/antrian-pasien?tgl=' . $tgl;
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		return $this->render('kuota_pasien', [
			'json' => $json
		]);
	}
	public function actionShowJadwal($tgl)
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/pasien-rest/antrian-pasien?tgl=' . $tgl;
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show-jadwal', [
			'json' => $json
		]);
	}
	function kuota_pasien($tgl, $idpoli, $iddokter)
	{
		$hari = date('N', strtotime($tgl));
		$jadwal = DokterJadwal::find()->where(['iddokter' => $iddokter])->andwhere(['idhari' => $hari])->count();
		$jadwal2 = DokterJadwal::find()->where(['iddokter' => $iddokter])->andwhere(['idhari' => $hari])->one();
		$kuotac = DokterKuota::find()->where(['iddokter' => $iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->count();
		$kuota = DokterKuota::find()->where(['iddokter' => $iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->one();

		if ($kuotac == 0) {
			$newkuota = new DokterKuota();
			$newkuota->iddokter = $iddokter;
			$newkuota->idpoli = $idpoli;
			$newkuota->idhari = $hari;
			$newkuota->tgl = $tgl;
			$newkuota->kuota = $jadwal2->kuota;
			$newkuota->sisa = $jadwal2->kuota - 1;
			$newkuota->terdaftar = 1;
			$newkuota->status = 1;
			$newkuota->save();
		} else {
			if ($kuota->sisa == 0) {
				return 1;
			} else {
				$kuota->sisa = $kuota->sisa - 1;
				$kuota->terdaftar = $kuota->terdaftar + 1;
				$kuota->save();
				return 2;
			}
		}
	}
	//Batalkan Kunjungan
	public function actionBatalKunjungan($id)
	{
		$model = RawatKunjungan::find()->where(['id' => $id])->one();
		$trx = Transaksi::find()->where(['idkunjungan' => $id])->one();
		$model->status = 5;
		if ($model->save()) {
			$trx->status = 3;
			$trx->save();
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}
	//Edit Rawat
	public function actionCekEdit($idrawat, $poli, $dokter, $tgl)
	{
		$rawat = Rawat::find()->where(['id' => $idrawat])->one();
		return $this->renderAjax('cek-edit', [
			'rawat' => $rawat,
			'poli' => $poli,
			'dokter' => $dokter,
			'tgl' => $tgl,
		]);
	}
	//list 
	public function actionListpoli($id)
	{
		$models = Poli::find()->where(['ket' => $id])->all();
		echo "<option value=''>-- Pilih Poli --</option>";
		foreach ($models as $k) {
			echo "<option value='" . $k->id . "'>" . $k->poli . "</option>";
		}
	}
	public function actionListruangan2($id)
	{
		$models = Ruangan::find()->where(['idkelas' => $id])->all();
		echo "<option value=''>-- Pilih Ruangan --</option>";
		foreach ($models as $k) {
			echo "<option value='" . $k->id . "'>" . $k->nama_ruangan . "</option>";
		}
	}
	public function actionListbed($id)
	{
		$models = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->all();
		echo "<option value=''>-- Pilih Bed --</option>";
		foreach ($models as $k) {
			echo "<option value='" . $k->id . "'>" . $k->kodebed . "</option>";
		}
	}
	public function actionListdokter($id)
	{
		$models = Dokter::find()->where(['idpoli' => $id])->all();

		foreach ($models as $k) {
			echo "<option value='" . $k->id . "'>" . $k->nama_dokter . "</option>";
		}
	}

	public function actionEditRawat($id)
	{
		if (Yii::$app->user->identity->idpriv != 8) {
			return $this->redirect(['site/index']);
		}
		$model = Rawat::find()->where(['id' => $id])->one();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$kunjungan = RawatKunjungan::find()->where(['idkunjungan' => $model->idkunjungan])->one();
		$transaksi = Transaksi::find()->where(['kode_kunjungan' => $kunjungan->idkunjungan])->one();
		$trx = TransaksiDetailRinci::find()->where(['idtransaksi' => $transaksi->id])->all();
		// $tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
		// $tglkunjungan =date('Y-m-d',strtotime($model->tglmasuk));
		//	if($tgl != $tglkunjungan ){
		//		Yii::$app->session->setFlash('warning', 'Pelayanan Tidak bisa diedit Karena tanggal sudah lewat');
		//		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		//	}
		$post = Yii::$app->request->post();
		if ($post) {

			$poli = Yii::$app->request->post('poli');
			$tglrawat = Yii::$app->request->post('tglrawat');
			$bayar = Yii::$app->request->post('bayar');
			$jenisrawat = Yii::$app->request->post('jenisrawat');
			$dokter = Yii::$app->request->post('dokter');
			$kunjungan = Yii::$app->request->post('kunjungan');
			$anggota = Yii::$app->request->post('anggota');
			$online = Yii::$app->request->post('online');
			$nosep = Yii::$app->request->post('nosep');
			$nosurat = Yii::$app->request->post('nosurat');
			$idjenisperawatan = Yii::$app->request->post('idjenisperawatan');
			$idkelas = Yii::$app->request->post('idkelas');
			$idruangan = Yii::$app->request->post('idruangan');
			$idbed = Yii::$app->request->post('idbed');
			$keterangan = Yii::$app->request->post('keterangan');
			if ($model->idjenisrawat == 2) {
				// return $dokter;
				if ($model->idbed != $idbed) {
					$bed = RuanganBed::findOne($model->idbed);
					$bed2 = RuanganBed::findOne($idbed);
					$bed->terisi = 0;
					if ($bed->save()) {
						$bed2->terisi = 1;
						$bed2->save();
					}
				}
				$ruanganrawat = RawatRuangan::find()->where(['idrawat' => $model->id])->andwhere(['status' => 1])->one();
				$ruanganrawat->tgl_masuk = $tglrawat . ' ' . date('H:i:s', strtotime('+7 hours'));
				$ruanganrawat->idruangan = $idruangan;
				$ruanganrawat->idbed = $idbed;
				$ruanganrawat->idkelas = $idkelas;
				if ($ruanganrawat->save()) {
					$model->no_sep = $nosep;
					$model->no_suratkontrol = $nosurat;
					$model->idkelas = $idkelas;
					$model->anggota = $anggota;
					$model->idbayar = $bayar;
					$model->idruangan = $idruangan;
					$model->idbed = $idbed;
					$model->idpoli = $poli;
					$model->iddokter = $dokter;
					$model->idjenisperawatan = $idjenisperawatan;
					$model->keterangan = $keterangan;
					$model->tglmasuk = $tglrawat . ' ' . date('H:i:s', strtotime('+7 hours'));;
					foreach ($trx as $trx) {
						$trx->idbayar = $model->idbayar;
						$trx->save(false);
					}
					if ($model->save()) {
						Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Data di edit');
						return $this->redirect(['pasien/' . $pasien->id]);
					}
				}
			} else if ($model->idpoli == $poli || $model->iddokter == $dokter || date('Y-m-d', strtotime($model->tglmasuk)) == $tglrawat) {
				$ckuota = DokterKuota::find()->where(['iddokter' => $model->iddokter])->andwhere(['idpoli' => $model->idpoli])->andwhere(['tgl' => date('Y-m-d', strtotime($model->tglmasuk))])->count();
				if ($ckuota > 0) {
					$kuota = DokterKuota::find()->where(['iddokter' => $model->iddokter])->andwhere(['idpoli' => $model->idpoli])->andwhere(['tgl' => date('Y-m-d', strtotime($model->tglmasuk))])->one();
					$kuota->terdaftar = $kuota->terdaftar - 1;
					$kuota->sisa = $kuota->sisa + 1;
					if ($kuota->save()) {
						$kuota_edit = DokterKuota::find()->where(['iddokter' => $dokter])->andwhere(['idpoli' => $poli])->andwhere(['tgl' => $tglrawat])->count();
						if ($kuota_edit < 1) {
							if ($jenisrawat == 1) {
								$jadwal2 = DokterJadwal::find()->where(['iddokter' => $dokter])->andwhere(['idpoli' => $poli])->andwhere(['status' => 1])->andwhere(['idhari' => date('N', strtotime($tglrawat))])->one();
								$newkuota = new DokterKuota();
								$newkuota->iddokter = $dokter;
								$newkuota->idpoli = $poli;
								$newkuota->idhari = $jadwal2->idhari;
								$newkuota->tgl = $tglrawat;
								$newkuota->kuota = $jadwal2->kuota;
								$newkuota->sisa = $jadwal2->kuota - 1;
								$newkuota->terdaftar = 1;
								$newkuota->status = 1;
								$newkuota->save();
							}
						} else {
							$kuota_update = DokterKuota::find()->where(['iddokter' => $dokter])->andwhere(['idpoli' => $poli])->andwhere(['tgl' => $tglrawat])->one();
							$kuota_update->terdaftar = $kuota_update->terdaftar + 1;
							$kuota_update->sisa = $kuota_update->sisa - 1;
							$kuota_update->save();
						}
					}
				}
				$model->iddokter = $dokter;
				$model->idpoli = $poli;
				$model->tglmasuk = $tglrawat . ' ' . date('H:i:s');
				$model->kunjungan = $kunjungan;
				$model->anggota = $anggota;
				$model->idbayar = $bayar;
				$model->online = $online;
				$model->idjenisrawat = $jenisrawat;
				$model->no_sep = $nosep;
				$model->keterangan = $keterangan;
				$model->kat_pasien = $this->getstatpasien($model->anggota, $pasien->idpekerjaan, $model->idbayar);
				foreach ($trx as $trx) {
					$trx->idbayar = $model->idbayar;
					$trx->save(false);
				}
				if ($model->save(false)) {
					Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Data di edit');
					return $this->redirect(['pasien/' . $pasien->id]);
				}
			}
		}

		return $this->render('editrawat2', [
			'model' => $model,
			'pasien' => $pasien,
		]);
	}
	//Batal Rawat
	public function actionBatalRawat($id)
	{
		if (Yii::$app->user->identity->idpriv != 8) {
			return $this->redirect(['site/index']);
		}
		$rawat = Rawat::find()->where(['id' => $id])->one();

		$trxdetail = TransaksiDetailRinci::find()->where(['idrawat' => $rawat->id])->all();
		$kunjungan = RawatKunjungan::find()->where(['idkunjungan' => $rawat->idkunjungan])->one();
		$model = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		$tgl = date('Y-m-d', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
		$tglkunjungan = date('Y-m-d', strtotime($rawat->tglmasuk));

		if ($rawat->load($this->request->post())) {
			$batal = Yii::$app->request->post('batalspri');
			if ($batal == 1) {
				$dataspri = RawatSpri::find()->where(['idrawat' => $rawat->id])->one();
				if ($dataspri) {
					$sspri = array(
						"request" => array(
							"t_suratkontrol" => array(
								"noSuratKontrol" => $dataspri->spri_bpjs,
								"user" => "coba WS"
							),
						),
					);
					// return print_r($sspri);
					$cari_spri = Yii::$app->kontrol->delete_spri($sspri);
					// return print_r($cari_spri);
				}

				// return print_r($cari_spri);
			}
			$cari_sep = Yii::$app->sep->cari_sep($rawat->no_sep);
			if ($cari_sep['metaData']['code'] == 200) {
				$sepp = array(
					"request" => array(
						"t_sep" => array(
							"noSep" => $cari_sep['response']['noSep'],
							"user" => "ssssss"
						),
					)
				);

				$response = Yii::$app->sep->delete_sep($sepp);
				// return print_r($response);
			}


			if ($rawat->idjenisrawat == 1) {
				$hari  = date('N', strtotime($rawat->tglmasuk));
				// return $hari;
				$kuota = DokterKuota::find()->where(['iddokter' => $rawat->iddokter])->andWhere(['idhari' => $hari])->andwhere(['tgl' => date('Y-m-d', strtotime($rawat->tglmasuk))])->one();
				// return $rawat->iddokter;
				$kuota->sisa = $kuota->sisa + 1;
				$kuota->terdaftar = $kuota->terdaftar - 1;
				$kuota->save(false);
			} else if ($rawat->idjenisrawat == 2) {
				$rawat_bed = RawatRuangan::find()->where(['idrawat' => $rawat->id])->all();
				$rawat_spri = RawatSpri::find()->where(['idrawat' => $rawat->id])->andwhere(['status' => 2])->all();
				// foreach($rawat_spri){

				// }
				foreach ($rawat_bed as $rb) {
					$rb->delete();
				}
				Yii::$app->kazo->updateBed($rawat->idbed);
			}
			$rawat->status = 5;

			if ($rawat->save(false)) {
				foreach ($trxdetail as $td) {
					$td->delete();
				}
				if ($rawat->taksid == 3 || $rawat->taksid == 4) {
					$taks = array(
						"kodebooking" => $rawat->idrawat,
						"taskid" => 99,
						"waktu" =>  $this->milliseconds(),
					);
					$taksid = Yii::$app->hfis->update_taks($taks);
					if ($taksid['metadata']['code'] == 200) {
						$rawat->taksid = 99;
						$rawat->save(false);
					} else {
						Yii::$app->session->setFlash('success', $taksid['metadata']['message']);
						return $this->redirect(Yii::$app->request->referrer);
					}
				}


				Yii::$app->session->setFlash('warning', "Kunjungan Dibatalkan taks id 99");
				return $this->redirect(['pasien/' . $rawat->pasien->id]);
			}
		}
		return $this->render('batal-rawat', [
			'model' => $model,
			'rawat' => $rawat,
		]);


		return $this->render('batal-rawat', [
			'model' => $model,
			'rawat' => $rawat,
		]);
	}
	public function actionShowLayanan($poli, $bayar, $jenis, $kunjungan)
	{
		$dokter = DokterJadwal::find()->where(['idpoli' => $poli])->andWhere(['idhari' => date('N', strtotime($kunjungan))])->all();
		return $this->renderAjax('show-layanan-bpjs', [
			'dokter' => $dokter,
			'kunjungan' => $kunjungan,
		]);
	}
	public function actionSepPostRanap($id)
	{
		$time = date('Y-m-d');
		$model = Rawat::findOne($id);
		$jaminan = new RawatJaminanBpjs();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$response = Yii::$app->vclaim->get_peserta($pasien->no_bpjs, $time);
		$kontrol = new RawatKontrol();
		if ($model->load($this->request->post()) && $jaminan->load($this->request->post())) {
			$icdx = explode(" - ", $model->icdx);
			$kode = $icdx[0];
			$nama = $icdx[1];
			$noskpd = Yii::$app->request->post('noskpd');
			$kddpjs_kontrol = Yii::$app->request->post('kddpjs_kontrol');
			$tglsep = Yii::$app->request->post('tglsep');
			$no_sepasal = Yii::$app->request->post('no_sepasal');
			$tglsepasal = Yii::$app->request->post('tglsepasal');
			$sep = array(
				"request" => array(
					"t_sep" => array(
						"noKartu" => $pasien->no_bpjs,
						"tglSep" => $tglsep,
						"ppkPelayanan" => '0120R012',
						"jnsPelayanan" => "2",
						"klsRawat" => array(
							"klsRawatHak" => "", //$response['peserta']['hakKelas']['kode'],
							"klsRawatNaik" => "",
							"pembiayaan" => "",
							"penanggungJawab" => ""
						),
						"noMR" => $pasien->no_rm,
						"rujukan" => array(
							"asalRujukan" => "2",
							"tglRujukan" => $tglsepasal,
							"noRujukan" => $no_sepasal,
							"ppkRujukan" => "0120R012"
						),
						"catatan" => "-",
						"diagAwal" => $kode,
						"poli" => array(
							"tujuan" => $model->poli->kode,
							"eksekutif" => "0"
						),
						"cob" => array(
							"cob" => "0"
						),
						"katarak" => array(
							"katarak" => "0",
						),
						"jaminan" => array(
							"lakaLantas" => $model->jaminan,
							"noLP" => $jaminan->noLp,
							"penjamin" => array(
								"tglKejadian" => $jaminan->tglkejadian,
								"keterangan" => $jaminan->keterangan,
								"suplesi" => array(
									"suplesi" => $jaminan->suplesi,
									"noSepSuplesi" => $jaminan->nosep_suplesi,
									"lokasiLaka" => array(
										"kdPropinsi" => $jaminan->propinsi,
										"kdKabupaten" => $jaminan->kabupaten,
										"kdKecamatan" => $jaminan->kecamatan
									)
								)
							)
						),
						"tujuanKunj" => "0",
						"flagProcedure" => "",
						"kdPenunjang" => "",
						"assesmentPel" => "",
						"skdp" => array(
							"noSurat" => $noskpd,
							"kodeDPJP" => $kddpjs_kontrol
						),
						"dpjpLayan" => $model->dokter->kode_dpjp,
						"noTelp" => $pasien->nohp,
						"user" => "Coba Ws",
					),

				)
			);
			// return print_r($sep);
			$sep_post = Yii::$app->sep->post_sep_online($sep);
			$model->no_sep = $sep_post['response']['sep']['noSep'];
			$model->save(false);
			if ($sep_post['metaData']['code'] == 200) {
				Yii::$app->session->setFlash('success', 'No SEP : ' . $sep_post['response']['sep']['noSep']);
			} else {
				Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $sep_post['metaData']['message']);
			}

			return $this->redirect(['pasien/' . $pasien->id]);
		} else if ($kontrol->load($this->request->post())) {
			$sep_kontrol = array(
				'request' => array(
					'noSEP' => $kontrol->no_sep,
					'kodeDokter' => $kontrol->kode_dokter,
					'poliKontrol' => $model->poli->kode,
					'tglRencanaKontrol' => $kontrol->tgl_kontrol,
					'user' => 'RM Sulaiman',
				),
			);
			
			// return print_r($sep_kontrol);
			$post_kontrol = Yii::$app->kontrol->post_kontrol($sep_kontrol);
			if ($post_kontrol['metaData']['code'] == 200) {
				$kontrol->idrawat = $id;
				$kontrol->no_rm = $model->no_rm;
				//$kontrol->no_sep = $rawat->no_sep;
				$kontrol->idpoli = $model->idpoli;
				$kontrol->tgl_buat = date('Y-m-d');
				$kontrol->no_surat = $post_kontrol['response']['noSuratKontrol'];
				Yii::$app->session->setFlash('success', 'No Surat ' . $post_kontrol['response']['noSuratKontrol']);
				$kontrol->save();
			} else {
				return print_r($post_kontrol);
			}
		}
		return $this->render('sep-post-ranap', [
			'model' => $model,
			'pasien' => $pasien,
			'jaminan' => $jaminan,
			'kontrol' => $kontrol,
		]);
	}
	//Kunjungan Rawat
	public function actionRawatKunjungan($id)
	{
		$kunjungan = RawatKunjungan::find()->where(['id' => $id])->one();
		$pindah = RawatPermintaanPindah::find()->where(['idrawat' => $id])->one();
		$trx = Transaksi::find()->where(['idkunjungan' => $kunjungan->id])->one();
		$trxdetail = new TransaksiDetail();
		$model = Pasien::find()->where(['no_rm' => $kunjungan->no_rm])->one();
		$pelayanan = new Rawat();
		$ruangan = new RawatRuangan();
		$kode = $model->no_bpjs;
		$time = date('Y-m-d');
		$tgl = date('Y-m-d');
		$hari = date('N', strtotime($tgl));
		// $response= Yii::$app->vclaim->get_peserta($kode,$time);
		//$data_json=json_decode($response, true);
		// $peserta = $response['response'];
		// $bpjs = $peserta['peserta'];
		if ($pelayanan->load($this->request->post())) {
			if ($pelayanan->iddokter == null) {
				Yii::$app->session->setFlash('danger', 'Gagal Simpan , Data tidak lengkap');
				return $this->refresh();
			}
			if ($pelayanan->idjenisrawat == 1) {
				$pelayanan->status = 1;
				$pelayanan->idruangan = 2;
				$pelayanan->genAntri($pelayanan->idpoli, $pelayanan->iddokter, $pelayanan->anggota, $kunjungan->tgl_kunjungan);
				$jadwal = DokterJadwal::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->count();
				$jadwal2 = DokterJadwal::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->one();
				$kuotac = DokterKuota::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->count();
				$kuota = DokterKuota::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->one();
				if ($jadwal == 0) {
					Yii::$app->session->setFlash('danger', 'Jadwal Dokter Tidak ditemukan');
					return $this->refresh();
				} else {
					if ($kuotac == 0) {
						$newkuota = new DokterKuota();
						$newkuota->iddokter = $pelayanan->iddokter;
						$newkuota->idpoli = $pelayanan->idpoli;
						$newkuota->idhari = $hari;
						$newkuota->tgl = $tgl;
						$newkuota->kuota = $jadwal2->kuota;
						$newkuota->sisa = $jadwal2->kuota - 1;
						$newkuota->terdaftar = 1;
						$newkuota->status = 1;
						$newkuota->save();
					} else {
						if ($kuota->sisa == 0) {
							Yii::$app->session->setFlash('danger', 'Kuota Habis');
							return $this->refresh();
						} else {
							$kuota->sisa = $kuota->sisa - 1;
							$kuota->terdaftar = $kuota->terdaftar + 1;
							$kuota->save();
						}
					}
				}
				$tindakan = Tindakan::findOne(15);
				$tarif = TindakanTarif::find()->where(['idtindakan' => $tindakan->id])->andWhere(['idbayar' => $pelayanan->idbayar])->one();
				$trxdetail->idjenispelayanan = 6;
			} else if ($pelayanan->idjenisrawat == 3) {
				$pelayanan->status = 1;
				$pelayanan->idruangan = 1;
				$tindakan = Tindakan::findOne(16);
				$tarif = TindakanTarif::find()->where(['idtindakan' => $tindakan->id])->andWhere(['idbayar' => $pelayanan->idbayar])->one();
				$trxdetail->idjenispelayanan = 15;
			} else {
				// if($pelayanan->idkelas == 1){
				// $tindakan = Tindakan::findOne(38);
				// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$pelayanan->idbayar])->one();
				// $trxdetail->idjenispelayanan = 7;
				// }else if($pelayanan->idkelas == 2){
				// $tindakan = Tindakan::findOne(39);
				// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$pelayanan->idbayar])->one();
				// $trxdetail->idjenispelayanan = 7;
				// }else{
				// $tindakan = Tindakan::findOne(40);
				// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$pelayanan->idbayar])->one();
				// $trxdetail->idjenispelayanan = 7;
				// }
				$bed = RuanganBed::find()->where(['id' => $pelayanan->idbed])->one();
				$bed->terisi = 1;
				$pelayanan->status = 2;
				$pelayanan->idruangan = $pelayanan->idruangan;
				$kunjungan->status = 2;

				$kunjungan->save(false);
				$bed->save(false);
			}
			$tanggal = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
			$date1 = date_create($model->tgllahir);
			$date2 = date_create($tanggal);
			$diff = date_diff($date1, $date2);
			$model->usia_tahun = $diff->format("%y");
			$model->usia_bulan = $diff->format("%m");
			$model->usia_hari = $diff->format("%d");
			$kunjungan->usia_kunjungan = $diff->format("%y");
			$pelayanan->genKode($pelayanan->idjenisrawat);
			$pelayanan->tglmasuk = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
			$pelayanan->iduser = Yii::$app->user->identity->id;
			$pelayanan->kat_pasien = $this->getstatpasien($pelayanan->anggota, $model->idpekerjaan, $pelayanan->idbayar);

			//$kuota = $this->kuota_pasien($time,$pelayanan->idpoli,$pelayanan->iddokter);
			if ($pelayanan->save(false)) {
				if ($pelayanan->idjenisrawat == 2) {
					$ruangan->idkunjungan = $kunjungan->id;
					$ruangan->idrawat = $pelayanan->id;
					$ruangan->no_rm = $kunjungan->no_rm;
					$ruangan->idruangan = $pelayanan->idruangan;
					$ruangan->idbayar = $pelayanan->idbayar;
					$ruangan->tgl_masuk = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
					$ruangan->status = 1;
					$ruangan->asal = $pelayanan->jenisrawat->jenis;
					$ruangan->save(false);
				}
				if ($pelayanan->idjenisrawat != 2) {
					$trxdetail->idtransaksi = $trx->id;
					$trxdetail->idrawat = $pelayanan->id;
					$trxdetail->idkunjungan = $kunjungan->id;
					$trxdetail->idpelayanan = $tindakan->id;
					$trxdetail->tgl = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
					$trxdetail->nama_tindakan = $tindakan->nama_tindakan;
					$trxdetail->tarif = $tarif->tarif;
					$trxdetail->jumlah = 1;
					$trxdetail->status = 1;
					$trxdetail->total = $tarif->tarif * $trxdetail->jumlah;
					$trxdetail->jenis = $pelayanan->idjenisrawat;
					$trxdetail->idtindakan = $tarif->id;
					$trxdetail->idbayar = $pelayanan->idbayar;
					$trxdetail->save(false);
				}

				$model->save(false);
				$kunjungan->save(false);
				return $this->refresh();
			} else {
				return $this->render('rawat-kunjungan', [
					'kunjungan' => $kunjungan,
					'model' => $model,
					'pelayanan' => $pelayanan,
					// 'bpjs'=>$bpjs,
					'pindah' => $pindah,
				]);
			}
		}
		return $this->render('rawat-kunjungan', [
			'kunjungan' => $kunjungan,
			'model' => $model,
			'pelayanan' => $pelayanan,
			// 'bpjs'=>$bpjs,
			'pindah' => $pindah,
		]);
	}

	public function actionListRuangan($id)
	{
		$models = Ruangan::find()->where(['idkelas' => $id])->all();

		foreach ($models as $k) {
			echo "<option value='" . $k->id . "'>" . $k->nama_ruangan . "</option>";
		}
	}
	public function actionShowPelayanan($id)
	{
		$rawat = Rawat::find()->where(['id' => $id])->one();
		$soapdr = SoapRajaldokter::find()->where(['idrawat' => $rawat->id])->one();
		$soappr = SoapRajalperawat::find()->where(['idrawat' => $rawat->id])->one();
		$soapicdx = SoapRajalicdx::find()->where(['idrawat' => $rawat->id])->all();
		$soaplab = SoapLab::find()->where(['idrawat' => $rawat->id])->all();
		$soaprad = SoapRadiologi::find()->where(['idrawat' => $rawat->id])->all();
		$soapobat = SoapRajalobat::find()->where(['idrawat' => $rawat->id])->all();
		return $this->renderAjax('show-pelayanan', [
			'rawat' => $rawat,
			'soapdr' => $soapdr,
			'soappr' => $soappr,
			'soapicdx' => $soapicdx,
			'soaplab' => $soaplab,
			'soaprad' => $soaprad,
			'soapobat' => $soapobat,
		]);
	}
	public function actionShowRuangan($id)
	{
		$pelayanan = new Rawat();
		$ruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->all();
		$cruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->count();;
		return $this->renderAjax('show-ruangan', [
			'ruangan' => $ruangan,
			'pelayanan' => $pelayanan,
			'cruangan' => $cruangan,
		]);
	}
	public function actionShowRuanganPindah($id)
	{
		$rawat = new Rawat();
		$ruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->all();
		$cruangan = RuanganBed::find()->where(['idruangan' => $id])->andwhere(['terisi' => 0])->count();;
		return $this->renderAjax('show-ruangan-pindah', [
			'ruangan' => $ruangan,
			'cruangan' => $cruangan,
			'rawat' => $rawat,
		]);
	}

	public function actionShowDokter($id, $rm, $jenis, $kunjungan)
	{
		$pelayanan = new Rawat();
		$model = Pasien::find()->where(['no_rm' => $rm])->one();
		$dokter = DokterJadwal::find()->where(['idpoli' => $id])->andWhere(['idhari' => date('N', strtotime($kunjungan))])->all();
		$cdokter = DokterJadwal::find()->where(['idpoli' => $id])->andWhere(['idhari' => date('N', strtotime($kunjungan))])->count();
		$cekrajal = Rawat::find()->where(['no_rm' => $rm])->andWhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => $kunjungan])->andwhere(['<>', 'status', '5'])->count();
		return $this->renderAjax('show-dokter', [
			'dokter' => $dokter,
			'cdokter' => $cdokter,
			'pelayanan' => $pelayanan,
			'cekrajal' => $cekrajal,
			'jenis' => $jenis,
			'kunjungan' => $kunjungan,
			'model' => $model,
		]);
	}

	public function actionGetDokter()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$model = Dokter::find()->where(['id' => $kode])->one();
		} else {
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
	}

	public function actionGetBed()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$model = RuanganBed::find()->where(['id' => $kode])->one();
		} else {
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
	}
	public function actionGetRanap()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$model = Rawat::find()->where(['id' => $kode])->one();
		} else {
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
	}
	public function actionView($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/index']);
		}
		$trx = new Transaksi();
		$model = $this->findModel($id);
		$list_rawat = Rawat::find()->where(['no_rm' => $model->no_rm])->orderBy(['tglmasuk' => SORT_DESC])->limit(30)->all();
		$list_kunjungan = RawatKunjungan::find()->where(['no_rm' => $model->no_rm])->andwhere(['<>', 'status', 5])->orderBy(['tgl_kunjungan' => SORT_DESC])->limit(5)->all();
		$kode = $model->no_bpjs;
		$time = date('Y-m-d');
		$kunjungan = new RawatKunjungan();
		$trxdetail = new TransaksiDetail();
		$pelayanan = new Rawat();
		$kontrol = new RawatKontrol();

		$response = Yii::$app->kazo->getUpdateusia($id);
		// $peserta = $response['response'];
		// $bpjs = $peserta['peserta'];
		if ($pelayanan->load($this->request->post())) {
			
			// return $data_json['jadwal'];
			// $response= Yii::$app->hfis->get_jadwaldokter($pelayanan->poli->kode,date('Y-m-d',strtotime($pelayanan->tglmasuk)));
			// $cek= Yii::$app->hfis->searchForId($pelayanan->dokter->kode_dpjp,$response);
			// $data_json = json_decode($cek, true);
			// $jam = explode("-", $data_json['jadwal']);
			// $jam_buka = $jam[0];
			// date_default_timezone_set("Asia/Jakarta");
			// $tambah = (45*3) ;
			// $jam_dilayani = date('H:i:s',strtotime($jam_buka));
			// $dilayani = date('Y-m-d H:i:s',strtotime('+'.$tambah.' minutes',strtotime($pelayanan->tglmasuk.' '.$jam_dilayani)));

			//$tes = strtotime($dilayani,$plus);
			//return $dilayani;
			$tgl = date('Y-m-d', strtotime($pelayanan->tglmasuk));
			$hari = date('N', strtotime($pelayanan->tglmasuk));
			$kunjungan = RawatKunjungan::find()->where(['no_rm' => $model->no_rm])->andwhere(['tgl_kunjungan' => date('Y-m-d', strtotime($pelayanan->tglmasuk))])->andwhere(['<>', 'status', 5])->one();
			$tanggal = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
			$date1 = date_create($model->tgllahir);
			$date2 = date_create($tanggal);
			$diff = date_diff($date1, $date2);
			if ($kunjungan) {
				$pelayanan->idkunjungan = $kunjungan->idkunjungan;
				$kunjungan->usia_kunjungan = $diff->format("%y");
				$trxdetail->idkunjungan = $kunjungan->id;
			} else {
				$new_kunjungan = new RawatKunjungan();
				$new_kunjungan->genKode();
				$new_kunjungan->iduser = Yii::$app->user->identity->id;
				$new_kunjungan->usia_kunjungan = $model->usia_tahun;
				$new_kunjungan->no_rm = $model->no_rm;
				$new_kunjungan->idpasien = $model->id;
				$new_kunjungan->tgl_kunjungan = date('Y-m-d', strtotime($pelayanan->tglmasuk));
				$new_kunjungan->jam_kunjungan = date('G:i:s', strtotime('+7 hour', strtotime(date('G:i:s'))));
				$new_kunjungan->usia_kunjungan = $diff->format("%y");
				$new_kunjungan->status = 1;
				$trx->genKode();
				if ($new_kunjungan->save(false)) {
					$trxdetail->idkunjungan = $new_kunjungan->id;
					$trx->idkunjungan = $new_kunjungan->id;
					$trx->kode_kunjungan = $new_kunjungan->idkunjungan;
					$trx->no_rm = $model->no_rm;
					$trx->tgltransaksi = $new_kunjungan->tgl_kunjungan . ' ' . $new_kunjungan->jam_kunjungan;
					$trx->tgl_masuk = $new_kunjungan->tgl_kunjungan;
					$trx->status = 1;
					$trx->save(false);
				}
				$pelayanan->idkunjungan = $new_kunjungan->idkunjungan;
			}
			if ($pelayanan->idjenisrawat == 1) {
				$pelayanan->status = 1;
				$pelayanan->idruangan = 2;
				$pelayanan->genAntri($pelayanan->idpoli, $pelayanan->iddokter, $pelayanan->anggota, date('Y-m-d', strtotime($pelayanan->tglmasuk)));
				$jadwal = DokterJadwal::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->count();
				$jadwal2 = DokterJadwal::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->one();
				$kuotac = DokterKuota::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->count();
				$kuota = DokterKuota::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->one();
				$model->usia_tahun = $diff->format("%y");
				$model->usia_bulan = $diff->format("%m");
				$model->usia_hari = $diff->format("%d");
				$model->kunjungan_terakhir = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
				$pelayanan->tglmasuk = $pelayanan->tglmasuk . ' ' . date('H:i:s', strtotime('+7 hour', strtotime(date('H:i:s'))));
				$pelayanan->genKode($pelayanan->idjenisrawat);
				$pelayanan->status = 1;

				$pelayanan->kat_pasien = $this->getstatpasien($pelayanan->anggota, $model->idpekerjaan, $pelayanan->idbayar);
				$pelayanan->iduser = Yii::$app->user->identity->id;
				if ($jadwal == 0) {
					Yii::$app->session->setFlash('danger', 'Jadwal Dokter Tidak ditemukan');
					return $this->refresh();
				} else {
					if ($kuotac == 0) {
						$newkuota = new DokterKuota();
						$newkuota->iddokter = $pelayanan->iddokter;
						$newkuota->idpoli = $pelayanan->idpoli;
						$newkuota->idhari = $hari;
						$newkuota->tgl = $tgl;
						$newkuota->kuota = $jadwal2->kuota;
						$newkuota->sisa = $jadwal2->kuota - 1;
						$newkuota->terdaftar = 1;
						$newkuota->status = 1;
						$newkuota->save();
					} else {
						if ($kuota->sisa == 0) {
							Yii::$app->session->setFlash('danger', 'Kuota Habis');
							return $this->refresh();
						} else {
							$kuota->sisa = $kuota->sisa - 1;
							$kuota->terdaftar = $kuota->terdaftar + 1;
							$kuota->save();
						}
					}
					$kuota2 = DokterKuota::find()->where(['iddokter' => $pelayanan->iddokter])->andwhere(['idhari' => $hari])->andwhere(['tgl' => $tgl])->one();
					if ($pelayanan->idbayar == 2) {
					}
				}
				// $tindakan = Tindakan::findOne(15);
				// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$pelayanan->idbayar])->one();
				// $trxdetail->idjenispelayanan = 6;
			} else if ($pelayanan->idjenisrawat == 3) {
				$pelayanan->status = 1;
				$pelayanan->idruangan = 1;
				$tindakan = Tindakan::findOne(16);
				$pelayanan->tglmasuk = $pelayanan->tglmasuk . ' ' . date('H:i:s', strtotime('+7 hour', strtotime(date('G:i:s'))));
				$pelayanan->genKode($pelayanan->idjenisrawat);
				// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$pelayanan->idbayar])->one();
				// $trxdetail->idjenispelayanan = 15;
			}
			if ($pelayanan->idbayar == 2) {
				if ($pelayanan->idjenisrawat == 1) {
					$response = Yii::$app->hfis->get_jadwaldokter($pelayanan->poli->kode, date('Y-m-d', strtotime($pelayanan->tglmasuk)));
					if(isset($response['metaData'])){
					    if ($response['metaData']['code'] == 201) {
    						Yii::$app->session->setFlash('error', 'Gagal jadwal dokter tidak ada di hfis antrian gagal dikirim');
    				// 		return $this->refresh();
    					}
					}else{
					    Yii::$app->session->setFlash('error', 'Gagal jadwal dokter tidak ada di hfis antrian gagal dikirim');
    				// 		return $this->refresh();
					}
					
				}
			}

			if ($pelayanan->save(false)) {
				if ($pelayanan->idjenisrawat == 1) {
					if ($pelayanan->idbayar == 2) {
						$jenis_pasien = 'JKN';
						$bpjs = $model->no_bpjs;
						$nomorreferensi = '017100050222P000524';
					} else {
						$jenis_pasien = 'NON JKN';
						$bpjs = '';
					}
					if ($pelayanan->kunjungan == 1) {
						$kunjungan = 1;
						// $nomorreferensi = $pelayanan->no_rujukan ;
					} else {
						$kunjungan = 3;
						$nomorreferensi = '017100050222P000524';
						//$pelayanan->no_rujukan = $nomorreferensi;
					}
					$pelayanan->taksid = 3;
					if ($model->nohp == null) {
						$model->nohp = '089777453673778';
					}
					$response = Yii::$app->hfis->get_jadwaldokter($pelayanan->poli->kode, date('Y-m-d', strtotime($pelayanan->tglmasuk)));
				// 	return print_r($response);
					if(isset($response['response'])){
					$cek = Yii::$app->hfis->searchForId($pelayanan->dokter->kode_dpjp, $response['response']);
					$data_json = json_decode($cek, true);
					$jam = explode("-", $data_json['jadwal']);
					$jam_buka = $jam[0];
					date_default_timezone_set("Asia/Jakarta");
					$angantrean = (int) ltrim(substr($pelayanan->no_antrian, -3), '0');
					$tambah = (25 * $angantrean);
					date_default_timezone_set("Asia/Jakarta");
					$tanggalperiksa = date('Y-m-d', strtotime($pelayanan->tglmasuk));
					$jam_dilayani = date("H:i:s", strtotime("+" . $tambah . " minutes", strtotime($jam_buka)));
					$dilayani = date('Y-m-d H:i:s', strtotime($tanggalperiksa . ' ' . $jam_dilayani));
					$fix = strtotime($dilayani, strtotime('-7 hour')) . '000';
					$dokter_kuota = DokterKuota::find()->where(['tgl' => date('Y-m-d', strtotime($pelayanan->tglmasuk))])->andwhere(['iddokter' => $pelayanan->iddokter])->andwhere(['idpoli' => $pelayanan->idpoli])->one();
					// return $data_json;
					$addAntri = array(
						"kodebooking" => $pelayanan->idrawat,
						"jenispasien" => $jenis_pasien,
						"nomorkartu" => $bpjs,
						"nik" => $model->nik,
						"nohp" => $model->nohp,
						"kodepoli" => $pelayanan->poli->kode,
						"namapoli" => $pelayanan->poli->poli,
						"pasienbaru" => 0,
						"norm" => $pelayanan->no_rm,
						"tanggalperiksa" => date('Y-m-d', strtotime($pelayanan->tglmasuk)),
						"kodedokter" => $pelayanan->dokter->kode_dpjp,
						"namadokter" => $pelayanan->dokter->nama_dokter,
						"jampraktek" => $data_json['jadwal'],
						"jeniskunjungan" => $kunjungan,
						"nomorreferensi" => '',
						"nomorantrean" => $pelayanan->poli->kode_antrean . '-' . substr($pelayanan->no_antrian, -3),
						"angkaantrean" => (int) ltrim(substr($pelayanan->no_antrian, -3), '0'),
						"estimasidilayani" => (int) $fix,
						"sisakuotajkn" =>  $dokter_kuota->sisa,
						"kuotajkn" => $dokter_kuota->kuota,
						"sisakuotanonjkn" => $dokter_kuota->sisa,
						"kuotanonjkn" => $dokter_kuota->kuota,
						"keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi.",
					);
					if ($pelayanan->idbayar == 1) {
						$antrian = Yii::$app->hfis->add_antrian($addAntri);
						$hitung_rawat = Rawat::find()->where(['no_rm' => $pelayanan->no_rm])->andwhere(['<>', 'status', 5])->count();
						if ($hitung_rawat < 2) {
							$taks = array(
								"kodebooking" => $pelayanan->idrawat,
								"taskid" => 1,
								"waktu" =>  $this->milliseconds(),
							);
							$taksid = Yii::$app->hfis->update_taks($taks);
							if ($taksid['metadata']['code'] == 200) {
								$taks2 = array(
									"kodebooking" => $pelayanan->idrawat,
									"taskid" => 2,
									"waktu" =>  $this->milliseconds(),
								);
								$taksid2 = Yii::$app->hfis->update_taks($taks2);
								if ($taksid2['metadata']['code'] == 200) {
									$taks3 = array(
										"kodebooking" => $pelayanan->idrawat,
										"taskid" => 3,
										"waktu" =>  $this->milliseconds(),
									);
									$taksid3 = Yii::$app->hfis->update_taks($taks3);
								}
							}
						} else {
							$taks = array(
								"kodebooking" => $pelayanan->idrawat,
								"taskid" => 3,
								"waktu" =>  $this->milliseconds(),
							);
							$taksid = Yii::$app->hfis->update_taks($taks);
						}
						$pelayanan->timecheckin = $fix;
					}

					$pelayanan->save(false);
					// return $pelayanan->idrawat;

					}
				
				}
				$tarif = Tarif::findOne(11);
				$tarif_trx = new TransaksiDetailRinci();
				$tarif_trx->idrawat = $pelayanan->id;
				$tarif_trx->idpaket = 0;
				$tarif_trx->idtransaksi = $trx->id;
				$tarif_trx->idtarif = $tarif->id;
				$tarif_trx->idbayar = $pelayanan->idbayar;
				$tarif_trx->iddokter = $pelayanan->iddokter;
				$tarif_trx->tarif = $tarif->tarif;
				$tarif_trx->tgl = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
				$tarif_trx->save(false);
				$model->save(false);
				Yii::$app->session->setFlash('success', 'Kode Booking ' . $pelayanan->idrawat . ', Antrian ' . $pelayanan->poli->kode_antrean . '-' . substr($pelayanan->no_antrian, -3) . ', Taks Id 3 (akhir waktu layan admisi/mulai waktu tunggu poli)');
				return $this->refresh();
			}
		}
		return $this->render('view', [
			'model' => $model,
			'list_rawat' => $list_rawat,
			'kunjungan' => $kunjungan,
			//'bpjs' => $bpjs,
			'list_kunjungan' => $list_kunjungan,
			'pelayanan' => $pelayanan,
			'kontrol' => $kontrol,
		]);
	}
	//Update Alamat
	public function actionEditAlamat($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}

		$model = $this->findAlamat($id);
		$pasien = Pasien::find()->where(['id' => $model->idpasien])->one();
		if ($model->load($this->request->post())) {
			$pasien->idkelurahan = $model->idkel;
			if ($model->save(false)) {
				$pasien->save(false);
				return $this->redirect(['pasien/' . $model->idpasien]);
			}
		}

		return $this->render('edit-alamat', [
			'model' => $model,
		]);
	}
	//Delete Alamat
	public function actionDeleteAlamat($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$this->findAlamat($id)->delete();

		return $this->redirect(Yii::$app->request->referrer);
	}
	//Tambah Alamat
	public function actionTambahAlamat($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		if (Yii::$app->user->identity->idpriv != 8) {
			return $this->redirect(['site/index']);
		}
		$model = $this->findModel($id);
		$alamat = new PasienAlamat();
		if ($this->request->isPost) {
			if ($alamat->load($this->request->post())) {
				$alamat->idpasien = $model->id;
				$response = Yii::$app->kazo->content_noid('https://simrs.rsausulaiman.com/apites/alamat-id?q=' . $alamat->idkel);
				$alm = json_decode($response, true);
				$alamat->idprov = $alm['response']['IdProv'];
				$alamat->idkab = $alm['response']['IdKab'];
				$alamat->idkec = $alm['response']['IdKec'];
				if ($alamat->save(false)) {
					return $this->redirect(['pasien/' . $model->id]);
				}
			}
		}
		return $this->render('tambah-alamat', [
			'model' => $model,
			'alamat' => $alamat,
		]);
	}
	//Update PAsien
	public function actionEditPasien($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		if (Yii::$app->user->identity->idpriv != 8) {
			return $this->redirect(['site/index']);
		}
		$model = $this->findModel($id);
		$modelstatus = PasienStatus::find()->where(['idpasien' => $model->id])->one();
		$transaksi = Transaksi::find()->where(['no_rm' => $model->no_rm])->all();
		$rawat = Rawat::find()->where(['no_rm' => $model->no_rm])->all();
		if ($model->load($this->request->post())) {
			$transaksi = Transaksi::find()->where(['no_rm' => $model->no_rm])->all();
			$rawat = Rawat::find()->where(['no_rm' => $model->no_rm])->all();
			if ($model->kodepasien == null) {
				$model->genKode();
			}
			$model->idusia = $this->jenjang_usia($model->barulahir, $model->usia_tahun);
			$model->no_rm = substr($model->kodepasien, 2);
			$tanggal = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));
			$date1 = date_create($model->tgllahir);
			$date2 = date_create($tanggal);
			$diff = date_diff($date1, $date2);
			$model->usia_tahun = $diff->format("%y");
			$model->usia_bulan = $diff->format("%m");
			$model->usia_hari = $diff->format("%d");
			$model->tgldaftar = date('Y-m-d H:i:s');
			$modelstatus->nrp = $model->nrp;
			$modelstatus->kesatuan = $model->kesatuan;
			$modelstatus->pangkat = $model->pangkat;
			if ($model->save(false)) {
				foreach ($transaksi as $trx) {
					$trx->no_rm = $model->no_rm;
					$trx->save(false);
				}
				foreach ($rawat as $r) {
					$r->no_rm = $model->no_rm;
					$r->save(false);
				}
				$modelstatus->save(false);
				return $this->redirect(['pasien/' . $model->id]);
			}
		}

		return $this->render('edit-pasien', [
			'model' => $model,
			'modelstatus' => $modelstatus,
		]);
	}
	//Create PAsien
	public function actionCreate()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		if (Yii::$app->user->identity->idpriv != 8) {
			return $this->redirect(['site/index']);
		}
		$model = new Pasien();
		$modelalamat = new PasienAlamat();
		$modelstatus = new PasienStatus();

		if ($model->load($this->request->post()) && $modelalamat->load($this->request->post()) && $modelstatus->load($this->request->post()) && Model::validateMultiple([$model])) {
			if ($modelstatus->idstatus == '') {
				return $this->refresh();
			}
			if ($model->kodepasien == null) {
				$model->genKode();
			}
			$model->no_rm = substr($model->kodepasien, 2);
			$cek_rm = Pasien::find()->where(['no_rm' => $model->no_rm])->count();
			if ($cek_rm > 0) {
				$rm_pasien =  (substr($model->no_rm, 1));
				$norm = $rm_pasien + 1;
				$rm = 0;
				$model->no_rm = $rm . $norm;
				$model->kodepasien = 'P-' . $model->no_rm;
			}
			if ($modelalamat->idkel == null) {
				Yii::$app->session->setFlash('danger', 'Kelurahan tidak boleh kosong');
				return $this->refresh();
			}
			if ($model->nik == null) {
				date_default_timezone_set('UTC');
				$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
				$model->nik = $tStamp . $model->no_rm;
			}
			if ($model->no_bpjs == null) {
				date_default_timezone_set('UTC');
				$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
				$model->no_bpjs = $tStamp . $model->no_rm;
			}
			$response = Yii::$app->kazo->content_noid('https://simrs.rsausulaiman.com/apites/alamat-id?q=' . $modelalamat->idkel);
			$alamat = json_decode($response, true);

			$model->iduser = Yii::$app->user->identity->id;


			$tanggal = date('Y-m-d H:i:s', strtotime('+7 hour', strtotime(date('Y-m-d H:i:s'))));

			$date1 = date_create($model->tgllahir);
			$date2 = date_create($tanggal);
			$diff = date_diff($date1, $date2);
			$model->usia_tahun = $diff->format("%y");
			$model->usia_bulan = $diff->format("%m");
			$model->usia_hari = $diff->format("%d");
			$model->tgldaftar = date('Y-m-d G:i:s', strtotime('+7 hour', strtotime(date('Y-m-d G:i:s'))));
			$model->kunjungan_terakhir = date('Y-m-d', strtotime('+7 hour', strtotime(date('Y-m-d'))));
			$model->jamdaftar = date('G:i:s', strtotime('+7 hour', strtotime(date('G:i:s'))));
			$model->status = 1;
			$model->idusia = $this->jenjang_usia($model->barulahir, $model->usia_tahun);

			$modelalamat->idprov = $alamat['response']['IdProv'];
			$modelalamat->idkab = $alamat['response']['IdKab'];
			$modelalamat->idkec = $alamat['response']['IdKec'];
			$modelalamat->utama = 1;
			$model->nrp = $modelstatus->nrp;
			$model->kesatuan = $modelstatus->kesatuan;
			$model->pangkat = $modelstatus->pangkat;
			$model->status_pasien = $modelstatus->idstatus;
			$model->idkelurahan = $modelalamat->idkel;
			if ($model->save(false)) {
				$modelstatus->idpasien = $model->id;
				$modelalamat->idpasien = $model->id;
				$modelalamat->save(false);
				$modelstatus->save(false);
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				Yii::$app->session->setFlash('danger', 'Data Gagal Tersimpans');
				return $this->refresh();
			}
		}


		return $this->render('create', [
			'model' => $model,
			'modelalamat' => $modelalamat,
			'modelstatus' => $modelstatus,
		]);
	}
	//sep4
	public function actionShowSkpd($bulan, $tahun, $nokartu, $filter, $idrawat)
	{
		$rawat = Rawat::findOne($idrawat);
		$list_skpd = Yii::$app->kontrol->get_kontrol_noka($bulan, $tahun, $nokartu, $filter);
		return $this->renderAjax('list-skpd', [
			'list_skpd' => $list_skpd,
			'rawat' => $rawat,
		]);
	}
	public function actionShowKunjungan($awal, $akhir, $nokartu, $idrawat)
	{
		$monitoring = Yii::$app->monitoring->get_historipel($nokartu, $awal, $akhir);
		$rawat = Rawat::findOne($idrawat);
		return $this->renderAjax('kunjunganMonitor', [
			'monitoring' => $monitoring,
			'rawat' => $rawat,
		]);
	}
	public function actionShowPoli($sep, $tgl, $idrawat)
	{
		$rawat = Rawat::findOne($idrawat);
		$dataPoli = Yii::$app->kontrol->get_data_poli(2, $sep, $tgl);
		$dataDokter = Yii::$app->kontrol->get_data_dokter(2, $rawat->poli->kode, $tgl);
		return $this->renderAjax('kunjunganPoli', [
			'rawat' => $rawat,
			'dataPoli' => $dataPoli,
			'dataDokter' => $dataDokter,
		]);
	}
	// public function actionSep($id,$rujukan,$tgl,$poli,$faskes=''){
	public function actionSep($id, $rujukan, $faskes = '')
	{
		//return '11';

		$data = Yii::$app->rujukan->get_rujukan($rujukan, $faskes);
		// return var_dump($data);
		$cek = Yii::$app->rujukan->total_sep($faskes, $rujukan);
		$rawat = Rawat::findOne($id);
		$jaminan = new RawatJaminanBpjs();
		$pasien = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		$kontrol = new RawatKontrol();
		if ($rawat->load($this->request->post()) && $jaminan->load($this->request->post())) {
			
			$response2 = Yii::$app->hfis->get_jadwaldokter($rawat->poli->kode, date('Y-m-d', strtotime($rawat->tglmasuk)));
			$cek_dokter = Yii::$app->hfis->searchForId($rawat->dokter->kode_dpjp, $response2['response']);
			$data_json = json_decode($cek_dokter, true);
			$jam = explode("-", $data_json['jadwal']);
			$jam_buka = $jam[0];
			date_default_timezone_set("Asia/Jakarta");
			$angantrean = (int) ltrim(substr($rawat->no_antrian, -3), '0');
			$tambah = (25 * $angantrean);
			date_default_timezone_set("Asia/Jakarta");
			$tanggalperiksa = date('Y-m-d', strtotime($rawat->tglmasuk));
			$jam_dilayani = date("H:i:s", strtotime("+" . $tambah . " minutes", strtotime($jam_buka)));
			$dilayani = date('Y-m-d H:i:s', strtotime($tanggalperiksa . ' ' . $jam_dilayani));
			$fix = strtotime($dilayani, strtotime('-7 hour')) . '000';
			$dokter_kuota = DokterKuota::find()->where(['tgl' => date('Y-m-d', strtotime($rawat->tglmasuk))])->andwhere(['iddokter' => $rawat->iddokter])->andwhere(['idpoli' => $rawat->idpoli])->one();
			if ($rawat->kunjungan == 1) {
				$kunjungan = 1;
			} else {
				$kunjungan = 3;
			}
			$addAntri = array(
				"kodebooking" => $rawat->idrawat,
				"jenispasien" => 'JKN',
				"nomorkartu" => $pasien->no_bpjs,
				"nik" => $pasien->nik,
				"nohp" => $pasien->nohp,
				"kodepoli" => $rawat->poli->kode,
				"namapoli" => $rawat->poli->poli,
				"pasienbaru" => 0,
				"norm" => $pasien->no_rm,
				"tanggalperiksa" => date('Y-m-d', strtotime($rawat->tglmasuk)),
				"kodedokter" => $rawat->dokter->kode_dpjp,
				"namadokter" => $rawat->dokter->nama_dokter,
				"jampraktek" => $data_json['jadwal'],
				"jeniskunjungan" => $kunjungan,
				"nomorreferensi" => $data['rujukan']['noKunjungan'],
				"nomorantrean" => $rawat->poli->kode_antrean . '-' . substr($rawat->no_antrian, -3),
				"angkaantrean" => (int) ltrim(substr($rawat->no_antrian, -3), '0'),
				"estimasidilayani" => (int) $fix,
				"sisakuotajkn" =>  $dokter_kuota->sisa,
				"kuotajkn" => $dokter_kuota->kuota,
				"sisakuotanonjkn" => $dokter_kuota->sisa,
				"kuotanonjkn" => $dokter_kuota->kuota,
				"keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi.",
			);
			$antrian = Yii::$app->hfis->add_antrian($addAntri);
			$hitung_rawat = Rawat::find()->where(['no_rm' => $rawat->no_rm])->andwhere(['<>', 'status', 5])->count();
			if ($hitung_rawat < 2) {
				$taks = array(
					"kodebooking" => $rawat->idrawat,
					"taskid" => 1,
					"waktu" =>  $this->milliseconds(),
				);
				$taksid = Yii::$app->hfis->update_taks($taks);
				if ($taksid['metadata']['code'] == 200) {
					$taks2 = array(
						"kodebooking" => $rawat->idrawat,
						"taskid" => 2,
						"waktu" =>  $this->milliseconds(),
					);
					$taksid2 = Yii::$app->hfis->update_taks($taks2);
					if ($taksid2['metadata']['code'] == 200) {
						$taks3 = array(
							"kodebooking" => $rawat->idrawat,
							"taskid" => 3,
							"waktu" =>  $this->milliseconds(),
						);
						$taksid3 = Yii::$app->hfis->update_taks($taks3);
					}
				}
			} else {
				$taks = array(
					"kodebooking" => $rawat->idrawat,
					"taskid" => 3,
					"waktu" =>  $this->milliseconds(),
				);
				$taksid = Yii::$app->hfis->update_taks($taks);
			}

			$rawat->timecheckin = $fix;
			//$rawat->save(false);
			if ($data) {				// $rawat->asal_faskes = $data['rujukan']['provPerujuk']['nama'];
				$rawat->kode_faskes = $data['rujukan']['provPerujuk']['kode'];
				$rawat->asal_rujukan = $data['rujukan']['provPerujuk']['nama'];
			}
			if ($cek['response']['jumlahSEP'] == 0) {
				$sep = array(
					"request" => array(
						"t_sep" => array(
							"noKartu" => $pasien->no_bpjs,
							"tglSep" => date('Y-m-d', strtotime($rawat->tglmasuk)),
							"ppkPelayanan" => "0120R012",
							"jnsPelayanan" => "2",
							"klsRawat" => array(
								"klsRawatHak" => $data['rujukan']['peserta']['hakKelas']['kode'],
								"klsRawatNaik" => "",
								"pembiayaan" => "",
								"penanggungJawab" => ""
							),
							"noMR" => $pasien->no_rm,
							"rujukan" => array(
								"asalRujukan" => $faskes,
								"tglRujukan" => $data['rujukan']['tglKunjungan'],
								"noRujukan" => $data['rujukan']['noKunjungan'],
								"ppkRujukan" => $data['rujukan']['provPerujuk']['kode'],
							),
							"catatan" => "-",
							"diagAwal" => $data['rujukan']['diagnosa']['kode'],
							"poli" => array(
								"tujuan" => $rawat->poli->kode,
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
							"dpjpLayan" => $rawat->dokter->kode_dpjp,
							"noTelp" => "081111111101",
							"user" => "Coba Ws"

						),

					)
				);

				$response = Yii::$app->sep->post_sep_online($sep);
				$rawat->no_sep = $response['response']['sep']['noSep'];
				$rawat->save(false);
				if ($response['metaData']['code'] == 200) {
					Yii::$app->session->setFlash('success', 'No SEP : ' . $response['response']['sep']['noSep']);
				} else {
					Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $response['metaData']['message']);
				}
				return $this->redirect(['pasien/' . $pasien->id]);
				// $json = json_encode($response, true);
				// return var_dump($json);
			} else if ($rawat->poli->kode != $data['rujukan']['poliRujukan']['kode']) {
				$icdxx = explode(" - ", $rawat->icdx);
				$kode = $icdxx[0];
				$nama = $icdxx[1];
				$asspel = Yii::$app->request->post('assesmen');
				$sep = array(
					"request" => array(
						"t_sep" => array(
							"noKartu" => $pasien->no_bpjs,
							"tglSep" => date('Y-m-d', strtotime($rawat->tglmasuk)),
							"ppkPelayanan" => "0120R012",
							"jnsPelayanan" => "2",
							"klsRawat" => array(
								"klsRawatHak" => $data['rujukan']['peserta']['hakKelas']['kode'],
								"klsRawatNaik" => "",
								"pembiayaan" => "",
								"penanggungJawab" => ""
							),
							"noMR" => $pasien->no_rm,
							"rujukan" => array(
								"asalRujukan" => "1",
								"tglRujukan" => $data['rujukan']['tglKunjungan'],
								"noRujukan" => $data['rujukan']['noKunjungan'],
								"ppkRujukan" => $data['rujukan']['provPerujuk']['kode'],
							),
							"catatan" => "-",
							"diagAwal" => $kode,
							"poli" => array(
								"tujuan" => $rawat->poli->kode,
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
							"assesmentPel" => $asspel,
							"skdp" => array(
								"noSurat" => "",
								"kodeDPJP" => ""
							),
							"dpjpLayan" => $rawat->dokter->kode_dpjp,
							"noTelp" => "081111111101",
							"user" => "Coba Ws"

						),

					)
				);
				// return print_r($sep);
				$response = Yii::$app->sep->post_sep_online($sep);
				$rawat->no_sep = $response['response']['sep']['noSep'];
				$rawat->save(false);
				if ($response['metaData']['code'] == 200) {
					Yii::$app->session->setFlash('success', 'No SEP : ' . $response['response']['sep']['noSep']);
				} else {
					Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $response['metaData']['message']);
				}
				return $this->redirect(['pasien/' . $pasien->id]);
			} else {
				$icdxx = explode(" - ", $rawat->icdx);
				$kode = $icdxx[0];
				$nama = $icdxx[1];
				$tujuanKunj = Yii::$app->request->post('tujuanKunj');
				$assesmentPel = Yii::$app->request->post('assesmentPel');
				$noskpd = Yii::$app->request->post('noskpd');
				$kddpjs_kontrol = Yii::$app->request->post('kddpjs_kontrol');
				$sep = array(
					"request" => array(
						"t_sep" => array(
							"noKartu" => $pasien->no_bpjs,
							"tglSep" => date('Y-m-d', strtotime($rawat->tglmasuk)),
							"ppkPelayanan" => "0120R012",
							"jnsPelayanan" => "2",
							"klsRawat" => array(
								"klsRawatHak" => $data['rujukan']['peserta']['hakKelas']['kode'],
								"klsRawatNaik" => "",
								"pembiayaan" => "",
								"penanggungJawab" => ""
							),
							"noMR" => $pasien->no_rm,
							"rujukan" => array(
								"asalRujukan" => "1",
								"tglRujukan" => $data['rujukan']['tglKunjungan'],
								"noRujukan" => $data['rujukan']['noKunjungan'],
								"ppkRujukan" => $data['rujukan']['provPerujuk']['kode'],
							),
							"catatan" => "-",
							"diagAwal" => $kode,
							"poli" => array(
								"tujuan" => $rawat->poli->kode,
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
							"tujuanKunj" => $tujuanKunj,
							"flagProcedure" => "",
							"kdPenunjang" => "",
							"assesmentPel" => $assesmentPel,
							"skdp" => array(
								"noSurat" => $noskpd,
								"kodeDPJP" => $kddpjs_kontrol
							),
							"dpjpLayan" => $rawat->dokter->kode_dpjp,
							"noTelp" => "081111111101",
							"user" => "Coba Ws"

						),

					)
				);
				// return print_r($sep);
				$response = Yii::$app->sep->post_sep_online($sep);
				$rawat->no_sep = $response['response']['sep']['noSep'];
				$rawat->save(false);
				if ($response['metaData']['code'] == 200) {
					Yii::$app->session->setFlash('success', 'No SEP : ' . $response['response']['sep']['noSep']);
				} else {
					Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $response['metaData']['message']);
				}
				return $this->redirect(['pasien/' . $pasien->id]);
			}
		} else if ($kontrol->load($this->request->post())) {
			$sep_kontrol = array(
				'request' => array(
					'noSEP' => $kontrol->no_sep,
					'kodeDokter' => $kontrol->kode_dokter,
					'poliKontrol' => $rawat->poli->kode,
					'tglRencanaKontrol' => $kontrol->tgl_kontrol,
					'user' => 'RM Sulaiman',
				),
			);
			
			// return print_r($sep_kontrol);
			$post_kontrol = Yii::$app->kontrol->post_kontrol($sep_kontrol);
			if ($post_kontrol['metaData']['code'] == 200) {
				$kontrol->idrawat = $rawat->id;
				$kontrol->no_rm = $rawat->no_rm;
				//$kontrol->no_sep = $rawat->no_sep;
				$kontrol->idpoli = $rawat->idpoli;
				$kontrol->tgl_buat = date('Y-m-d');
				$kontrol->no_surat = $post_kontrol['response']['noSuratKontrol'];
				Yii::$app->session->setFlash('success', 'No Surat ' . $post_kontrol['response']['noSuratKontrol']);
				$kontrol->save();
			} else {
				Yii::$app->session->setFlash('danger', 'No Surat ' . $post_kontrol['metaData']['message']);
				return $this->refresh();
			}
		}


		return $this->render('sep', [
			'rujukan' => $data,
			'cek' => $cek['response']['jumlahSEP'],
			'rawat' => $rawat,
			'pasien' => $pasien,
			'jaminan' => $jaminan,
		]);
	}
	public function actionSepUgd($id)
	{
		$time = date('Y-m-d');
		$model = Rawat::findOne($id);
		$jaminan = new RawatJaminanBpjs();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$response = Yii::$app->vclaim->get_peserta($pasien->no_bpjs, $time);
		if ($model->load($this->request->post()) && $jaminan->load($this->request->post())) {
			$icdx = explode(" - ", $model->icdx);
			$kode = $icdx[0];
			$nama = $icdx[1];
			$sep = array(
				"request" => array(
					"t_sep" => array(
						"noKartu" => $pasien->no_bpjs,
						"tglSep" => date('Y-m-d', strtotime($model->tglmasuk)),
						"ppkPelayanan" => '0120R012',
						"jnsPelayanan" => "2",
						"klsRawat" => array(
							"klsRawatHak" => $response['peserta']['hakKelas']['kode'],
							"klsRawatNaik" => "",
							"pembiayaan" => "",
							"penanggungJawab" => ""
						),
						"noMR" => $pasien->no_rm,
						"rujukan" => array(
							"asalRujukan" => "2",
							"tglRujukan" => "",
							"noRujukan" => "",
							"ppkRujukan" => ""
						),
						"catatan" => "-",
						"diagAwal" => $kode,
						"poli" => array(
							"tujuan" => $model->poli->kode,
							"eksekutif" => "0"
						),
						"cob" => array(
							"cob" => "0"
						),
						"katarak" => array(
							"katarak" => "0",
						),
						"jaminan" => array(
							"lakaLantas" => $model->jaminan,
							"noLP" => $jaminan->noLp,
							"penjamin" => array(
								"tglKejadian" => $jaminan->tglkejadian,
								"keterangan" => $jaminan->keterangan,
								"suplesi" => array(
									"suplesi" => $jaminan->suplesi,
									"noSepSuplesi" => $jaminan->nosep_suplesi,
									"lokasiLaka" => array(
										"kdPropinsi" => $jaminan->propinsi,
										"kdKabupaten" => $jaminan->kabupaten,
										"kdKecamatan" => $jaminan->kecamatan
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
						"dpjpLayan" => $model->dokter->kode_dpjp,
						"noTelp" => $pasien->nohp,
						"user" => "Coba Ws"

					),

				)
			);
			$sep_post = Yii::$app->sep->post_sep_online($sep);
			$model->no_sep = $sep_post['response']['sep']['noSep'];
			$model->save(false);
			if ($sep_post['metaData']['code'] == 200) {
				Yii::$app->session->setFlash('success', 'No SEP : ' . $sep_post['response']['sep']['noSep']);
			} else {
				Yii::$app->session->setFlash('danger', 'Gagal Buat SEP : ' . $sep_post['metaData']['message']);
			}

			return $this->redirect(['pasien/' . $pasien->id]);
		}
		return $this->render('sep-ugd', [
			'model' => $model,
			'pasien' => $pasien,
			'jaminan' => $jaminan,
		]);
	}
	public function actionPrintSep($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Rawat::find()->where(['id' => $id])->one();
		$pasien = Pasien::find()->where(['no_rm' => $model->no_rm])->one();
		$cari_sep = Yii::$app->sep->cari_sep($model->no_sep);
		$cari_peserta = Yii::$app->vclaim->get_peserta($pasien->no_bpjs, date('Y-m-d'));
		$content = $this->renderPartial('print-sep', ['model' => $model, 'sep' => $cari_sep, 'pasien' => $pasien, 'peserta' => $cari_peserta]);
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
	public function actionSepRajal($id, $tgl)
	{
		$rawat = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		return $this->render('sep-rajal', [
			'rawat' => $rawat,
			'pasien' => $pasien,
		]);
	}
	public function actionSepManual($id, $tgl)
	{
		$rawat = Rawat::findOne($id);
		$date = date('Y-m-d', strtotime($rawat->tglmasuk));
		$date3 = date_create(date('Y-m-d'));
		$date4 = date_create($date);
		$diff2 = date_diff($date3, $date4);
		$selisih2 = $diff2->format("%d");
		$rawat = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm' => $rawat->no_rm])->one();
		return $this->render('sep-manual', [
			'rawat' => $rawat,
			'selisih2' => $selisih2,
		]);
	}
	public function actionLabelPasien($id, $jumlah)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = RawatKunjungan::find()->where(['id' => $id])->one();
		$content = $this->renderPartial('label-kunjungan', ['model' => $model, 'jumlah' => $jumlah]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [50, 20],
			'marginTop' => '2',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '5',
			'marginRight' => '0',
			'marginBottom' => '2',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-pasien.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}
	public function actionGelang($id)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Rawat::find()->where(['id' => $id])->one();
		$content = $this->renderPartial('gelang', ['model' => $model]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [100, 25],
			'marginTop' => '3',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '4',
			'marginRight' => '4',
			'marginBottom' => '3',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-pasien.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}
	public function actionBarcodePasien($id, $jumlah)
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = RawatKunjungan::find()->where(['id' => $id])->one();
		$content = $this->renderPartial('barcode-kunjungan', ['model' => $model, 'jumlah' => $jumlah]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [50, 20],
			'marginTop' => '2',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '0',
			'marginRight' => '0',
			'marginBottom' => '2',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-pasien.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}
	public function actionBarcodeKartuLabel($id, $jumlah = '')
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Pasien::find()->where(['id' => $id])->one();
		$content = $this->renderPartial('barcode-kunjungan', ['model' => $model, 'jumlah' => $jumlah]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [70, 36],
			'marginTop' => '3',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '4',
			'marginRight' => '4',
			'marginBottom' => '3',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-pasien.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}
	public function actionBarcodeKartu($id)
	{
		//tampilkan bukti proses
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Pasien::find()->where(['id' => $id])->one();
		$alamat = PasienAlamat::find()->where(['idpasien' => $model->id])->andwhere(['utama' => 1])->one();
		$content = $this->renderPartial('barcode-kartu', ['model' => $model, 'alamat' => $alamat]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => [70, 36],
			'marginTop' => '3',
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'marginLeft' => '4',
			'marginRight' => '4',
			'marginBottom' => '3',
			'content' => $content,
			'cssFile' => '@frontend/web/css/paper-pasien.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}
	//Print Form Pasien
	public function actionFormPasien($id)
	{
		//tampilkan bukti proses
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Pasien::find()->where(['id' => $id])->one();
		$content = $this->renderPartial('form-pasien', ['model' => $model]);

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4,
			'content' => $content,

			'cssFile' => '@frontend/web/css/paper.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],

			'methods' => [
				'SetFooter' => ['DRM. 01 A{PAGENO} - RJ'],
			]
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render();
	}

	//Get Data Pasien
	public function actionGetPasienNik()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$time = date('Y-m-d');
			$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Peserta/nokartu/' . $kode . '/tglSEP/' . $time, 2);
			$data_json = json_decode($response, true);
			$json = $data_json['metaData'];
			if ($json['code'] == 200) {
				$response = Yii::$app->vclaim->get_peserta($kode, $time);
				$model = $response['peserta'];
			} else {
				$model = 201;
			}
		} else {
			$model = 201;
		}
		return \yii\helpers\Json::encode($model);
	}
	public function actionGetPasien()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$time = date('Y-m-d');
			$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Peserta/nokartu/' . $kode . '/tglSEP/' . $time, 2);
			$data_json = json_decode($response, true);
			$json = $data_json['metaData'];
			// return print_r($json);
			if ($json['code'] == 200) {
				$response = Yii::$app->vclaim->get_peserta($kode, $time);
				$model = $response['peserta'];
			} else {
				$model = 201;
			}
		} else {
			$model = 201;
		}
		return \yii\helpers\Json::encode($model);
	}

	/**
	 * Updates an existing Pasien model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param int $id ID
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model = $this->findModel($id);
	}
	public function actionTesVclaim()
	{
		$response = Yii::$app->vclaim->get_rujukan('0001391429979');
		$model = $response['response'];
		return \yii\helpers\Json::encode($response);
	}
	/**
	 * Deletes an existing Pasien model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param int $id ID
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Pasien model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param int $id ID
	 * @return Pasien the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Pasien::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
	protected function findAlamat($id)
	{
		if (($model = PasienAlamat::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
