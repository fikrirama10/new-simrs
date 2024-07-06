<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class BridgingFunction extends Component{
	
	public static function allowedDomains()
	{
		return [
		   '*' ,  // star allows all domains
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
	//Peserta 
	
	//Get Pasien NO BPJS
	function get_pesertanobpjs($noKartu,$tglSep){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Peserta/nokartu/'.$noKartu.'/tglSEP/'.$tglSep,2);	
		// return $response;
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
				
			];
		}else{
			return $data_json;
		}
	}
	//Get Pasien NIK
	function get_pesertanik($nik,$tglSep){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Peserta/nik/'.$nik.'/tglSEP/'.$tglSep,2);	
		// return $response;
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
				
			];
		}else{
			return $data_json;
		}
	}
	
	//Referensi
	
	//ICD 10
	function get_icd10($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/diagnosa/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//ICD 9
	function get_icd9($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/procedure/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Poli	
	function get_poli($id){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/poli/'.$id,2);		
		$data_json=json_decode($response, true);
		// return $data_json;
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Faskes 
	function get_faskes($id,$jenis){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/faskes/'.$id.'/'.$jenis,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Pencarian data dokter DPJP untuk pengisian DPJP Layan
	function get_dpjp($id,$layanan,$tgl){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/dokter/pelayanan/'.$layanan.'/tglPelayanan/'.$tgl.'/Spesialis/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Provinsi
	function get_provinsi(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/propinsi',2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Kabupaten
	function get_kabupaten($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/kabupaten/propinsi/'.$id,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Kecamatan
	function get_kecamatan($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/kecamatan/kabupaten/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}	
	
	//Diagnosa PRB
	function get_diagnosaprb(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/diagnosaprb',2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}

	//Obat PRB
	function get_obatprb($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/obatprb/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}

	//Kelas Rawat
	function get_kelasrawat(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/kelasrawat',2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Pencarian data dokter dalam faskes sesuai consid
	function get_dokter($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/dokter/'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}

	//Spesialistik
	function get_spesialistik($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/spesialistik'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Ruang Rawat
	function get_ruangrawat($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/ruangrawat'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Cara Keluar
	function get_carakeluar(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/carakeluar',2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	//Pasca Pulang
	function get_pascapulang($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/referensi/pascapulang'.$id,2);		
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){		
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);			
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,				
			];
		}else{
			return $data_json;
		}
	}
	
	
	//SEP
	//Pembuatan SEP
	
	//Insert SEP
	
	function insert_sep($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/2.0/insert',2,$post);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Update SEP
	function update_sep($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/2.0/update',2,$post,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Hapus SEP
	function delete_sep($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/2.0/delete',2,$post,1);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Cari SEP
	function cari_sep($nosep){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/'.$nosep,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Potensi Suplesi Jasa Raharja
	//Pencarian data potensi SEP Sebagai Suplesi Jasa Raharja	
	function suplesi($nokartu,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		// $response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/sep/JasaRaharja/Suplesi/'.$nokartu.'/tglPelayanan/'.$tgl,2);	
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/sep/JasaRaharja/Suplesi/'.$nokartu.'/tglPelayanan/'.$tgl,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Pencarian data SEP Induk Kecelakaan Lalu Lintas
	function data_induk_kecelakaan($nokartu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/sep/KllInduk/List/'.$nokartu,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Approval Penjaminan SEP
	//Pengajuan
	
	function pengajuan($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Sep/pengajuanSEP',2,$post);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Approval Pengajuan SEP	
	function pengajuan_sep($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Sep/aprovalSEP',2,$post);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Update Tgl Pulang SEP
	function update_pulang($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/2.0/updtglplg',2,$post,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	function list_data_pulang($bulan,$tahun,$filter=''){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Sep/updtglplg/list/bulan/'.$bulan.'/tahun/'.$tahun.'/'.$filter,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Integrasi SEP dan Inacbg
	// Pencarian No.SEP untuk Aplikasi Inacbg 4.1
	function inacbg($sep){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/sep/cbg/'.$sep,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//SEP Internal
	//Data SEP Internal
	function sep_internal($sep){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/Internal/'.$sep,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Hapus SEP Internal
	function delete_sep_internal($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/Internal/delete',2,$post,1);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Finger Print
	
	//Get Finger Print
	function get_finger($nokartu,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/SEP/FingerPrint/Peserta/'.$nokartu.'/TglPelayanan/'.$tgl,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Rujukan
	//Cari Rujukan nomor rujukan
	function rujukan_nomor($norujukan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/'.$norujukan,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	function rujukan_nomor_rs($norujukan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/RS/'.$norujukan,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Cari Rujukan nomor kartu
	function rujukan_kartu($nokartu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/Peserta/'.$nokartu,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	function rujukan_kartu_rs($nokartu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/RS/Peserta/'.$nokartu,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//List Rujukan
	
	function rujukan_list($nokartu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/List/Peserta/'.$nokartu,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	function rujukan_list_rs($nokartu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/RS/List/Peserta/'.$nokartu,2);	
		$data_json=json_decode($response, true);
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	//Cek Kunjungan & total SEP
	function cek_rujukan($tgl){
		date_default_timezone_set('UTC');
		$date = date('Y-m-d');
		$date1=date_create($tgl);
		$date2=date_create($date);
		$diff=date_diff($date1,$date2);
		$selisih = $diff->format("%a");
		if($selisih > 90){
			return 0;
		}else{
			return 1;
		}
	}
	function total_sep($faskes,$norujukan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/JumlahSEP/'.$faskes.'/'.$norujukan,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Spesialistik Rujukan
	function get_rujukan_spesialistik($faskes,$tglrujukan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/ListSpesialistik/PPKRujukan/'.$faskes.'/TglRujukan/'.$tglrujukan,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Detail Rujukan keluar
	function detail_rujukan_keluar($norujukan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/Keluar/'.$norujukan,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//List Rujukan Keluar RS
	function list_rujukan_keluarrs($tglAwal,$tglAkhir){
		date_default_timezone_set('UTC');
		$consId =  $this->consId; 	
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/Keluar/List/tglMulai/'.$tglAwal.'/tglAkhir/'.$tglAkhir,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Delete Rujukan
	function hapus_rujukan($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/delete',2,$post,1);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Update Rujukan
	function edit_rujukan($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/2.0/Update',2,$post,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Post Rujukan
	function post_rujukan($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/2.0/insert',2,$post);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	
	
	//Data Rencana Kontrol
	//Surat Kontrol
	function surat_kontrol_sep($nokontrol){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/nosep/'.$nokontrol,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	function surat_kontrol($nokontrol){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/noSuratKontrol/'.$nokontrol,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Data Surat Kontrol Nokka
	function data_surat_kontrol_noka($bulan,$tahun,$noka,$filter){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/ListRencanaKontrol/Bulan/'.$bulan.'/Tahun/'.$tahun.'/Nokartu/'.$noka.'/filter/'.$filter,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Data Surat Kontrol
	function data_surat_kontrol($tglawal,$tglakhir,$filter){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/ListRencanaKontrol/tglAwal/'.$tglawal.'/tglAkhir/'.$tglakhir.'/filter/'.$filter,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Data Dokter SPRI
	function data_dokter_kontrol($jenis,$nomor,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/'.$jenis.'/KdPoli/'.$nomor.'/TglRencanaKontrol/'.$tgl,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Data Poli SPRI
	function data_poli_kontrol($jenis,$nomor,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/ListSpesialistik/JnsKontrol/'.$jenis.'/nomor/'.$nomor.'/TglRencanaKontrol/'.$tgl,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Update SPRI
	function update_spri($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/UpdateSPRI',2,$post,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Insert SPRI
	function insert_spri($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/InsertSPRI',2,$post);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Insert Surat Kontrol
	function insert_surat_kontrol($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/insert',2,$post);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Update Surat Kontrol
	function update_surat_kontrol($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/Update',2,$post,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Delete Surat Kontrol
	function delete_surat_kontrol($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/Delete',2,$post,1);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Monitoring
	//Monitoring Layanan Peserta
	function histori_pelayanan($nokartu,$tglawal,$tglakhir){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/monitoring/HistoriPelayanan/NoKartu/'.$nokartu.'/tglMulai/'.$tglawal.'/tglAkhir/'.$tglakhir,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//Data Kunjungan
	function data_kunjungan($tgl,$pelayanan){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Monitoring/Kunjungan/Tanggal/'.$tgl.'/JnsPelayanan/'.$pelayanan,2);	
		$data_json=json_decode($response, true);
		
		if($data_json['metaData']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metaData'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
	//hfis
	//jadwal dokter
	function hfis_jadwal($kode,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/jadwaldokter/kodepoli/'.$kode.'/tanggal/'.$tgl,3);	
		$data_json=json_decode($response, true);
		
		if($data_json['metadata']['code'] == 200){
			
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metadata'=>$data_json['metadata'],
				'response'=>$final,
			];
		}else{
			return $data_json;
		}
	}
}