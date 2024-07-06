<?php
namespace common\components;
use yii\base\Component;
use common\models\Pasien;
use Yii;

class HfisFunction extends Component{
	//
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
	function searchtaksForId($kodebooking,$taks) {
		$post = array(
            'kodebooking' => $kodebooking,
        );

        $response = Yii::$app->hfis->list_taks($post);
		$data_json = json_encode($response, true);
		//return $data_json;

		if($response['data']['code'] == 200 ){
			foreach ($response['response'] as $key => $val) {
				if ($val['taskid'] == $taks) {
					$data_json = json_encode($val, true);
					return $val['wakturs']; 
				}
			}
		}
		
		return 0;
	 } 
	 function milliseconds()
		{
			$mt = explode(' ', microtime());
			return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
		}
	 function updateTaks($kodebooking,$taks){
		$update = [
            "kodebooking" => $kodebooking,
            "taskid" => $taks,
            "waktu" => $this->milliseconds(),
        ];
        $response = Yii::$app->hfis->update_taks($update);
		if($response['metadata']['code'] == 208){
			$ex = explode(' ',$response['metadata']['message']);
            $ex2 = explode('=',$ex[0]);
			$this->updateTaks($kodebooking,$taks+1);
		}
		return $response;
	 }
	 function taksForId($kodebooking) {
		$post = array(
            'kodebooking' => $kodebooking,
        );

        $response = Yii::$app->hfis->list_taks($post);
		$data_json = json_encode($response, true);
		if($response['data']['code'] == 200){
			rsort($response['response']);
			$array = array_slice($response['response'], 0,1);
			$taskid = $array[0]['taskid'];
			if($taskid < 7){
				return $taskid + 1;
			}else{
				return $taskid;
			}
		}else{
			return 3;
		}
		
	 }  
	 
	 function searchForRm($rm) {
		$pasien = Pasien::find()->where(['no_rm'=>$rm])->one();
		if($pasien){
			return $pasien;
		}else{
			return '0';
		} 	
		
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
	function antri_tanggal($tgl){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/pendaftaran/tanggal/'.$tgl,1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'data'=>$data_json['metadata'],
				'response'=>$final
			];
		}else{
			return [
				'data'=> $data_json['metadata']
			];
		}
	}
	function antri_kodebooking($kode){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/pendaftaran/kodebooking/'.$kode,1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return $final;
		}else{
			return $data_json['metadata'];
		}
	}
	function antri_belum(){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/pendaftaran/aktif',1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return $final;
		}else{
			return $data_json['metadata'];
		}
	}
	function antri_dokter($kodepoli,$kodedokter,$hari,$jam){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/pendaftaran/kodepoli/'.$kodepoli.'/kodedokter/'.$kodedokter.'/hari/'.$hari.'/jampraktek/'.$jam,1);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return $final;
		}else{
			return $data_json['metadata'];
		}
	}
	function list_taks($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;	
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/antrean/getlisttask',1,$post);	
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'data'=>$data_json['metadata'],
				'response'=>$final
			];
		}else{
			return [
				'data'=> $data_json['metadata']
			];
		} 
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
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/jadwaldokter/kodepoli/'.$kode.'/tanggal/'.$tgl,1);	
		// return $response;
		$data_json=json_decode($response, true);
		if($data_json['metadata']['code'] == 200){
			$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
			$data_json3=Yii::$app->kazo->decompress($data_json2);
			$final=json_decode($data_json3, true);
			return [
				'metaData'=>$data_json['metadata'],
				'response'=>$final,
			];
		}else{
			return $data_json;
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
	function get_dashboardtgl($tgl,$waktu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;			
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/dashboard/waktutunggu/tanggal/'.$tgl.'/waktu/'.$waktu,1);	
		$data_json=json_decode($response, true);
		return $data_json;
		
	}
	function get_dashboardbln($bulan,$tahun,$waktu){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;			
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr('https://apijkn.bpjs-kesehatan.go.id/antreanrs/dashboard/waktutunggu/bulan/'.$bulan.'/tahun/'.$tahun.'/waktu/'.$waktu,1);	
		$data_json=json_decode($response, true);
		return $data_json;
		
	}
	
	
		
	
}
