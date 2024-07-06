<?php
namespace common\components;
use yii\base\Component;
use Yii;

class HfisFunction extends Component{
	//
	protected $consId;
    protected $secretKey;
    protected $userKey;
    public function __construct()
    {
        parent::__construct();
        $this->consId = '29250';
        $this->secretKey = '5lQ5E30F4C';
        //$this->userKey = 'c898a2018239f9b32dda4d5a00792f79';
    }
	public static function allowedDomains()
	{
		return [
		   '*' , 
		   'http://localhost:3000',
		];
	}  
	function searchForId($dokter,$array) {
		
	   foreach ($array as $key => $val) {
		   if ($val['kodedokter'] == $dokter) {
			   $data_json = json_encode($val, true);
			   return $data_json;
		   }
	   }
	   return null;
	}
	//get poli
	function get_poli(){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/ref/poli',1);	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	//taks
	function update_taks($post){
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/updatewaktu',1,$post);	
		$data_json=json_decode($response, true);
		return $data_json;
	}
	function dashboard_tanggal(){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/dashboard/waktutunggu/tanggal/'.$tgl.'/waktu/'.$waktu,1);	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	function list_taks($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/getlisttask',1,$post);	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	//get dokter
	function update_jadwal($post){
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/jadwaldokter/updatejadwaldokter',1,$post);	
		$data_json=json_decode($response, true);
		return $data_json;
	}
	function add_antrian($post){
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/add',1,$post);	
		$data_json=json_decode($response, true);
		return $data_json;
	}
	function batal_antrian($post){
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/batal',1,$post);	
		$data_json=json_decode($response, true);
		return $data_json;
	}
	function get_dokter(){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/ref/dokter',1);	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	//jadwal dokter
	function get_jadwaldokter($kode,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;			
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn-dev.bpjs-kesehatan.go.id/antreanrsdev/jadwaldokter/kodepoli/'.$kode.'/tanggal/'.$tgl,1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return $data_json['metadata'];
		}else{
			return $data_json['metadata'];
		}
		
	}
	//hitung jadwal dokter
	function get_jadwalcount($kode,$tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;	
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/jadwaldokter/kodepoli/'.$kode.'/tanggal/'.$tgl,1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return array(
				'jadwal'=>count($final),
			);
		}else{
			return array(
				'jadwal'=>0,
			);
		}
		
	}
	
	
		
	
}

?>