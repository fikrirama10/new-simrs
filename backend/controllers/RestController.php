<?php

namespace backend\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\Tindakan;
use common\models\Tarif;
use common\models\Transaksi;
use common\models\Kelurahan;
use common\models\RadiologiHasilfoto;
use common\models\ObatKartustok;
use common\models\RadiologiTindakan;
use common\models\LaboratoriumPemeriksaan;
use common\models\Pasien;
use common\models\Obat;
use common\models\ObatDroping;
use common\models\Rawat;
use common\models\ObatMutasi;
use common\models\Ruangan;
use common\models\RuanganBed;
use common\models\TransaksiDetail;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;
use common\models\Dokter;
use common\models\Poli;
use common\models\Klpcm;
use common\models\DokterJadwal;
use common\models\RawatJenis;

class RestController extends \yii\rest\Controller
{
	public static function allowedDomains()
	{
		return [
			'*',  // star allows all domains
			'http://localhost:3000',
		];
	}
	public $enableCsrfValidation = false;
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [

			// For cross-domain AJAX request
			'corsFilter'  => [
				'class' => \yii\filters\Cors::className(),
				'cors'  => [
					// restrict access to domains:
					'Origin' => static::allowedDomains(),
					'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'OPTIONS'],
					'Access-Control-Allow-Credentials' => false,
					'Access-Control-Max-Age' => 260000, // Cache (seconds)
					'Access-Control-Request-Headers' => ['*'],
					'Access-Control-Allow-Origin' => false,


				],

			],

		]);
	}
	public function actionListTaks($kode_booking){
		$taks = array(
			"kodebooking"=> $kode_booking,
		);
		$taksid = Yii::$app->hfis->list_taks($taks);
		return $taksid;
	}
	public function actionUpdateTakss($kode_booking,$taks){
		$tpstamp = strtotime(date('Y-m-d H:i:s')).'000';
		$taks = array(
			"kodebooking"=> $kode_booking,
			"taskid"=> $taks,
			"waktu"=>  $tpstamp,
		);
		$taksid = Yii::$app->hfis->update_taks($taks);
		return $taksid;
	}
	public function actionKlpcmLaporan($tahun,$bulan){
		$poli = Poli::find()->where(['between','id',1,6])->all();
		$arrpoli = [];
		foreach($poli as $p){

			$terbaca = Klpcm::find()->joinWith(['rawat as rawat'])->where(['MONTH(tgl_kunjungan)'=>$bulan])->andwhere(['YEAR(tgl_kunjungan)'=>$tahun])->andwhere(['rawat.idpoli'=>$p->id])->andwhere(['keterbacaan'=>1])->count();
			$tidak_terbaca = Klpcm::find()->joinWith(['rawat as rawat'])->where(['MONTH(tgl_kunjungan)'=>$bulan])->andwhere(['YEAR(tgl_kunjungan)'=>$tahun])->andwhere(['rawat.idpoli'=>$p->id])->andwhere(['keterbacaan'=>0])->count();
			$terbaca_persen = ($terbaca / ($terbaca+$tidak_terbaca)) * 100;
			$tidak_terbaca_persen = ($tidak_terbaca / ($terbaca+$tidak_terbaca)) * 100;
			$lengkap = Klpcm::find()->joinWith(['rawat as rawat'])->where(['MONTH(tgl_kunjungan)'=>$bulan])->andwhere(['YEAR(tgl_kunjungan)'=>$tahun])->andwhere(['rawat.idpoli'=>$p->id])->andwhere(['kelengkapan'=>1])->count();
			$tidak_lengkap = Klpcm::find()->joinWith(['rawat as rawat'])->where(['MONTH(tgl_kunjungan)'=>$bulan])->andwhere(['YEAR(tgl_kunjungan)'=>$tahun])->andwhere(['rawat.idpoli'=>$p->id])->andwhere(['kelengkapan'=>0])->count();
			$lengkap_persen = ($lengkap / ($lengkap+$tidak_lengkap) ) * 100;
			$tidak_lengkap_persen = ($tidak_lengkap / ($lengkap+$tidak_lengkap)) * 100;

			array_push($arrpoli,[
				'poli'=>$p->poli, 
				'terbaca'=>$terbaca,
				'tidak_terbaca'=>$tidak_terbaca,
				'lengkap'=>$lengkap,
				'tidak_lengkap'=>$tidak_lengkap,

				'terbaca_persen'=>round($terbaca_persen),
				'tidak_terbaca_persen'=>round($tidak_terbaca_persen),

				'lengkap_persen'=>round($lengkap_persen),
				'tidak_lengkap_persen'=>round($tidak_lengkap_persen),
			]);
			
		}
		
		return $arrpoli;
	}
	public function actionUpdateSolo()
	{
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)) {
			exit("Data empty.");
		} else {
			$data = json_encode(array(
				"kodekelas" => $arr->kodekelas,
				"koderuang" => $arr->koderuang,
				"namaruang" => $arr->namaruang,
				"kapasitas" => $arr->kapasitas,
				"tersedia" => $arr->tersedia,
				"tersediapria" => "0",
				"tersediawanita" => "0",
				"tersediapriawanita" => $arr->kapasitas,
			));
			return Yii::$app->vclaim->updateKamarSolo($data);  
		}
	}
	public function actionAntriTgl()
	{
		$tgl = date('Y-m-d', strtotime('+5 hours'));
		$response = Yii::$app->hfis->antri_tanggal($tgl);
		//return $response;
		$json = json_encode($response, true);
		$array = array();
		if ($response['data']['code'] == 200) {
			foreach ($response['response'] as $js) {
				if ($js['status'] != 'Batal') {
					if ($js['ispeserta'] == true) {
						$pasien = Yii::$app->vclaim->get_pesertanobpjs($js['nokapst'], $tgl);
						$nama_pasien = $pasien['response']['peserta']['nama'];
					} else {
						$pasien = Pasien::find()->where(['no_rm' => $js['norekammedis']])->one();
						$nama_pasien = $pasien->nama_pasien;
					}

					$post = array(
						'kodebooking' => 'RJ2023837020001',
					);
					$taksid = Yii::$app->hfis->list_taks($post);
					$array_json = array();
					// if($taksid['data']['code'] == 200){
					// 	foreach($taksid as $ti){
					// 		array_push($array_json,[
					// 			'taksid'=>$ti['taksid'],
					// 		]);
					// 	}
					// }
					array_push($array, [
						'nama_pasien' => $nama_pasien,
						'no_rm' => $js['norekammedis'],
						'poli' => $js['kodepoli'],
						'noantrean' => $js['noantrean'],
						'kodebooking' => $js['kodebooking'],
						'taksid' => $taksid

					]);
				}
			}
			return [
				'status' => 200,
				'data' => $array,
			];
		} else {
			return [
				'status' => 401,
				'data' => $array,
			];
		}
	}
	public function actionTesRujukan()
	{
		$id = '0002067700149';
		$response = Yii::$app->rujukan->get_nokas($id);
		return $response;
	}
	function searchForId($dokter, $array)
	{

		foreach ($array as $key => $val) {
			if ($val['kodedokter'] == $dokter) {
				return $val;
			}
		}
		return null;
	}
	function milliseconds()
	{
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
	public function actionTesJadwal($kode, $tgl, $dokter = '')
	{
		$response = Yii::$app->hfis->get_jadwaldokter($kode, $tgl);
		$cek = $this->searchForId($dokter, $response);
		return $cek;
	}

	public function actionGetidPasien($id)
	{
		$query = Pasien::find()->where(['no_rm' => $id])->one();
		// $arrdip=array();
		// foreach ($query as $q){
		// 	array_push($arrdip,[
		// 		'no_rm'=>$q->no_rm,
		// 		'nama_pasien'=>$q->nama_pasien,
		// 		'nohp'=>$q->nohp
		// 	]);
		// }		
		return [
			'no_rm' => $query->no_rm,
			'nama_pasien' => $query->nama_pasien,
			'nohp' => $query->nohp
		];
	}
	public function actionGetidDokter($q)
	{
		$query = Dokter::find()->where(['id' => $q])->one();
		return [
			'id_dokter' => $query->id,
			'nama_dokter' => $query->nama_dokter,
		];
	}
	public function actionGetDokter($q)
	{
		$query = Dokter::find()->andFilterWhere(['like', 'nama_dokter', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id_dokter' => $q->id,
				'nama_dokter' => $q->nama_dokter,
			]);
		}

		return $arrdip;
	}
	public function actionGetPasien($q)
	{
		$query = Pasien::find()->andFilterWhere(['like', 'no_rm', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'no_rm' => $q->no_rm,
				'nama_pasien' => $q->nama_pasien,
				'nohp' => $q->nohp
			]);
		}

		return $arrdip;
	}
	public function actionGetJenisrawat()
	{
		$query = RawatJenis::find()->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id_jenis' => $q->id,
				'jenis_rawat' => $q->jenis,
			]);
		}

		return $arrdip;
	}
	public function actionTesSpri()
	{
		$spri = array(
			"request" => array(
				"noKartu" => "0001116500714",
				"kodeDokter" => "227186",
				"poliKontrol" => "INT",
				"tglRencanaKontrol" => "2022-02-24",
				"user" => "sss"
			)
		);
		$response = Yii::$app->sep->post_spri($spri);
		return $response;
	}
	public function actionDeleteSep()
	{
		$spri = array(
			"request" => array(
				"t_sep" => array(
					"noSep" => "0171R0010222V000002",
					"user" => "Coba Ws"
				)
			)
		);
		$response = Yii::$app->sep->delete_sep($spri);
		return $response;
	}
	public function actionUpdateTaks()
	{
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)) {
			exit("Data empty.");
		} else {
			$kodebooking = $arr->kodebooking;
			$taksid = $arr->taksid;
			$post = array(
				'kodebooking' => $kodebooking,
				"taskid" => $taksid,
				"waktu" =>  $this->milliseconds(),
			);
			$taks = Yii::$app->hfis->update_taks($post);
			return $taks;
		}
	}
	public function actionTaksid()
	{
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)) {
			exit("Data empty.");
		} else {
			$kodebooking = $arr->kodebooking;
			$post = array(
				'kodebooking' => $kodebooking,
			);
			$response = Yii::$app->kazo->bpjs_contentr('https://apijkn-dev.bpjs-kesehatan.go.id/antreanrs_dev/antrean/getlisttask', 1, $post);
			$data_json = json_decode($response, true);

			if ($data_json['metadata']['code'] == '200') {
				$post_update = Yii::$app->hfis->list_taks($post);
				return $post_update;
			}
		}
	}
	public function actionJadwalDokter($id)
	{
		$dokter = Dokter::findOne($id);
		if ($dokter) {
			$hari = date('N', strtotime(date('Y-m-d')));
			$jadwal = DokterJadwal::find()->where(['iddokter' => $dokter->id])->andwhere(['status' => 1])->andwhere(['<>', 'idhari', $hari])->all();
			$arrjad = array();
			foreach ($jadwal as $j) {
				array_push($arrjad, [
					'hari' => $j->idhari,
					'buka' => date('H:i', strtotime($j->jam_mulai)),
					'tutup' => date('H:i', strtotime($j->jam_selesai)),
				]);
			}
			$result =  array(
				'kodepoli' => $dokter->poli->kode,
				'kodesubspesialis' => $dokter->poli->kode,
				'kodedokter' => $dokter->kode_dpjp,
				'jadwal' => $arrjad,

			);
			$post_update = Yii::$app->hfis->update_jadwal($result);
			return $post_update;
		}
	}
	public function actionTesInput()
	{
		$arr = json_decode(file_get_contents("php://input"));
		if (empty($arr)) {
			exit("Data empty.");
		} else {
			$kodebooking =  $arr->nomorkartu;
			$jenispasien =  $arr->jenispasien;
			$nomorkartu =  $arr->nomorkartu;
			$nik =  $arr->nik;
			$nohp =  $arr->nohp;
			$kodepoli =  $arr->kodepoli;
			$namapoli =  $arr->namapoli;
			$pasienbaru =  $arr->pasienbaru;
			$norm =  $arr->norm;
			$tanggalperiksa =  $arr->tanggalperiksa;
			$kodedokter =  $arr->kodedokter;
			$namadokter =  $arr->namadokter;
			$jampraktek =  $arr->jampraktek;
			$jeniskunjungan =  $arr->jeniskunjungan;
			$nomorreferensi =  $arr->nomorreferensi;
			$nomorantrean =  $arr->nomorantrean;
			$angkaantrean =  $arr->angkaantrean;
			$estimasidilayani =  $arr->estimasidilayani;
			$sisakuotajkn =  $arr->sisakuotajkn;
			$kuotajkn =  $arr->kuotajkn;
			$sisakuotanonjkn =  $arr->sisakuotanonjkn;
			$kuotanonjkn =  $arr->kuotanonjkn;
			$keterangan =  $arr->keterangan;

			$addAntri = array(
				"kodebooking" => $kodebooking,
				"jenispasien" => $jenispasien,
				"nomorkartu" => $nomorkartu,
				"nik" => $nik,
				"nohp" => $nohp,
				"kodepoli" => $kodepoli,
				"namapoli" => $namapoli,
				"pasienbaru" => $pasienbaru,
				"norm" => $norm,
				"tanggalperiksa" => $tanggalperiksa,
				"kodedokter" => $kodedokter,
				"namadokter" => $namadokter,
				"jampraktek" => $jampraktek,
				"jeniskunjungan" => $jeniskunjungan,
				"nomorreferensi" => $nomorreferensi,
				"nomorantrean" => $nomorantrean,
				"angkaantrean" => $angkaantrean,
				"estimasidilayani" => $estimasidilayani,
				"sisakuotajkn" =>  $sisakuotajkn,
				"kuotajkn" => $kuotajkn,
				"sisakuotanonjkn" => $sisakuotanonjkn,
				"kuotanonjkn" => $kuotanonjkn,
				"keterangan" => $keterangan,
			);
			$antrian = Yii::$app->hfis->add_antrian($addAntri);
			return $antrian;
		}
	}
	public function actionTes()
	{
		// $string = 110;
		// return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
		date_default_timezone_set('UTC');
		$consId = '10003';
		$pssword = '8rP8F99311';
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$noKartu = '0001106101416';
		$tglSep = date('Y-m-d');
		$response = Yii::$app->kazo->bpjs_contentr('https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev/Peserta/nokartu/' . $noKartu . '/tglSEP/' . $tglSep, 2);
		$data_json = json_decode($response, true);

		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);
		$final = json_decode($data_json3, true);
		return $final;
	}

	public function actionMonitoringKunjungan($norujukan, $awal, $akhir, $nokartu, $poli)
	{
		$response = Yii::$app->vclaim->get_monitoring($nokartu, $awal, $akhir);
		if ($response['metaData']['code'] == 200) {
			$history = $response['response']['histori'];
			$arrdip = array();
			foreach ($history as $key => $q) {

				if ($q['noRujukan'] == $norujukan && $q['poli'] == $poli) {
					array_push($arrdip, [
						'noRujukan' => $q['noRujukan'],
						'noKartu' => $q['noKartu'],
						'jnsPelayanan' => $q['jnsPelayanan'],
						'poli' => $q['poli'],
					]);
				}
			}

			return array(
				'metaData' => array(
					'code' => 200,
				),
				'response' => $arrdip,
			);
		} else {
			return $response;
		}
	}
	public function actionAlamat($q)
	{
		$query = Kelurahan::find()->andFilterWhere(['like', 'nama', $q])->all();

		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id_kel,
				'IdKel' => $q->id_kel,
				'nama' => $q->nama,
				'Kec' => $q->kecamatan->nama,
				'IdKec' => $q->kecamatan->id_kec,
				'Kab' => $q->kecamatan->kabupaten->nama,
				'IdKab' => $q->kecamatan->kabupaten->id_kab,
				'Prov' => $q->kecamatan->kabupaten->provinsi->nama,
				'IdProv' => $q->kecamatan->kabupaten->provinsi->id_prov,
			]);
		}

		return $arrdip;
	}
	public function actionKunjunganHarian()
	{
		$pasien = Pasien::find()->where(['tgldaftar' => date('Y-m-d', strtotime('+6 hour', strtotime(date('Y-m-d H:i:s'))))])->andwhere(['pasien_lama' => 0])->count();
		$ugd = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => date('Y-m-d', strtotime('+6 hour', strtotime(date('Y-m-d H:i:s'))))])->andwhere(['idjenisrawat' => 3])->count();
		$rajal = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => date('Y-m-d', strtotime('+6 hour', strtotime(date('Y-m-d H:i:s'))))])->andwhere(['idjenisrawat' => 1])->count();
		$ranap = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => date('Y-m-d', strtotime('+6 hour', strtotime(date('Y-m-d H:i:s'))))])->andwhere(['idjenisrawat' => 2])->count();
		return array(
			'metaData' => array(
				'code' => 200,
			),
			'kunjungan' => array(
				'pasien' => $pasien,
				'ugd' => $ugd,
				'rajal' => $rajal,
				'ranap' => $ranap,
			),
		);
	}
	public function actionHistoriPasienRajal($start, $end, $iddokter, $jenisrawat, $jenisbayar)
	{
		$rawat = Rawat::find()->where(['iddokter' => $iddokter])->andwhere(['between', 'DATE_FORMAT(tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['<>', 'status', '5'])->andwhere(['idjenisrawat' => $jenisrawat])->andwhere(['idbayar' => $jenisbayar])->orderBy(['tglmasuk' => SORT_ASC])->all();
		$arrdip = array();
		foreach ($rawat as $r) {
			$rincian = TransaksiDetail::find()->where(['iddokter' => $r->iddokter])->andwhere(['idrawat' => $r->id])->all();
			$rincian_sum = TransaksiDetail::find()->where(['iddokter' => $r->iddokter])->andwhere(['idrawat' => $r->id])->sum('persentase_dokter');
			$arrinci = array();
			$total_rinci = 0;
			foreach ($rincian as $ri) :
				$total_rinci += $ri->persentase_dokter;
				array_push($arrinci, [
					'idTrx' => $ri->idtransaksi,
					'idRawat' => $ri->idrawat,
					'namaTindakan' => $ri->nama_tindakan,
					'persenDokter' => $ri->persentase_dokter,
				]);
			endforeach;
			array_push($arrdip, [
				'id' => $r->id,
				'idrawat' => $r->idrawat,
				'noRm' => $r->no_rm,
				'pasien' => $r->pasien->nama_pasien,
				'poli' => $r->poli->poli,
				'tglRawat' => $r->tglmasuk,
				'dokter' => $arrinci,
				'total' => $total_rinci,
			]);
		}
		return $arrdip;
	}
	public function actionMonitoring($awal, $akhir, $nokartu)
	{
		$response = Yii::$app->vclaim->get_monitoring($nokartu, $awal, $akhir);
		//$history = $response['response']['histori'];


		return $response;
	}
	public function actionKontrol()
	{
		$response = Yii::$app->vclaim->get_rencanakontrol('0120R0121021K000058');
		//$history = $response['response']['histori'];


		return $response;
	}
	public function actionPesertaNik()
	{
		$response = Yii::$app->vclaim->get_peserta_nik('3204112508670002', date('Y-m-d'));

		return $response;
	}
	public function actionTarifRajal($q, $idjenis = '', $idpoli = '')
	{
		$query = Tarif::find()->where(['idjenisrawat' => $idjenis])->andwhere(['idpoli' => $idpoli])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifOk($q)
	{
		$query = Tarif::find()->where(['idkategori' => 7])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'nama' => $q->nama_tarif,
			]);
		}
		return $arrdip;
	}

	public function actionFisio($q)
	{
		$query = Tarif::find()->where(['idkategori' => 8])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'nama' => $q->nama_tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifRad($q)
	{
		$query = Tarif::find()->where(['idkategori' => 5])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
				'tarif' => $q->tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifLab($q)
	{
		$query = Tarif::find()->where(['idkategori' => 4])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
				'tarif' => $q->tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifPenunjang($q)
	{
		$query = Tarif::find()->where(['between', 'idkategori', 4, 10])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
				'tarif' => $q->tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifInap($q, $idjenis = '', $idkelas, $idruangan)
	{
		$query = Tarif::find()->where(['idjenisrawat' => $idjenis])->andwhere(['idkelas' => $idkelas])->andwhere(['idruangan' => $idruangan])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
				'tarif' => $q->tarif,
			]);
		}
		return $arrdip;
	}
	public function actionTarifRawat($q, $idjenis = '')
	{
		if ($idjenis == '') {
			$query = Tarif::find()->andFilterWhere(['like', 'nama_tarif', $q])->all();
		} else {
			$query = Tarif::find()->where(['idjenisrawat' => $idjenis])->andFilterWhere(['like', 'nama_tarif', $q])->all();
		}


		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tarif,
				'tarif' => $q->tarif,
			]);
		}

		return $arrdip;
	}
	public function actionListObat2($q)
	{
		$query = Obat::find()->andFilterWhere(['like', 'nama_obat', $q])->all();

		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'nama' => $q->nama_obat,
			]);
		}

		return [
		    'result'=>$arrdip
		    ];
	}
	public function actionListObat($q)
	{
		$query = Obat::find()->andFilterWhere(['like', 'nama_obat', $q])->all();

		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'nama' => $q->nama_obat,
			]);
		}

		return $arrdip;
	}
	public function actionListObatDroping($q)
	{
		$query = ObatDroping::find()->andFilterWhere(['like', 'nama_obat', $q])->all();

		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'nama' => $q->nama_obat,
			]);
		}

		return $arrdip;
	}
	public function actionFaskes($q)
	{
		$response = Yii::$app->vclaim->get_faskes($q, 2);
		// return $response;
		$model = $response['faskes'];
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['kode'] . ',' . $q['nama'],
				'nama' => $q['nama'],
			]);
		}
		return $arrdip;
	}
	public function actionDpjp($q)
	{
		$response = Yii::$app->vclaim->get_dpjp(1, $q);
		$model = $response['response']['list'];
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['kode'],
				'nama' => $q['nama'],
			]);
		}
		return $arrdip;
	}
	public function actionListRadiologi($q)
	{
		$query = RadiologiTindakan::find()->andFilterWhere(['like', 'nama_tindakan', $q])->all();

		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_tindakan,
			]);
		}

		return $arrdip;
	}
	public function actionListLab($q)
	{
		$query = LaboratoriumPemeriksaan::find()->andFilterWhere(['like', 'nama_pemeriksaan', $q])->all();
		$arrdip = array();
		foreach ($query as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'tindakan' => $q->nama_pemeriksaan,
				'tarif' => $q->tarif->tarif,
			]);
		}
		return $arrdip;
	}


	public function actionTagihan($id)
	{
		$total = 0;
		$total_tu = 0;
		$transaksi = Transaksi::findOne($id);
		$trandetail = TransaksiDetail::find()->select(['transaksi_detail.*', 'COUNT(idpelayanan) AS jml'])->where(['idtransaksi' => $id])->groupBy('idpelayanan')->orderBy(['jml' => SORT_DESC])->all();
		$trandetail_umum = TransaksiDetail::find()->select(['transaksi_detail.*', 'COUNT(idpelayanan) AS jml'])->where(['idtransaksi' => $id])->andwhere(['idbayar' => 1])->groupBy('idpelayanan')->orderBy(['jml' => SORT_DESC])->all();
		$trxdetail_unit = TransaksiDetail::find()->where(['idtransaksi' => $id])->groupBy('idjenispelayanan')->all();
		$arrdip = array();
		$arrdipumum = array();
		$arrdip2 = array();
		foreach ($trxdetail_unit as $trx_u) {
			$arrdip3 = array();
			$unit = TransaksiDetail::find()->select(['transaksi_detail.*', 'COUNT(idpelayanan) AS jml'])->where(['idtransaksi' => $id])->andwhere(['idjenispelayanan' => $trx_u->idjenispelayanan])->groupBy('idpelayanan')->orderBy(['jml' => SORT_DESC])->all();
			foreach ($unit as $u) :
				array_push($arrdip3, [
					'nama' => $u->tindakan->nama_tindakan,
					'jumlah' => (int) $u->jml,
					'harga' => $u->tarif,
					'total' => $u->total * $u->jml,
					'jenis' => $u->jenis,
				]);
			endforeach;
			array_push($arrdip2, [
				'nama' => $trx_u->unit->unit,
				'rincian' => $arrdip3,
			]);
		}
		foreach ($trandetail as $q) {
			$total += $q->total * $q->jml;
			array_push($arrdip, [
				'nama' => $q->tindakan->nama_tindakan,
				'jumlah' => (int) $q->jml * $q->jumlah,
				'harga' => $q->tarif,
				'total' => $q->total * $q->jml,
				'jenis' => $q->jenis,
				'bayar' => $q->idbayar,

			]);
		}
		foreach ($trandetail_umum as $tu) {
			$total_tu += $tu->total * $tu->jml;
			array_push($arrdipumum, [
				'nama' => $tu->tindakan->nama_tindakan,
				'jumlah' => (int) $tu->jml * $tu->jumlah,
				'harga' => $tu->tarif,
				'total' => $tu->total * $tu->jml,
				'jenis' => $tu->jenis,
				'bayar' => $tu->idbayar,

			]);
		}
		return array(
			'transaksi' => array(
				'idkunjungan' => $transaksi->idkunjungan,
				'trxid' => $transaksi->idtransaksi,
				'no_rm' => $transaksi->no_rm,
				'namaPasien' => $transaksi->pasien->nama_pasien,
				'tglmasuk' => $transaksi->tgl_masuk,
				'status' => $transaksi->status
			),
			'rekapTagihan' => $arrdip,
			'rekapTagihanUmum' => $total_tu,
			'tagihan' => $arrdip2,
			'total' => $total
		);
	}
	public function actionListFoto($id)
	{

		$gambar = RadiologiHasilfoto::find()->where(['idhasil' => $id])->all();
		$arrdip = array();
		foreach ($gambar as $q) {
			array_push($arrdip, [
				'src' => Yii::$app->params['baseUrl'] . '/frontend/images/radiologi/x-ray/' . $q->foto,
				'title' => $q->nofoto,
			]);
		}

		return $arrdip;
	}
	public function actionBed()
	{

		$ruangan = Ruangan::find()->where(['idjenis' => 1])->all();
		$arrdip = array();
		foreach ($ruangan as $q) {
			$bed = RuanganBed::find()->where(['idruangan' => $q->id])->count();
			$bedTerisi = RuanganBed::find()->where(['idruangan' => $q->id])->andwhere(['terisi' => 1])->count();
			array_push($arrdip, [
				'id' => $q->id,
				'ruangan' => $q->nama_ruangan,
				'kelas' => $q->idkelas,
				'bed' => $bed,
				'terisi' => $bedTerisi,
			]);
		}

		return $arrdip;
	}

	public function actionMutasiStok($idobat, $awal, $akhir, $jenis = '', $subjenis = '', $idbatch = '', $asal = '')
	{
		if ($jenis == null && $idbatch == null && $subjenis == null) {
			$obat = ObatMutasi::find()->where(['idobat' => $idobat])->andwhere(['between', 'DATE_FORMAT(tgl,"%Y-%m-%d")', $awal, $akhir])->andwhere(['idgudang' => $asal])->orderBy(['tgl' => SORT_ASC])->all();
		} else {
			$obat = ObatMutasi::find()->where(['idobat' => $idobat])->andwhere(['between', 'DATE_FORMAT(tgl,"%Y-%m-%d")', $awal, $akhir])->andwhere(['idgudang' => $asal])->andwhere(['idjenismutasi' => $jenis])->andwhere(['idsubmutasi' => $subjenis])->andwhere(['idbacth' => $idbatch])->orderBy(['tgl' => SORT_DESC])->all();
		}
		$arrdip = array();
		foreach ($obat as $q) {
			array_push($arrdip, [
				'id' => $q->id,
				'obat' => $q->obat->nama_obat,
				'bacth' => $q->bacth->no_bacth,
				'merk' => $q->bacth->merk,
				'tgl' => $q->tgl,
				'stokAwal' => $q->stokawal,
				'stokAkhir' => $q->stokakhir,
				'jumlah' => $q->jumlah,
				'jenisMutasi' => $q->jenis->jenis_mutasi,
				'subMutasi' => $q->subjenis->subjenis,
			]);
		}

		return $arrdip;
	}
	public function actionKartuStok($idobat, $awal, $akhir, $jenis = '', $idbatch = '', $asal = '')
	{
		if ($jenis == null && $idbatch == null) {
			$obat = ObatKartustok::find()->where(['idobat' => $idobat])->andwhere(['between', 'tgl', $awal, $akhir])->andwhere(['idasal' => $asal])->orderBy(['tgl' => SORT_DESC])->all();
		} else {
			$obat = ObatKartustok::find()->where(['idobat' => $idobat])->andwhere(['between', 'tgl', $awal, $akhir])->andwhere(['jenis' => $jenis])->andwhere(['idbatch' => $idbatch])->andwhere(['idasal' => $asal])->orderBy(['tgl' => SORT_DESC])->all();
		}
		$arrdip = array();
		foreach ($obat as $q) {
			if ($q->jenis == 1) {
				$jenis = 'Keluar';
			} else {
				$jenis = 'Masuk';
			}
			array_push($arrdip, [
				'id' => $q->id,
				'jenis' => $jenis,
				'namaObat' => $q->obat->nama_obat,
				'kodeBatch' => $q->batch->no_bacth,
				'merk' => $q->batch->merk,
				'jumlah' => $q->jumlah,
				'tgl' => $q->tgl,
			]);
		}

		return $arrdip;
	}
	public function actionTransaksiApotek($start, $end)
	{
		$transaksi = ObatTransaksi::find()->where(['between', 'DATE_FORMAT(tgl,"%Y-%m-%d")', $start, $end])->andwhere(['status' => 2])->all();
		$arrtran = array();
		$total = 0;
		$total_yanmas = 0;
		foreach ($transaksi as $trx) {
			$total += $trx->total_harga;
			$total_yanmas += $trx->total_bayar;
			array_push($arrtran, [
				'id' => $trx->id,
				'kodeResep' => $trx->kode_resep,
				'tglResep' => $trx->tgl,
			]);
		}
		return array(
			'resep' => $arrtran,
			'totalTransaksi' => $total,
			'totalYanmas' => $total_yanmas,
			'totalBpjs' => $total - $total_yanmas,
		);
	}
	// public function actionMinStok($jenis){
	// $obat = Obat::find()->all();
	// $bacth = ObatBatch::find()->where(['idobat'=>$idobat])->all();
	// $stok_apotek = 0;
	// $stok_gudang = 0;
	// foreach($bacth as $b):
	// $stok_apotek += $b->stok_apotek;
	// $stok_gudang += $b->stok_gudang;
	// endforeach;
	// }
}
