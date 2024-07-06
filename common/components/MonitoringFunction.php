<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class MonitoringFunction extends Component{
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
	
	function get_datamonitoring($tgl,$jenispel){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/Monitoring/Kunjungan/Tanggal/'.$tgl.'/JnsPelayanan/'.$jenispel,2);	
	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);
		$final=json_decode($data_json3, true);
		return $final;
	}
	function get_historipel($noka,$awal,$akhir){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlVclaim'].'/monitoring/HistoriPelayanan/NoKartu/'.$noka.'/tglMulai/'.$awal.'/tglAkhir/'.$akhir,2);	
	
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