<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * BarangAmprahController implements the CRUD actions for BarangAmprah model.
 */
class AntreanController extends Controller
{
    function milliseconds()
	{
		$mt = explode(' ', microtime());
		return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
    public function actionAntreanUpdate2($kode){
        $response = Yii::$app->hfis->taksForId($kode);
        $hasil = Yii::$app->hfis->updateTaks($kode,$response);            
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionAntreanUpdate($kode,$taks){
        $update = [
            "kodebooking" => $kode,
            "taskid" => $taks,
            "waktu" => $this->milliseconds(),
        ];
        $response = Yii::$app->hfis->update_taks($update);
        $json = json_encode($response, true);
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionIndexTgl(){
        $tgl = date('Y-m-d',strtotime('+5 hours'));
        // $tgl = '2023-05-20';
         $response = Yii::$app->hfis->antri_tanggal($tgl);
         $json = json_encode($response, true);
         //return $json;  
         return $this->render('index-tgl2',[
             'json'=>$response
         ]);
    }
    public function actionIndexTg2l(){
        
         $tgl = date('Y-m-d',strtotime('+5 hours'));
       // $tgl = '2023-05-20';
        $response = Yii::$app->hfis->antri_tanggal($tgl);
        $json = json_encode($response, true);
        //return $json;  
        return $this->render('index-tgl',[
            'json'=>$response
        ]);
    }
    public function actionUpdateTaks()
    {
        $update = [
            "kodebooking" => "RJ2023613100002",
            "taskid" => 5,
            "waktu" => $this->milliseconds(),
        ];
        $response = Yii::$app->hfis->update_taks($update);
        $json = json_encode($response, true);
        return $json;
    }
    public function actionListTaks()
    {
        $post = array(
            'kodebooking' => 'RJ2023837020001',
        );
        $taks = Yii::$app->hfis->list_taks($post);
        //$response = Yii::$app->hfis->taksForId('RJ2023613100002');
        $json = json_encode($taks, true); 
        return $json;
    }
    public function actionAntriTgl()
    {
        $tgl = date('Y-m-d',strtotime('+5 hours'));
        $response = Yii::$app->hfis->antri_tanggal($tgl);
        $json = json_encode($response, true);
        return $json;
    }
    public function actionAntriKode()
    {
        $kode = 'RJ2023605540001';
        $response = Yii::$app->hfis->antri_kodebooking($kode);
        $json = json_encode($response, true);
        return $json;
    }
    public function actionAntriBelum()
    {
        $response = Yii::$app->hfis->antri_belum();
        $json = json_encode($response, true);
        return $json;
    }
    public function actionAntriDokter()
    {
        $response = Yii::$app->hfis->antri_dokter('INT', 24566, 5, '12:00-14:00');
        $json = json_encode($response, true);
        return $json;
    }
}
