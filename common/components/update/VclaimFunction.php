<?php
namespace common\components;
use yii\base\Component;
use common\models\RawatSpri;
use common\models\Ruangan;
use common\models\RuanganBed;
use Yii;
use yii\LZCompressor\LZString;

class VclaimFunction extends Component{
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
	//peserta by no kartu
	function get_peserta($noKartu,$tglSep){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlBpjs'].'Peserta/nokartu/'.$noKartu.'/tglSEP/'.$tglSep,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	// peserta nik
	function get_peserta_nik($nik,$tglSep){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response= Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/Peserta/nik/'.$nik.'/tglSEP/'.$tglSep,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	//faskes
	function get_faskes($id,$jenis){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/faskes/'.$id.'/'.$jenis,2);	
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	//poliklinik
	function get_poli($id){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'referensi/poli/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	//DOKTER
	function get_dpjp($id,$layanan,$tgl){
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/dokter/pelayanan/'.$layanan.'/tglPelayanan/'.$tgl.'/Spesialis/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	//icd 10
	function get_icd10($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlBpjs'].'referensi/diagnosa/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}
	//icd 9
	function get_icd9($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/procedure/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//provinsi
	function get_provinsi(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/propinsi',2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//kabupaten
	function get_kabupaten($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/kabupaten/propinsi/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//kecamatan
	function get_kecamatan($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/kecamatan/kabupaten/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//diagnosa PRB
	function get_diagnosaprb(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/diagnosaprb',2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//Obat PRB
	function get_obatprb($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/obatprb/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//KELAS RAWAT
	function get_kelasrawat(){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/kelasrawat',2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $final;
	}	
	//Dokter
	function get_dokter($id){		
		date_default_timezone_set('UTC');
		$consId = $this->consId;
		$pssword = $this->secretKey;		
		$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
		$key = $consId.$pssword.$tStamp;		
		$response = Yii::$app->kazo->bpjs_contentr(Yii::$app->params['baseUrlDev'].'/referensi/dokter/'.$id,2);		
		$data_json=json_decode($response, true);
		$data_json2=Yii::$app->kazo->stringDecrypt($key,$data_json['response']);
		$data_json3=Yii::$app->kazo->decompress($data_json2);

		$final=json_decode($data_json3, true);
		return $data_json3;
	}	

	function updateKamar(){
		$model = Ruangan::find()->all();
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
		//return $arrdip;
	
		$response= Yii::$app->kazo->bkonten('https://simrs.rsausulaiman.com/api/pulang',$arrdip);
		$data_json=json_decode($response, true);
		}
	}
	
		
	
}

?>