<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\Rawat;
use common\models\Pasien;
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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$url = Yii::$app->params['baseUrl']."dashboard/rest/bed";
		
// 		$content = file_get_contents($url);
		$content = Yii::$app->kazo->fetchApiData($url);
// 		return $content;
		$json = json_decode($content, true);
// 		return $json;
		$url2 = Yii::$app->params['baseUrl']."dashboard/rest/kunjungan-harian";
		$content2 = Yii::$app->kazo->fetchApiData($url2);
		$json2 = json_decode($content2, true);
		$kunjungan = $json2['kunjungan'];
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
		$pasien = Pasien::find()->where(['tgldaftar'=>$tgl])->all();
		$rajal = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl])->andwhere(['idjenisrawat'=>1])->andwhere(['<>','status',5])->all();
		$ranap = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl])->andwhere(['idjenisrawat'=>2])->andwhere(['<>','status',5])->all();
		$ugd = Rawat::find()->where(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>$tgl])->andwhere(['idjenisrawat'=>3])->andwhere(['<>','status',5])->all();
		
		
		$url_fin = Yii::$app->params['baseUrl'].'dashboard/rest-laporan-keuangan/transaksi-bulanan?tahun='.date('Y');
		$content_fin = Yii::$app->kazo->fetchApiData($url_fin);
		$json_fin = json_decode($content_fin, true);
		
        return $this->render('index',[
			'bed'=>$json,
			'kunjungan'=>$kunjungan,
			'pasien'=>$pasien,
			'rajal'=>$rajal,
			'ranap'=>$ranap,
			'ugd'=>$ugd,
			'model'=>$json_fin,
		]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
