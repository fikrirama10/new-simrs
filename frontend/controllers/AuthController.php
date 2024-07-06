<?php

namespace frontend\controllers;

use Yii;
use common\models\KlpcmFormulir;

class AuthController extends \yii\rest\Controller
{
	public static function allowedDomains()
	{
		return [
			'*',  // star allows all domains
			'http://localhost:3000',
		];
	}
	public $enableCsrfValidation = false;
	public function behaviors()
	{
		return array_merge(parent::behaviors(), [

			// For cross-domain AJAX request
			'corsFilter'  => [
				'class' => \yii\filters\Cors::className(),
				'cors'  => [
					// restrict access to domains:
					'Origin' => static::allowedDomains(),
					'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'OPTIONS'],
					'Access-Control-Allow-Credentials' => false,
					'Access-Control-Max-Age' => 260000, // Cache (seconds)
					'Access-Control-Request-Headers' => ['*'],
					'Access-Control-Allow-Origin' => false,


				],

			],

		]);
	}
	public function actionListformulir($q)
	{
		// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model = KlpcmFormulir::find()->andFilterWhere(['like', 'formulir', $q])->all();
		$arrdip = array();
		foreach ($model as $m) {
			array_push($arrdip, [
				'id' => $m->id . '-' . $m->formulir,
				'nama' => $m->formulir,
			]);
		}
		return $arrdip;
	}
	public function actionCek($kartu, $tgl)
	{
		$response = Yii::$app->vclaim->get_peserta($kartu, $tgl);
		$model = $response['peserta'];
		//$response= Yii::$app->kazo->bpjs_contentr('https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest/Peserta/nokartu/'.$kartu.'/tglSEP/'.$tgl);
		//$data_json=json_decode($response, true);
		return $model;
	}
	public function actionListdiagnosa($q)
	{
		// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$response = Yii::$app->vclaim->get_icd10($q);
		$model = $response['diagnosa'];
		//return $response;
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['nama'],
				'nama' => $q['nama'],
				'kode' => $q['kode'] . '-' . $q['nama'],

			]);
		}
		return $arrdip;
	}
	public function actionListdiagnosa2($q)
	{
		// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$response = Yii::$app->vclaim->get_icd10($q);
		$model = $response['diagnosa'];
		//return $response;
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['nama'],
				'text' => $q['nama'],

			]);
		}
		return [
			'result'=>$arrdip
		];
	}
	public function actionListprosedur($q)
	{
		// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$response = Yii::$app->vclaim->get_icd9($q);
		$model = $response['procedure'];
		//return $response;
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['nama'],
				'nama' => $q['nama'],
				'kode' => $q['kode'] . '-' . $q['nama'],

			]);
		}
		return $arrdip;
	}
	public function actionListprosedur2($q)
	{
		// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$response = Yii::$app->vclaim->get_icd9($q);
		$model = $response['procedure'];
		//return $response;
		$arrdip = array();
		foreach ($model as $q) {
			array_push($arrdip, [
				'id' => $q['nama'],
				'text' => $q['nama'],

			]);
		}
		return $arrdip;
	}
	public function actionLogin()
	{
		$headers = Yii::$app->request->headers;
		$username = $headers->get('x-username');
		$password = $headers->get('x-password');
		$user = \common\models\User::findByUsername($username);
		if (!empty($user)) {
			// check, valid nggak passwordnya, jika valid maka bikin response success
			if ($user->validatePassword($password)) {
				if ($user->status == 10) {
					$response = [
						'response' => [
							'token' => Yii::$app->kazo->setJwt($user->username),
						],
						'metadata' => [
							'message' => "Ok",
							'code' => 200
						]

					];
				} else {
					$response = [
						'response' => [
							'token' => null,
						],
						'metadata' => [
							'message' => "Token Expired",
							'code' => 201
						]
					];
				}
			} else {
				$response = [
					'response' => [
						'token' => null,
					],
					'metadata' => [
						'message' => "Username / Password Salah",
						'code' => 201
					]
				];
			}
		} else {
			$response = [
				'metadata' => [
					'message' => "Username / Password Tidak ditemukan",
					'code' => 201
				]
			];
		}
		return $response;
	}
}
