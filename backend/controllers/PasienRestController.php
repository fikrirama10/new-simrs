<?php

namespace backend\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\Pasien;
use common\models\DataJenjangusia;
use common\models\DataAgama;
use common\models\DokterJadwal;
use common\models\DataPendidikan;
use common\models\DataEtnis;
use common\models\PasienAlamat;
use common\models\RawatJenis;
use common\models\SoapRajalicdx;
use common\models\Rawat;
use common\models\RawatRuangan;
use common\models\RuanganBed;
use common\models\Ruangan;
use common\models\Poli;
use common\models\DokterKuota;
use common\models\Klpcm;

class PasienRestController extends \yii\rest\Controller
{
	public static function allowedDomains()
	{
		return [
			'*',  // star allows all domains
			'http://localhost:3000',
		];
	}

	public $enableCsrfValidation = false;
	public function actionTesSep()
	{
		$sep = array(
			"request" => array(
				"t_sep" => array(
					"noKartu" => "0001112230666",
					"tglSep" => "2017-10-18",
					"ppkPelayanan" => "0301R001",
					"jnsPelayanan" => "2",
					"klsRawat" => "3",
					"noMR" => "123456",
					"rujukan" => array(
						"asalRujukan" => "1",
						"tglRujukan" => "2017-10-17",
						"noRujukan" => "1234567",
						"ppkRujukan" => "00010001"
					),

					"catatan" => "test",
					"diagAwal" => "A00.1",
					"poli" => array(
						"tujuan" => "INT",
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
						"penjamin" => array(
							"penjamin" => "1",
							"tglKejadian" => "2018-08-06",
							"keterangan" => "kll",
							"suplesi" => array(
								"suplesi" => "0",
								"noSepSuplesi" => "0301R0010718V000001",
								"lokasiLaka" => array(
									"kdPropinsi" => "03",
									"kdKabupaten" => "0050",
									"kdKecamatan" => "0574"
								)
							),

						),
					),
					"skdp" => array(
						"noSurat" => "000002",
						"kodeDPJP" => "31661"
					),
					"noTelp" => "081919999",
					"user" => "Coba Ws"

				),

			)
		);

		return $sep;
	}
	public function actionMonitoringKunjungan($tgl)
	{
		$pasien = Pasien::find()->where(['tgldaftar' => $tgl])->all();
		$jenis = RawatJenis::find()->all();
		$rajal = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => $tgl])->andwhere(['idjenisrawat' => 1])->andwhere(['<>', 'status', 5])->all();
		$ranap = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => $tgl])->andwhere(['idjenisrawat' => 2])->andwhere(['<>', 'status', 5])->all();
		$ugd = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")' => $tgl])->andwhere(['idjenisrawat' => 3])->andwhere(['<>', 'status', 5])->all();

		return array(
			'pasienBaru' => count($pasien),
			'ranap' => count($ranap),
			'rajal' => count($rajal),
			'ugd' => count($ugd),
		);
	}
	public function actionFindPasien($rm)
	{
		$pasien = Pasien::find()->where(['no_rm' => $rm])->one();
		if ($pasien) {
			return array(
				'id' => $pasien->id,
				'nama_pasien' => $pasien->nama_pasien,
				'no_rm' => $pasien->no_rm,
				'kode' => 200,
			);
		} else {
			return array(
				'kode' => 404,
			);
		}
	}
	public function actionAntrianPasien($tgl)
	{
		$poli = Poli::find()->where(['ket' => 1])->all();
		$arrpol = array();
		foreach ($poli as $p) :
			$kuota = DokterKuota::find()->where(['idpoli' => $p->id])->andwhere(['tgl' => $tgl])->all();
			$arrkuota = array();
			$arrjad = array();
			$jadwal = DokterJadwal::find()->where(['idhari' => date('N', strtotime($tgl))])->andwhere(['idpoli' => $p->id])->all();
			foreach ($kuota as $k) {

				array_push($arrkuota, [
					'iddokter' => $k->iddokter,
					'dokter' => $k->dokter->nama_dokter,
					'kuota' => $k->kuota,
					'terdaftar' => $k->terdaftar,
					'sisa' => $k->sisa,

				]);
			}
			foreach ($jadwal as $j) {

				array_push($arrjad, [
					'iddokter' => $j->iddokter,
					'dokter' => $j->dokter->nama_dokter,
					'jam_selesai' => $j->jam_selesai,
					'jam_mulai' => $j->jam_mulai,
					'kuota' => $j->kuota,

				]);
			}
			array_push($arrpol, [
				'idpoli' => $p->id,
				'namaPoli' => $p->poli,
				'kuota' => $arrkuota,
				'jadwalDokter' => $arrjad,
			]);
		endforeach;
		return $arrpol;
	}
	public function actionJumlahPasien($start = '', $end = '')
	{
		$jenis_rawat = RawatJenis::find()->all();
		$arrdip = array();
		foreach ($jenis_rawat as $jr) {
			$rawat_baru = Rawat::find()->where(['idjenisrawat' => $jr->id])->andwhere(['between', 'DATE_FORMAT(tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['kunjungan' => 1])->count();
			$pasien_baru = Rawat::find()->where(['idjenisrawat' => $jr->id])->andwhere(['between', 'DATE_FORMAT(tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['kunjungan' => 1])->groupBy('no_rm')->count();
			$pasien_lama = Rawat::find()->where(['idjenisrawat' => $jr->id])->andwhere(['between', 'DATE_FORMAT(tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['kunjungan' => 2])->groupBy('no_rm')->count();
			$rawat_lama = Rawat::find()->where(['idjenisrawat' => $jr->id])->andwhere(['between', 'DATE_FORMAT(tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['kunjungan' => 2])->count();
			array_push($arrdip, [
				'jenisRawat' => $jr->jenis,
				'pasienBaru' => $pasien_baru,
				'pasienLama' => $pasien_lama,
			]);
		}
		return $arrdip;
	}
	public function actionBorDetail($start = '', $end = '')
	{
		$ruangan = Ruangan::find()->where(['status' => 1])->andwhere(['idjenis' => 1])->all();
		$arrru = array();
		$date1 = date_create($start);
		//tgl_aakhir
		$date2 = date_create($end);
		//selisih hari
		$diff = date_diff($date1, $date2);
		$hari = $diff->format("%a") + 1;
		foreach ($ruangan as $r) :
			$los = Rawat::find()->where(['between', 'DATE_FORMAT(tglpulang,"%Y-%m-%d")', $start, $end])->andwhere(['idruangan' => $r->id])->andwhere(['idjenisrawat' => 2])->andwhere(['status' => 4])->average('los');
			$bed =  RuanganBed::find()->where(['status' => 1])->andwhere(['idruangan' => $r->id])->count();
			$hari_rawat = Rawat::find()->where(['between', 'DATE_FORMAT(tglpulang,"%Y-%m-%d")', $start, $end])->andwhere(['idjenisrawat' => 2])->andwhere(['status' => 4])->andwhere(['idruangan' => $r->id])->sum('los');
			$bor = ($hari_rawat / ($bed * $hari)) * 100;
			array_push($arrru, [
				'ruangan' => $r->nama_ruangan,
				'hariRawat' => $hari_rawat,
				'hari' => $hari,
				'bed' => $bed,
				'bor' => $bor,
				'los' => $los,

			]);
		endforeach;
		return $arrru;
	}
	public function actionBor($tgl_awal = '', $tgl_akhir = '')
	{
		//tgl_awal
		$date1 = date_create($tgl_awal);
		//tgl_aakhir
		$date2 = date_create($tgl_akhir);
		//selisih hari
		$diff = date_diff($date1, $date2);
		$hari = $diff->format("%a") + 1;
		//tt
		$bed =  RuanganBed::find()->where(['status' => 1])->count();
		//harirawat
		$hari_rawat = Rawat::find()->where(['between', 'DATE_FORMAT(tglpulang,"%Y-%m-%d")', $tgl_awal, $tgl_akhir])->andwhere(['idjenisrawat' => 2])->andwhere(['status' => 4])->sum('los');
		//pasien keluar hidup / mati
		$pasienHm = Rawat::find()->where(['between', 'DATE_FORMAT(tglpulang,"%Y-%m-%d")', $tgl_awal, $tgl_akhir])->andwhere(['idjenisrawat' => 2])->andwhere(['status' => 4])->count();
		//los
		$los = Rawat::find()->where(['between', 'DATE_FORMAT(tglpulang,"%Y-%m-%d")', $tgl_awal, $tgl_akhir])->andwhere(['idjenisrawat' => 2])->andwhere(['status' => 4])->average('los');
		//bor
		$bor = ($hari_rawat / ($bed * $hari)) * 100;
		//bto
		$bto = $pasienHm / $bed;
		//bto langsung
		$btodirect = ($bor / 100) * $los;
		//toi
		if ($pasienHm > 0) {
			$toi = (($bed * $hari) - $hari_rawat) / $pasienHm;
			$pasienHm = $pasienHm;
		} else {
			$toi = 0;
			$pasienHm = 0;
		}
		return array(
			'harirawat' => $hari_rawat,
			'periode' => $hari,
			'pasienKeluar' => $pasienHm,
			'bed' => $bed,
			'bor' => $bor,
			'los' => $los,
			'bto' => $bto,
			'btoDirect' => $btodirect,
			'toi' => $toi,
			'hari' => $hari,
		);
	}
	public function actionIcd10jenis($awal = '', $akhir = '', $jenis = '')
	{
		$diagnosa = Klpcm::find()->joinWith(['rawat as rawat'])->select(['klpcm.*', 'COUNT(klpcm.icdx) AS jml'])->where(['klpcm.idjenisrawat' => $jenis])->andwhere(['<>', 'klpcm.icdx', ''])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('klpcm.icdx')->orderBy(['jml' => SORT_DESC]);
		$dataDiagnosa = $diagnosa->all();
		$arrdip = array();
		foreach ($dataDiagnosa as $q) {
			array_push($arrdip, [
				'diagnosa' => substr($q->icdx, 0, 5),
				'nama' => $q->icdx,
				'jumlah' => (int) $q->jml,

			]);
		}
		return $arrdip;
	}
	public function actionIcd10($awal = '', $akhir = '', $limit = '')
	{
		$jenisRawat = RawatJenis::find()->all();
		$arrdip = array();
		// $diagnosa = Klpcm::find()->joinWith(['rawat as rawat'])->select(['klpcm.*', 'COUNT(klpcm.icdx) AS jml'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('klpcm.icdx')->orderBy(['jml' => SORT_DESC])->all(); 
		// return $diagnosa;
		foreach ($jenisRawat as $jr) {
			if ($limit == '') {
				$diagnosa = Klpcm::find()->joinWith(['rawat as rawat'])->select(['klpcm.*', 'COUNT(klpcm.icdx) AS jml'])->where(['klpcm.idjenisrawat' => $jr->id])->andwhere(['<>', 'klpcm.icdx', ''])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('klpcm.icdx')->orderBy(['jml' => SORT_DESC]);
				// $diagnosa = SoapRajalicdx::find()->joinWith(['rawat as rawat'])->select(['soap_rajalicdx.*', 'COUNT(soap_rajalicdx.icdx) AS jml'])->where(['soap_rajalicdx.idjenisrawat' => $jr->id])->andwhere(['<>', 'soap_rajalicdx.icdx', ''])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('soap_rajalicdx.icdx')->orderBy(['jml' => SORT_DESC]);
				$dataDiagnosa = $diagnosa->all();

			} else {
				// $diagnosa = SoapRajalicdx::find()->joinWith(['rawat as rawat'])->select(['soap_rajalicdx.*', 'COUNT(soap_rajalicdx.icdx) AS jml'])->where(['soap_rajalicdx.idjenisrawat' => $jr->id])->andwhere(['<>', 'soap_rajalicdx.icdx', ''])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('soap_rajalicdx.icdx')->orderBy(['jml' => SORT_DESC])->limit($limit);
				$diagnosa = Klpcm::find()->joinWith(['rawat as rawat'])->select(['klpcm.*', 'COUNT(klpcm.icdx) AS jml'])->where(['klpcm.idjenisrawat' => $jr->id])->andwhere(['<>', 'klpcm.icdx', ''])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $awal, $akhir])->groupBy('klpcm.icdx')->orderBy(['jml' => SORT_DESC])->limit($limit);
				$dataDiagnosa = $diagnosa->all();
			}

			$arrdip2 = array();
			foreach ($dataDiagnosa as $q) {
				array_push($arrdip2, [
					'diagnosa' => substr($q->icdx, 0, 5),
					'nama' => $q->icdx,
					'jumlah' => (int) $q->jml,

				]);
			}
			array_push($arrdip, [
				'id' => $jr->id,
				'nama' => $jr->jenis,
				'active' => $jr->active,
				'diagnosa' => $arrdip2,
			]);
		}
		return $arrdip;
	}
	// public function actionPasienSex($start, $end, $rawat = null)
	// {
	// 	$pasienL = Pasien::find()->where(['jenis_kelamin' => 'L'])->andwhere(['between', 'tgldaftar', $start, $end])->count();
	// 	$pasienP = Pasien::find()->where(['jenis_kelamin' => 'P'])->andwhere(['between', 'tgldaftar', $start, $end])->count();
	// 	$jenjang = DataJenjangusia::find()->all();
	// 	$agama = DataAgama::find()->all();
	// 	$etnis = Pasien::find()->andwhere(['between', 'tgldaftar', $start, $end])->groupBy('idetnis')->orderBy(['count(idetnis)' => SORT_DESC])->limit(10)->all();
	// 	// $kelurahan = PasienAlamat::find()->joinWith('pasien as pasien')->where(['utama'=>1])->andwhere(['between','pasien.tgldaftar',$start,$end])->groupBy('idkel')->orderBy(['count(idkel)'=>SORT_DESC])->limit(10)->all();
	// 	$kelurahan = Rawat::find()->joinWith(['pasien', 'pasien.desa'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->groupBy('pasien.idkelurahan')->all();
	// 	// $suku = DataEtnis::find()->all();

	// 	$pendidikan = DataPendidikan::find()->all();
	// 	$arrdip = array();
	// 	foreach ($jenjang as $j) :
	// 		// $pLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		// $pPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		// $pTotal = Pasien::find()->andwhere(['idusia'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
	// 		// $pLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		$pLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idusia' => $j->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idusia' => $j->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pTotal = $pLaki + $pPerempuan;
	// 		array_push($arrdip, [
	// 			'id' => $j->id,
	// 			'nama' => $j->jenjang,
	// 			'laki' => $pLaki,
	// 			'perempuan' => $pPerempuan,
	// 			'total' => $pTotal,
	// 		]);
	// 	endforeach;
	// 	$arrdip2 = array();
	// 	foreach ($agama as $a) :
	// 		// $aLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
	// 		// $aPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
	// 		// $aTotal = Pasien::find()->andwhere(['idagama'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
	// 		$aLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idagama' => $a->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$aPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idagama' => $a->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$aTotal = $aLaki + $aPerempuan;
	// 		array_push($arrdip2, [
	// 			'id' => $a->id,
	// 			'nama' => $a->agama,
	// 			'laki' => $aLaki,
	// 			'perempuan' => $aPerempuan,
	// 			'total' => $aTotal,
	// 		]);
	// 	endforeach;
	// 	$arrdip3 = array();
	// 	// foreach($etnis as $s):
	// 	// 	$sLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idetnis'=>$s->idetnis])->count();
	// 	// 	$sPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idetnis'=>$s->idetnis])->count();
	// 	// 	$sTotal = Pasien::find()->andwhere(['idetnis'=>$s->idetnis])->andwhere(['between','tgldaftar',$start,$end])->count();
	// 	// 	array_push($arrdip3,[
	// 	// 			'id' => $s->idetnis,
	// 	// 			'nama' => $s->etnis->etnis,
	// 	// 			'laki' => $sLaki,
	// 	// 			'perempuan' => $sPerempuan,
	// 	// 			'total' => $sTotal,
	// 	// 		]);
	// 	// endforeach;
	// 	$arrdip4 = array();
	// 	foreach ($pendidikan as $pn) :
	// 		$pnLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idpendidikan' => $pn->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pnPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['idjenisrawat' => $rawat])->andwhere(['pasien.idpendidikan' => $pn->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pnTotal = $pnLaki + $pnPerempuan;
	// 		if ($pnTotal > 0) {
	// 			array_push($arrdip4, [
	// 				'id' => $pn->id,
	// 				'nama' => $pn->pendidikan,
	// 				'laki' => $pnLaki,
	// 				'perempuan' => $pnPerempuan,
	// 				'total' => $pnTotal,
	// 			]);
	// 		}

	// 	endforeach;
	// 	$arrdip5 = array();
	// 	foreach ($kelurahan as $k) {
	// 		// $kLaki = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'L'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();
	// 		// $kPerempuan = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'P'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();

	// 		// $aLaki
	// 		$kLaki = Rawat::find()->joinWith(['pasien'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['pasien.idkelurahan' => $k->pasien->idkelurahan])->andwhere(['pasien.jenis_kelamin' => 'L'])->andwhere(['idjenisrawat' => $rawat])->count();
	// 		$kPerempuan = Rawat::find()->joinWith(['pasien'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['pasien.idkelurahan' => $k->pasien->idkelurahan])->andwhere(['pasien.jenis_kelamin' => 'P'])->andwhere(['idjenisrawat' => $rawat])->count();
	// 		$jumlah = $kLaki + $kPerempuan;
	// 		if ($jumlah > 0) {
	// 			if($k->pasien->idkelurahan != null){
	// 				array_push($arrdip5, [
	// 					'id' => $k->idkel,
	// 					'nama' => $k->pasien->idkelurahan == null ? '' : $k->pasien->desa->nama,
	// 					'laki' => $kLaki,
	// 					'perempuan' => $kPerempuan,
	// 					'total' => $jumlah
	// 				]);
	// 			}
				
	// 		}
	// 	}
	// 	return array(
	// 		'laki' => array(
	// 			'jumlah' => $pasienL,
	// 		),
	// 		'perempuan' => array(
	// 			'jumlah' => $pasienP,
	// 		),
	// 		'total' => array(
	// 			'jumlah' => $pasienL + $pasienP,
	// 		),
	// 		'jenjangUsia' => $arrdip,
	// 		'agama' => $arrdip2,
	// 		'etnis' => [],
	// 		'pendidikan' => $arrdip4,
	// 		'kelurahan' => $arrdip5,

	// 	);
	// }
	// public function actionPasienSex($start, $end, $rawat = null) 
	// {
	// 	$pasienL = Pasien::find()->where(['jenis_kelamin' => 'L'])->andwhere(['between', 'tgldaftar', $start, $end])->count();
	// 	$pasienP = Pasien::find()->where(['jenis_kelamin' => 'P'])->andwhere(['between', 'tgldaftar', $start, $end])->count();
	// 	$jenjang = DataJenjangusia::find()->all();
	// 	$agama = DataAgama::find()->all();
	// 	$etnis = Pasien::find()->andwhere(['between', 'tgldaftar', $start, $end])->groupBy('idetnis')->orderBy(['count(idetnis)' => SORT_DESC])->limit(10)->all();
	// 	// $kelurahan = PasienAlamat::find()->joinWith('pasien as pasien')->where(['utama'=>1])->andwhere(['between','pasien.tgldaftar',$start,$end])->groupBy('idkel')->orderBy(['count(idkel)'=>SORT_DESC])->limit(10)->all();
	// 	$kelurahan = Rawat::find()->joinWith(['pasien', 'pasien.desa'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->groupBy('pasien.idkelurahan')->all();
	// 	$suku = DataEtnis::find()->all();

	// 	$pendidikan = DataPendidikan::find()->all();
	// 	$arrdip = array();
	// 	foreach ($jenjang as $j) :
	// 		// $pLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		// $pPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		// $pTotal = Pasien::find()->andwhere(['idusia'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
	// 		// $pLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
	// 		$pLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['pasien.idusia' => $j->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['pasien.idusia' => $j->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pTotal = $pLaki + $pPerempuan;
	// 		array_push($arrdip, [
	// 			'id' => $j->id,
	// 			'nama' => $j->jenjang,
	// 			'laki' => $pLaki,
	// 			'perempuan' => $pPerempuan,
	// 			'total' => $pTotal,
	// 		]);
	// 	endforeach;
	// 	$arrdip2 = array();
	// 	foreach ($agama as $a) :
	// 		// $aLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
	// 		// $aPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
	// 		// $aTotal = Pasien::find()->andwhere(['idagama'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
	// 		$aLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['pasien.idagama' => $a->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$aPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['pasien.idagama' => $a->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$aTotal = $aLaki + $aPerempuan;
	// 		array_push($arrdip2, [
	// 			'id' => $a->id,
	// 			'nama' => $a->agama,
	// 			'laki' => $aLaki,
	// 			'perempuan' => $aPerempuan,
	// 			'total' => $aTotal,
	// 		]);
	// 	endforeach;
	// 	$arrdip3 = array();
	// 	foreach ($suku as $s) :
	// 		$sLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['pasien.idetnis' => $s->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$sPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['pasien.idetnis' => $s->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$sTotal = $sLaki + $sPerempuan;
	// 		if ($s->id != '') {
	// 			array_push($arrdip3, [
	// 				'id' => $s->id,
	// 				'nama' => $s->etnis,
	// 				'laki' => $sLaki,
	// 				'perempuan' => $sPerempuan,
	// 				'total' => $sTotal,
	// 			]);
	// 		}

	// 	endforeach;
	// 	$arrdip4 = array();
	// 	foreach ($pendidikan as $pn) :
	// 		$pnLaki = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'L'])->andwhere(['pasien.idpendidikan' => $pn->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pnPerempuan = Rawat::find()->joinWith('pasien')->where(['pasien.jenis_kelamin' => 'P'])->andwhere(['pasien.idpendidikan' => $pn->id])->andwhere(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->count();
	// 		$pnTotal = $pnLaki + $pnPerempuan;
	// 		if ($pnTotal > 0) {
	// 			array_push($arrdip4, [
	// 				'id' => $pn->id,
	// 				'nama' => $pn->pendidikan,
	// 				'laki' => $pnLaki,
	// 				'perempuan' => $pnPerempuan,
	// 				'total' => $pnTotal,
	// 			]);
	// 		}

	// 	endforeach;
	// 	$arrdip5 = array();
	// 	foreach ($kelurahan as $k) {
	// 		// $kLaki = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'L'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();
	// 		// $kPerempuan = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'P'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();

	// 		// $aLaki
	// 		$kLaki = Rawat::find()->joinWith(['pasien'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['pasien.idkelurahan' => $k->pasien->idkelurahan])->andwhere(['pasien.jenis_kelamin' => 'L'])->count();
	// 		$kPerempuan = Rawat::find()->joinWith(['pasien'])->where(['between', 'DATE_FORMAT(rawat.tglmasuk,"%Y-%m-%d")', $start, $end])->andwhere(['pasien.idkelurahan' => $k->pasien->idkelurahan])->andwhere(['pasien.jenis_kelamin' => 'P'])->count();
	// 		$jumlah = $kLaki + $kPerempuan;
	// 		if ($jumlah > 0) {
	// 			if($k->pasien->idkelurahan != null){
	// 				array_push($arrdip5, [
	// 					'id' => $k->idkel,
	// 					'nama' => $k->pasien->idkelurahan == null ? '' : $k->pasien->desa->nama,
	// 					'laki' => $kLaki,
	// 					'perempuan' => $kPerempuan,
	// 					'total' => $jumlah
	// 				]);
	// 			}
				
	// 		}
	// 	}
	// 	return array(
	// 		'laki' => array(
	// 			'jumlah' => $pasienL,
	// 		),
	// 		'perempuan' => array(
	// 			'jumlah' => $pasienP,
	// 		),
	// 		'total' => array(
	// 			'jumlah' => $pasienL + $pasienP,
	// 		),
	// 		'jenjangUsia' => $arrdip,
	// 		'agama' => $arrdip2,
	// 		'etnis' => $arrdip3,
	// 		'pendidikan' => $arrdip4,
	// 		'kelurahan' => $arrdip5,

	// 	);
	// }
	public function actionPasienSex($start,$end){
		$pasienL = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->count();
		$pasienP = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->count();
		$jenjang = DataJenjangusia::find()->all();
		$agama = DataAgama::find()->all();
		$etnis = Pasien::find()->where(['between','tgldaftar',$start,$end])->andwhere(['>','idetnis',0])->groupBy('idetnis')->orderBy(['count(idetnis)'=>SORT_DESC])->limit(10)->all();
		$kelurahan = PasienAlamat::find()->joinWith('pasien as pasien')->where(['utama'=>1])->andwhere(['between','pasien.tgldaftar',$start,$end])->groupBy('idkel')->orderBy(['count(idkel)'=>SORT_DESC])->limit(10)->all();
		// $suku = DataEtnis::find()->all();
		$pendidikan = DataPendidikan::find()->all();
		$arrdip=array();
		foreach($jenjang as $j):
			$pLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
			$pPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idusia'=>$j->id])->count();
			$pTotal = Pasien::find()->andwhere(['idusia'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
			array_push($arrdip,[
					'id' => $j->id,
					'nama' => $j->jenjang,
					'laki' => $pLaki,
					'perempuan' => $pPerempuan,
					'total' => $pTotal,
				]);
		endforeach;
		$arrdip2=array();
		foreach($agama as $a):
			$aLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
			$aPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idagama'=>$a->id])->count();
			$aTotal = Pasien::find()->andwhere(['idagama'=>$j->id])->andwhere(['between','tgldaftar',$start,$end])->count();
			array_push($arrdip2,[
					'id' => $a->id,
					'nama' => $a->agama,
					'laki' => $aLaki,
					'perempuan' => $aPerempuan,
					'total' => $aTotal,
				]);
		endforeach;
		$arrdip3=array();
		foreach($etnis as $s):
			$sLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idetnis'=>$s->idetnis])->count();
			$sPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idetnis'=>$s->idetnis])->count();
			$sTotal = Pasien::find()->andwhere(['idetnis'=>$s->idetnis])->andwhere(['between','tgldaftar',$start,$end])->count();
			array_push($arrdip3,[
					'id' => $s->idetnis,
					'nama' => isset($s->idetnis) ? $s->etnis->etnis:'-',
					'laki' => $sLaki,
					'perempuan' => $sPerempuan,
					'total' => $sTotal,
				]);
		endforeach;
		$arrdip4=array();
		foreach($pendidikan as $pn):
			$pnLaki = Pasien::find()->where(['jenis_kelamin'=>'L'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idpendidikan'=>$pn->id])->count();
			$pnPerempuan = Pasien::find()->where(['jenis_kelamin'=>'P'])->andwhere(['between','tgldaftar',$start,$end])->andwhere(['idpendidikan'=>$pn->id])->count();
			$pnTotal = Pasien::find()->andwhere(['idpendidikan'=>$pn->id])->andwhere(['between','tgldaftar',$start,$end])->count();
			array_push($arrdip4,[
					'id' => $pn->id,
					'nama' => $pn->pendidikan,
					'laki' => $pnLaki,
					'perempuan' => $pnPerempuan,
					'total' => $pnTotal,
				]);
		endforeach;
		$arrdip5=array();
		foreach($kelurahan as $k){
			$kLaki = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'L'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();
			$kPerempuan = PasienAlamat::find()->joinWith(['pasien as pasien'])->where(['pasien.jenis_kelamin'=>'P'])->andwhere(['utama'=>1])->andwhere(['idkel'=>$k->idkel])->count();
			array_push($arrdip5,[
					'id' => $k->idkel,
					'nama' => $k->idkel != null ? $k->kelurahan->nama : '-',
					'laki' => $kLaki,
					'perempuan' => $kPerempuan,
				]);
		}
		return array(
				'laki'=>array(
					'jumlah'=>$pasienL,
				),
				'perempuan'=>array(
					'jumlah'=>$pasienP,
				),
				'total'=>array(
					'jumlah'=>$pasienL + $pasienP,
				),
				'jenjangUsia'=>$arrdip,
				'agama'=>$arrdip2,
				'etnis'=>$arrdip3, 
				'pendidikan'=>$arrdip4,
				'kelurahan'=>$arrdip5,
			
		);
	}
}
