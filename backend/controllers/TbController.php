<?php

namespace backend\controllers;

use Yii;
use common\models\Pasien;
use common\models\Rawat;
use common\models\Klpcm;
use common\models\KlpcmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\LZCompressor\LZString;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class TbController extends Controller
{
	 public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	public function actionIndex()
    {
		$where = ['kat_diagnosa'=>34];
		$searchModel = new KlpcmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionViewPasien($id){
	    $rawat = Klpcm::findOne($id);
	    $pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
	    return $this->redirect(['/pasien/'.$pasien->id]);
	}
}