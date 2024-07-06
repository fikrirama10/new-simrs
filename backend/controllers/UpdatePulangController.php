<?php
namespace backend\controllers;
use Yii;
use common\models\Pasien;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class UpdatePulangController extends Controller
{
	public function actionIndex(){
		$response= Yii::$app->bpjs->list_data_pulang(12,2022);   
		//return print_r($response);		 
		// $data_json=json_decode($response, true);
		$data = $response['response']['list'];
			// $provider = $$response['response']['list']['sep'][' noSep']->search(Yii::$app->request->get());
			$dataProvider = new ArrayDataProvider([
				  'allModels' => $data,
				  'pagination' => [
				  'pageSize' => 100,
				],
			 
			]);
		return $this->render('index',[
			'dataProvider'=>$dataProvider,
			// 'filter'=>$filter,
			'response'=>$response,
		]);
	}
	public function actionPulang($id){
		$post = Yii::$app->request->post();
		$request = Yii::$app->request;
		if($post){
			$carapulang = $request->post('carapulang');
			$tglpulang = $request->post('tglpulang');
			$nosurat = $request->post('nosurat');
			$tglmeninggal = $request->post('tglmeninggal');
			$post_pulang = array(
				'request'=>array(
					't_sep'=>array(
						'noSep'=>$id,
						'statusPulang'=>$carapulang,
						'noSuratMeninggal'=>$nosurat,
						'tglMeninggal'=>$tglmeninggal,
						'tglPulang'=>$tglpulang,
						'noLPManual'=>'',
						'user'=>'DionaY123',
					),
				),
			);
			$posting_rujukan = Yii::$app->bpjs->update_pulang($post_pulang);
			if($posting_rujukan['metaData']['code'] == '200'){
				Yii::$app->session->setFlash('success', 'Berhasil Update No rujukan : '.$posting_rujukan['response']);
				return $this->redirect(['/update-pulang']);
			}else{
				Yii::$app->session->setFlash('danger', $posting_rujukan['metaData']['message']);
				return $this->redirect(['/update-pulang']);
			}
		}
	}
	public function actionShowList($awal,$akhir){
		$response= Yii::$app->bpjs->list_data_pulang($awal,$akhir);
		// return print_r($response);		
		// $data_json=json_decode($response, true);
		$data = $response['response']['list'];
			// $provider = $$response['response']['list']['sep'][' noSep']->search(Yii::$app->request->get());
			$dataProvider = new ArrayDataProvider([
				  'allModels' => $data,
				  'pagination' => [
				  'pageSize' => 100,
				],
			 
			]);
		return $this->renderAjax('show-list',[
			'dataProvider'=>$dataProvider,
			// 'filter'=>$filter,
			'response'=>$response,
		]);
	}
}