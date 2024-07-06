<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class SepFunction extends Component{
	//
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
        $this->consId = '10003';
        $this->secretKey = '8rP8F99311';
        //$this->userKey = 'c898a2018239f9b32dda4d5a00792f79';
    }
	function cek_rujukan($tgl){
		
	}
	function post_spri($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/RencanaKontrol/InsertSPRI',2,$post);	
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
	function cari_sep($nosep){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/SEP/'.$nosep,2);	
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
	
	function post_sep_online($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/SEP/2.0/insert',2,$post);	
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
	function delete_sep($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/SEP/2.0/delete',2,$post,1);	
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
			return $response;
		}
	}
	function sep_update($post){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/SEP/2.0/update',2,$post,2);	
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
			return $response;
		}
	}

}
?>