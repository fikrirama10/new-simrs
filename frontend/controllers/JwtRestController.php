<?php
namespace frontend\controllers;
use Yii;
use common\models\LoginForm;
use common\models\UserDetail;
use frontend\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\rest\Controller;
class JwtRestController extends Controller
{
	
	
	// public function beforeAction($action){
		// if(parent::beforeAction($action)){
			// $headers = Yii::$app->request->headers;
			// $token = $headers->get('x-token');
			// $username = $headers->get('x-username');
			// if($username){
				// $idApp = $username;
				// $idToken = $token ; 
				// if($idApp == 'bpjs' && $idToken == '1Sb7L54W-rCFjoe7oipNZwiSgPPo4d-v'){
					// return true;
				// }
			// }
		// }
		// throw new \yii\web\UnauthorizedHttpException('Error');
		// return false;
	// }
	
	public function actionSet(){
		$headers = Yii::$app->request->headers;
		$username = $headers->get('x-username');
		$token = $headers->get('x-token');
		$hitungToken = explode(".", $token);
		if(count($hitungToken) < 3){
			return 'Gagal';
		}
		
		$header =base64_decode($hitungToken[0]);
		$payload =base64_decode($hitungToken[1]);
		$signature =$hitungToken[2];
		
		$payload_data = json_decode($payload);
		$tokenExp = isset($payload_data->exp) ? (is_int(intval($payload_data->exp)) ? intval($payload_data->exp) : 0 ): 0;
		$dayy = strtotime(date("Y-m-d H:i:s"));
		// return $tokenExp."-".$dayy;
		if($tokenExp < $dayy){
			return 'expired';
		}else{
			return 'success';
		}
		
		
	}
	
	public function actionShow(){
		return 'berhasil';
	}

}