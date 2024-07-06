<?php

namespace common\components;

use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class VclaimFunction extends Component
{
	//
	public static function allowedDomains()
	{
		return [
			'*',  // star allows all domains
			'http://localhost:3000',
		];
	}
	protected $consId;
	protected $secretKey;
	protected $userKey;
	public function __construct()
	{
		parent::__construct();
		$this->consId = '29855';
		$this->secretKey = '3rU307868B';
		//$this->userKey = 'c898a2018239f9b32dda4d5a00792f79';
	}
	function updateKamar(){
		$model = Ruangan::find()->all();
		// $model = Ruangan::find()->where(['kode_kelas'=>'NON'])->all();
		$response = array();
		foreach($model as $model){
			$bed = RuanganBed::find()->where(['idruangan'=>$model->id])->andwhere(['terisi'=>0])->andwhere(['status'=>1])->count();
			$arrdip= json_encode(array(	
				"kodekelas"=>$model->kode_kelas, 
				"koderuang"=>$model->kode_ruangan, 
				"namaruang"=>"Ruang ". $model->nama_ruangan, 
				"kapasitas"=>$model->kapasitas, 
				"tersedia"=>$bed,
				"tersediapria"=>"0", 
				"tersediawanita"=>"0", 
				"tersediapriawanita"=>$bed,
			));
			// array_push($arrdip,[
			// 	"kodekelas"=>$model->kode_kelas, 
			// 	"koderuang"=>$model->kode_ruangan, 
			// 	"namaruang"=>"Ruang ". $model->nama_ruangan, 
			// 	"kapasitas"=>$model->kapasitas, 
			// 	"tersedia"=>$bed,
			// 	"tersediapria"=>"0", 
			// 	"tersediawanita"=>"0", 
			// 	"tersediapriawanita"=>$bed,
			// ]);
			$data_responde = Yii::$app->kazo->bpjs_content_sulaiman(Yii::$app->params['baseUrlaplicares'].'/bed/update/0120R012',$arrdip);
			// Yii::$app->kazo->bpjs_content(Yii::$app->params['baseUrlaplicares'].'/bed/create/0171R001',$arrdip);
			$r = json_decode($data_responde);
			// if($r->metadata->code == 0){
			// 	Yii::$app->kazo->bpjs_content(Yii::$app->params['baseUrlaplicares'].'/bed/create/0171R001',$arrdip);
			// }
			$response[] = $r;
		}
		
		return json_encode($response);
	}
	//peserta by no kartu
// 	function updateKamar(){
// 		$model = Ruangan::find()->all();
// 		$response = array();
// 		foreach($model as $model){
// 			$bed = RuanganBed::find()->where(['idruangan'=>$model->id])->andwhere(['terisi'=>0])->andwhere(['status'=>1])->count();
// 			$arrdip= json_encode(array(	
// 				"kodekelas"=>$model->kode_kelas, 
// 				"koderuang"=>$model->kode_ruangan, 
// 				"namaruang"=>"Ruang ". $model->nama_ruangan, 
// 				"kapasitas"=>$model->kapasitas, 
// 				"tersedia"=>$bed,
// 				"tersediapria"=>"0", 
// 				"tersediawanita"=>"0", 
// 				"tersediapriawanita"=>$bed,
// 			));
// 			// array_push($arrdip,[
// 			// 	"kodekelas"=>$model->kode_kelas, 
// 			// 	"koderuang"=>$model->kode_ruangan, 
// 			// 	"namaruang"=>"Ruang ". $model->nama_ruangan, 
// 			// 	"kapasitas"=>$model->kapasitas, 
// 			// 	"tersedia"=>$bed,
// 			// 	"tersediapria"=>"0", 
// 			// 	"tersediawanita"=>"0", 
// 			// 	"tersediapriawanita"=>$bed,
// 			// ]);
// 			$response[] =	Yii::$app->kazo->bpjs_content('https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest/bed/update/0120R012',$arrdip);
// 		}
		
// 		return json_encode($response);
// 	}
	function get_pesertanobpjs($noKartu, $tglSep)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Peserta/nokartu/' . $noKartu . '/tglSEP/' . $tglSep, 2);
		// return $response;
		$data_json = json_decode($response, true);
		if ($data_json['metaData']['code'] == 200) {

			$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
			$data_json3 = Yii::$app->kazo->decompress($data_json2);
			$final = json_decode($data_json3, true);
			return [
				'metaData' => $data_json['metaData'],
				'response' => $final,
			];
		} else {
			return $data_json;
		}
	}
	function get_historipasien($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bkonten('simrs.rsausulaiman.com/api/rawatjalan?id=' . $id);
		$data_json = json_decode($response, true);

		return $data_json;
	}
	function get_historiinap($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bkonten('simrs.rsausulaiman.com/api/rawatinap?id=' . $id);
		$data_json = json_decode($response, true);

		return $data_json;
	}
	function get_historiugd($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bkonten('simrs.rsausulaiman.com/api/rawatugd?id=' . $id);
		$data_json = json_decode($response, true);

		return $data_json;
	}
	function get_peserta($noKartu, $tglSep)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Peserta/nokartu/' . $noKartu . '/tglSEP/' . $tglSep, 2);
		// return $response;
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);
		$final = json_decode($data_json3, true);
		return $final;
	}
	// peserta nik
	function get_peserta_nik($nik, $tglSep)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/Peserta/nik/' . $nik . '/tglSEP/' . $tglSep, 2);
		$data_json = json_decode($response, true);
		// return $data_json;
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//faskes
	function get_faskes($id, $jenis)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/faskes/' . $id . '/' . $jenis, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//poliklinik
	function get_poli($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . 'referensi/poli/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//DOKTER
	function get_dpjp($id, $layanan, $tgl)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/dokter/pelayanan/' . $layanan . '/tglPelayanan/' . $tgl . '/Spesialis/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//icd 10
	function get_icd10($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/diagnosa/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//icd 9
	function get_icd9($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/procedure/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//provinsi
	function get_provinsi()
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/propinsi', 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//kabupaten
	function get_kabupaten($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/kabupaten/propinsi/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//kecamatan
	function get_kecamatan($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/kecamatan/kabupaten/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//diagnosa PRB
	function get_diagnosaprb()
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/diagnosaprb', 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//Obat PRB
	function get_obatprb($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/obatprb/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//KELAS RAWAT
	function get_kelasrawat()
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/kelasrawat', 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $final;
	}
	//Dokter
	function get_dokter($id)
	{
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;
		$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$key = $consId . $pssword . $tStamp;
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'] . '/referensi/dokter/' . $id, 2);
		$data_json = json_decode($response, true);
		$data_json2 = Yii::$app->kazo->stringDecrypt($key, $data_json['response']);
		$data_json3 = Yii::$app->kazo->decompress($data_json2);

		$final = json_decode($data_json3, true);
		return $data_json3;
	}
	function listkamar()
	{
		$response = Yii::$app->kazo->bpjs_content('https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest/bed/read/0120R012/1/10');
		$data_json = json_decode($response);
		return $data_json;
	}
	function listkamarsolo()
	{
		$response = Yii::$app->kazo->bpjs_content_solo('https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest/bed/read/0171R001/1/20');
		$data_json = json_decode($response);
		
	}
	function updateKamarSolo($data)
	{
		$response = Yii::$app->kazo->bpjs_content_solo('https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest/bed/update/0171R001', $data);
		return $response;
	}
	function updateKamar2()
	{
		$modell = Ruangan::find()->andwhere(['idjenis' => 1])->limit(2)->all();
		$array = array();
		foreach ($modell as $model) {
			$bed = RuanganBed::find()->where(['idruangan' => $model->id])->andwhere(['terisi' => 0])->andwhere(['status' => 1])->count();
			$data = [
				"kodekelas" => $model->kode_kelas,
				"koderuang" => $model->kode_ruangan,
				"namaruang" => "Ruang " . $model->nama_ruangan,
				"kapasitas" => $model->kapasitas,
				"tersedia" => $bed,
				"tersediapria" => "0",
				"tersediawanita" => "0",
			];
			$data_json = json_encode($data);
			$response = Yii::$app->kazo->bpjs_content('https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest/bed/update/0120R012', $data_json);
			array_push($array, [
				'response' => $response,
			]);
		}
		return $array;
		//return $array;
	}
	function updateKamar3()
	{
		$model = Ruangan::find()->limit(3)->all();
		$array = array();
		foreach ($model as $model) {
			$bed = RuanganBed::find()->where(['idruangan' => $model->id])->andwhere(['terisi' => 0])->andwhere(['status' => 1])->count();
			$arrdip = json_encode(array(
				"kodekelas" => $model->kode_kelas,
				"koderuang" => $model->kode_ruangan,
				"namaruang" => "Ruang " . $model->nama_ruangan,
				"kapasitas" => $model->kapasitas,
				"tersedia" => $bed,
				"tersediapria" => "0",
				"tersediawanita" => "0",
				"tersediapriawanita" => $bed,
			));
			array_push($array,[
			    "kodekelas" => $model->kode_kelas,
				"koderuang" => $model->kode_ruangan,
				"namaruang" => "Ruang " . $model->nama_ruangan,
				"kapasitas" => $model->kapasitas,
				"tersedia" => $bed,
				"tersediapria" => "0",
				"tersediawanita" => "0",
				"tersediapriawanita" => $bed,
				]);
			//return $arrdip;

// 			$response = Yii::$app->kazo->bkonten('https://simrs.rsausulaiman.com/api/pulangsulaiman', $arrdip);
// 			$data_json = json_decode($response, true);
		}
		return $array;
	}
}
