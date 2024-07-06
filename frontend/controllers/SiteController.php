<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\VaksinSearch;
use common\models\Daftar;
use common\models\Vaksin;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionPrint($id) {
	  //tampilkan bukti proses
		$noregister=  base64_decode($id);
		$model =  Vaksin::find()->where(['noregister'=>$noregister])->one();
		$content = $this->renderPartial('print-permohonan',['model' => $model]);
		  
		  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'content' => $content,  
			'marginTop' => '6',
			'cssFile' => '@frontend/web/css/paper.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
			$response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
		  // return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	 public function actionPrintSkrining($id) {
	  //tampilkan bukti proses
		$noregister=  base64_decode($id);
		$model =  Vaksin::find()->where(['noregister'=>$noregister])->one();
		$content = $this->renderPartial('print-skrining',['model' => $model]);
		  
		  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_A4, 
			'content' => $content,  
			'marginTop' => '3',
		   'orientation' => Pdf::ORIENT_LANDSCAPE, 
		   'marginLeft' => '4',
		   'marginRight' => '4',
		   'marginBottom' => '3',
			'cssFile' => '@frontend/web/css/paper.css',
			//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
			$response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
		  // return the pdf output as per the destination setting
		return $pdf->render(); 
	}
	public function actionPesertaVaksin(){
		$searchModel = new VaksinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('peserta-vaksin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    public function actionCariDataVaksin(){
        return $this->render('data-vaksin');
    }
    public function actionShowDataVaksin($id){
		$response = $this->get_content('http://localhost/sinarsp/rest/cari-vaksin?id='.$id);
		$kuota = json_decode($response, true);
		return $this->renderAjax('show-data-vaksin',[
			'kuota'=>$kuota,
		]);
	}
    public function actionVaksinBerhasil($id){
		$noregister=  base64_decode($id);
		$model =  Vaksin::find()->where(['noregister'=>$noregister])->one();
		return $this->render('vaksin-berhasil',[
			'model'=>$model
		]);
	}
	public function actionVaksinBerhasil2($id){
		$model =  Vaksin::find()->where(['noregister'=>$id])->one();
		return $this->render('vaksin-berhasil',[
			'model'=>$model
		]);
	}
	public function actionVaksin(){
		$model = new Vaksin();
		if ($model->load(Yii::$app->request->post())) {
		    $vaksinn = Vaksin::find()->where(['tglvaksin'=>$model->tglvaksin])->count();
			$vaksin = Vaksin::find()->where(['nik'=>$model->nik])->count();
			$model->genKode();
			$model->genAntri($model->tglvaksin);
			$model->tgldaftar = date('Y-m-d G:i:s',strtotime('+7 hour',strtotime(date('Y-m-d G:i:s'))));
			$model->idhari = date('N',strtotime($model->tglvaksin));
			if($model->tglvaksin == "0000-00-00"){
				Yii::$app->session->setFlash('warning', 'Kesalahan menginput silahkan ulangi proses input dengan benar');
				return $this->refresh();  
			}else if($model->usia < 15){
			  Yii::$app->session->setFlash('warning', 'Kesalahan menginput silahkan ulangi proses input dengan benar');
				return $this->refresh();  
			}
			if($vaksin > 0){
				Yii::$app->session->setFlash('danger', 'PENDAFTARAN GAGAL NIK SUDAH TERDAFTAR');
				return $this->refresh();
			}else{
			    if($vaksinn > 110){
				Yii::$app->session->setFlash('danger', 'UPS.. PENDAFTARAN GAGAL KUOTA SUDAH PENUH');
				return $this->refresh();
		    	}else{
    				if($model->save()){
						$model->antri = substr($model->noantrian,9);
						$model->save(false);
    					return $this->redirect(['vaksin-berhasil?id='.base64_encode($model->noregister)]);
    				}else{
    					return $this->render('vaksin',[
    						'model'=>$model
    					]);
    				}
		    	}
			}
			
			
		}
		return $this->render('vaksin',[
			'model'=>$model
		]);
	}
	public function actionShowVaksin($tgl){
		$response = $this->get_content('http://localhost/sinarsp/rest/list-vaksin?tgl='.$tgl);
		$kuota = json_decode($response, true);
		$model = new Vaksin();
		return $this->renderAjax('show-vaksin',[
			'kuota'=>$kuota,
			'model'=>$model,
		]);
	}
    public function actionStatusDaftar(){
		return $this->render('status-daftar');
	}
	public function actionShowDaftar($id){
		$response = $this->get_content('daftarsulaiman.rsausulaiman.com/rest/list-daftar-by-id?id='.$id);
		$kuota = json_decode($response, true);
		return $this->renderAjax('show-daftar',[
			'kuota'=>$kuota,
		]);
	}
	
	public function actionShowKuota($tgl){
		$response = $this->get_content('simrs.rsausulaiman.com/rest-daftar/kuota-tgl?tgl='.$tgl);
		$kuota = json_decode($response, true);
		return $this->renderAjax('show-kuota',[
			'kuota'=>$kuota,
		]);
	}
	public function get_content($url, $post = '') {
		
		// $data = "29250";
		// $secretKey = "5lQ5E30F4C";
		$data = "29855";
		$secretKey = "3rU307868B";
         // Computes the timestamp
        date_default_timezone_set('UTC');
        $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
        // Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
		$encodedSignature = base64_encode($signature);
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$usecookie = __DIR__ . "/cookie.txt";
		$header[] = 'Content-Type: application/json;charset=utf-8';
		$header[] = "Accept-Encoding: gzip, deflate";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Accept-Language:  en-US,en;q=0.8,id;q=0.6";
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		// curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");

		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$rs = curl_exec($ch);
		
		if(empty($rs)){
			//var_dump($rs, curl_error($ch));
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		return $rs;
	}
    public function actionIndex()
    {
        return $this->redirect(['/dashboard']);
    }
    public function actionJadwal()
    {
		$response = $this->get_content('https://simrs.rsausulaiman.com/rest-daftar/kuota');
		$kuota = json_decode($response, true);
		$response2 = $this->get_content('https://simrs.rsausulaiman.com/rest-daftar/poli');
		$poli = json_decode($response2, true);
		return $this->render('jadwal',[
			'kuota' => $kuota,
			'poli' => $poli,
		]);
    }
	
	public function actionDaftar()
    {
        return $this->render('daftar');
    }
	public function actionTerdaftar($jenis,$rujukan)
    {
		$daftar = Daftar::find()->where(['norujukan'=>$rujukan])->andwhere(['jenisrujukan'=>$jenis])->one();
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-poli?id='.$daftar->idpoli);
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/pasien?norm='.$daftar->no_rekmed);
		$data_json=json_decode($response, true);
		$data_json2=json_decode($response2, true);
		return $this->render('terdaftar',[
			'data_json'=>$data_json,
			'data_json2'=>$data_json2,
			'daftar'=>$daftar,
		]);
    }
	public function actionCekKunjungan(){
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cek-kunjungan');
		$data_json=json_decode($response, true);
		$daaa = '0120B1210421P000409';
		$dasar = $this->cariyangsama($data_json,$daaa);
		return $this->render('cek-kunjungan',[
			'data_json'=>$data_json,
			'dasar'=>$dasar,
		]);
		
	}
	
    function cariyangsama($data,$dupval) {
        $nb= 0;
        foreach($data as $key => $val)
        if ($val['Rujukan']==$dupval) $nb++;
        return $nb;
    }  
	public function actionShow($id,$tgl){
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/data-pasien?norm='.$id.'&tgllahir='.$tgl.'');
		$data_json=json_decode($response, true);
		return $this->renderAjax('show',[
			'data_json'=>$data_json,
		]);
	}
	public function actionDaftarUmum($id){
		return $this->renderAjax('daftar-umum');
	}
	public function actionDaftarBpjs($id,$nobpjs){
		$bpjs = $nobpjs;
		return $this->renderAjax('daftar-bpjs',[
			'bpjs'=>$bpjs,
		]);
	}
	public function actionShowBpjs($id,$tgl,$bpjs){
		
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/rujukan?jenis='.$tgl.'&norujukan='.$id);
		$data_json=json_decode($response, true);
		
		if($tgl == 1 ){
			$response2 = $this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/dokter-rujukan?kode='.$data_json['response']['rujukan']['poliRujukan']['kode']);
			$data_dokter = json_decode($response2, true);
			$daftar = Daftar::find()->where(['norujukan'=>$id])->count();
			// print_r($data_dokter);
			return $this->renderAjax('show-rujukan',[
				'data_json'=>$data_json,
				'data_dokter'=>$data_dokter,
				'bpjs'=>$bpjs,
				'jenis'=>$tgl,
				'daftar'=>$daftar,
			]);
		}else{
			$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/kode-poli?id='.$data_json['response']['poliTujuan']);
			$data_json2=json_decode($response2, true);
			$response3 = $this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/dokter-kode?kode='.$data_json['response']['kodeDokter']);
			$data_dokter2 = json_decode($response3, true);
			return $this->renderAjax('show-bpjs',[
				'data_json'=>$data_json,
				'data_json2'=>$data_json2,
				'data_dokter2'=>$data_dokter2,
				'bpjs'=>$bpjs,
				'jenis'=>$tgl,
			]);
		}
		
	}
	public function actionCekDokter($id,$tgl,$bpjs,$rujukan,$jenis){
		$daftar = new Daftar();
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cek-jadwal?dokter='.$id.'&tgl='.$tgl);
		$data_json=json_decode($response, true);
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-dokter?id='.$id);
		$data_json2=json_decode($response2, true);
		// $daftar = Daftar::find()->where()
		$datadaftar = Daftar::find()->where(['tglberobat'=>$tgl])->andwhere(['nobpjs'=>$bpjs])->one();
		$countdatadaftar = Daftar::find()->where(['tglberobat'=>$tgl])->andwhere(['nobpjs'=>$bpjs])->count();
		return $this->renderAjax('cek-dokter',[
			'data_json'=>$data_json,
			'data_json2'=>$data_json2,
			'daftar'=>$daftar,
			'countdatadaftar'=>$countdatadaftar,
			'datadaftar'=>$datadaftar,
			'tgl'=>$tgl,
			'jenis'=>$jenis,
			'rujukan'=>$rujukan,
		]);
	}
	public function actionSelesai($id){
		$noregistrasi =  base64_decode($id);
		$daftar = Daftar::find()->where(['noregistrasi'=>$noregistrasi])->one();
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-poli?id='.$daftar->idpoli);
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/pasien?norm='.$daftar->no_rekmed);
		$data_json=json_decode($response, true);
		$data_json2=json_decode($response2, true);
		return $this->render('selesai',[
			'data_json'=>$data_json,
			'data_json2'=>$data_json2,
			'daftar'=>$daftar,
		]);
	}
	public function actionHitungKunjungan(){
		$rujukan = '0120B0130521P000438';
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cek-kunjungan');
		$data_json=json_decode($response, true);
		//$hitung = array_count_values($data_json);
		print_r($data_json);
	}
	public function actionKonfirmasi($id){
		$noregistrasi =  base64_decode($id);
		$daftar = Daftar::find()->where(['noregistrasi'=>$noregistrasi])->one();
		$arrdip= json_encode(array(
			"NoRm"=>$daftar->no_rekmed,
			"NoRujukan"=>$daftar->norujukan,
			"TglBerobat"=>$daftar->tglberobat,
			"IdDokter"=>$daftar->iddokter,
			"IdPoli"=>$daftar->idpoli,
			"Email"=>$daftar->email,
			"NoTlp"=>$daftar->notlp,
			"Anggota"=>$daftar->anggota,
			"Bayar"=>"5"
		));
        date_default_timezone_set("UTC");
        $tStamp = strval(time()-strtotime("1970-01-01 00:00:00"));
        // Computes the signature by hashing the salt with the secret key as the key
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$usecookie = __DIR__ . "/cookie.txt";
		//$header[] = "Content-Length: " . strlen($data_string) ." ";
		$header[] = 'Content-Type: application/json;charset=utf-8';
		
		$ch = curl_init("https://dvlpsimrs.rsausulaiman.com/rest-daftar/daftar");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $arrdip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		$data_json=json_decode($result, true);
		//return print_r($data_json);
		$daftar->status = 2;
		$daftar->noantrian = $data_json['response']['nomorantrean'];
		$daftar->idrawat = $data_json['response']['idrawat'];
		$daftar->save(false);
		return $this->redirect(['/site/selesai?id='.$id]);
	}
	public function actionBerhasil($id=''){
		$noregistrasi =  base64_decode($id);
		$daftar = Daftar::find()->where(['noregistrasi'=>$noregistrasi])->one();
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/pasien?norm='.$daftar->no_rekmed);
		$response2=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/cari-dokter?id='.$daftar->iddokter);
		$data_json2=json_decode($response2, true);
		$data_json=json_decode($response, true);
		if($daftar->status > 1){
			return $this->redirect('index');
		}
		return $this->render('berhasil',[
			'daftar'=>$daftar,
			'data_json'=>$data_json,
			'data_json2'=>$data_json2,
		]);
	}
	public function actionDaftarPoli($kode)
    {
		$tes = base64_decode($kode);
		$no_rm = substr($tes,4,6);
		$response=$this->get_content('https://dvlpsimrs.rsausulaiman.com/rest-daftar/pasien?norm='.$no_rm);
		$data_json=json_decode($response, true);
		
		
		$daftar = new Daftar();
		if ($daftar->load(Yii::$app->request->post())) {
			$daftar->genKode();
			$daftar->nobpjs = $data_json['response']['NoBpjs'];
			$daftar->no_rekmed = $data_json['response']['NoRM'];
			$daftar->tgl = date('Y-m-d G:i:s',strtotime('+7 hour',strtotime(date('Y-m-d G:i:s'))));
			if($data_json['response']['Anggota'] == NULL){
				if($data_json['response']['Pekerjaan'] == 7){
					$daftar->anggota = 1;
				}else{
					$daftar->anggota = 0;
				}				
			}else{
				$daftar->anggota = $data_json['response']['Anggota'];
			}
			$daftar->status = 1;
			if($daftar->save(false)){
				return $this->redirect(['berhasil?id='.base64_encode($daftar->noregistrasi)]);
			}else{
				return $this->render('daftar-poli',['data_json'=>$data_json,'daftar'=>$daftar]);
			}
		}
        return $this->render('daftar-poli',['data_json'=>$data_json,'daftar'=>$daftar]);
    }
   
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
