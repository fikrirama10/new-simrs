<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class RujukanFunction extends Component{
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
        $this->consId = '29855';
        $this->secretKey = '3rU307868B';
        //$this->userKey = 'c898a2018239f9b32dda4d5a00792f79';
    }
	function getRomawi($bln){
		switch ($bln){
			case 1: 
				return "I";
				break;
			case 2:
				return "II";
				break;
			case 3:
				return "III";
				break;
			case 4:
				return "IV";
				break;
			case 5:
				return "V";
				break;
			case 6:
				return "VI";
				break;
			case 7:
				return "VII";
				break;
			case 8:
				return "VIII";
				break;
			case 9:
				return "IX";
				break;
			case 10:
				return "X";
				break;
			case 11:
				return "XI";
				break;
			case 12:
				return "XII";
				break;
		}
	}
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
	function get_rujukan($noRujukan,$faskes){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;
		if($faskes == 1){
    		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/'.$noRujukan,2);	
		}else{
		    	$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/RS/'.$noRujukan,2);	
		}
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	function get_rujukanrs($noRujukan){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/Keluar/'.$noRujukan,2);	
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
	function delete_rujukan($post){
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
	function get_noka($nokartu){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/Peserta/'.$nokartu,2);	
	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
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
	function get_nokas($nokartu){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/List/Peserta/'.$nokartu,2);	
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
	function get_nokas_rs($nokartu){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
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
	function get_spesialistik($kodeppk,$tgl){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Rujukan/ListSpesialistik/PPKRujukan/'.$kodeppk.'/TglRujukan/'.$tgl,2);	
	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}


}
?>