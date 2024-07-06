<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class KontrolFunction extends Component{
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
	
	function delete_spri($post){
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
		return array(
			'metaData'=>$data_json['metaData'],
			'response'=>$final,
		);
			
		
		}else{
			return $data_json;
		}
	}
	function post_spri($post){
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
	function post_kontrol($post){
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
	function get_spri($spri){
		date_default_timezone_set('UTC');
		$consId =  $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/noSuratKontrol/'.$spri,2);	
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
	function get_kontrol_noka($bulan,$tahun,$nokartu,$filter){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/ListRencanaKontrol/Bulan/'.$bulan.'/Tahun/'.$tahun.'/Nokartu/'.$nokartu.'/filter/'.$filter,2);	
	
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
	function get_data_poli($jenis_kontrol,$nomor,$tgl){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/ListSpesialistik/JnsKontrol/'.$jenis_kontrol.'/nomor/'.$nomor.'/TglRencanaKontrol/'.$tgl,2);	
	
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
	function get_data_dokter($jenis_kontrol,$poli,$tgl){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/'.$jenis_kontrol.'/KdPoli/'.$poli.'/TglRencanaKontrol/'.$tgl,2);	
	
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
}
?>