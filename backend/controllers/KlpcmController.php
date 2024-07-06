<?php

namespace backend\controllers;

use Yii;
use common\models\Klpcm;
use common\models\KlpcmDokumen;
use common\models\KlpcmDetail;
use common\models\Rawat;
use common\models\Pasien;
use common\models\KlpcmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * KlpcmController implements the CRUD actions for Klpcm model.
 */
class KlpcmController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
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

	/**
	 * Lists all Klpcm models.
	 * @return mixed
	 */
	public function actionKlpcmPrintLaporan($bulan, $tahun)
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/rest/klpcm-laporan?bulan=' . $bulan . '&tahun=' . $tahun;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('print-laporan', ['json' => $json, 'bulan' => $bulan ,'tahun'=>$tahun]);

		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE,
			'destination' => Pdf::DEST_BROWSER,
			'format' => Pdf::FORMAT_LEGAL,
			'content' => $content,

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
	public function actionKlpcmShowLaporan($bulan, $tahun)
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/rest/klpcm-laporan?bulan=' . $bulan . '&tahun=' . $tahun;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('klpcm-show', [
			'json' => $json
		]);
	}
	public function actionKlpcmLaporan()
	{
		$url = Yii::$app->params['baseUrl'] . 'dashboard/rest/klpcm-laporan?bulan=' . date('m') . '&tahun=' . date('Y');
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('klpcm-laporan', [
			'json' => $json,
		]);
	}
	public function actionIndex()
	{
		$searchModel = new KlpcmSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Klpcm model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionGetRawat()
	{
		$kode = Yii::$app->request->post('id');
		if ($kode) {
			$model = Rawat::find()->where(['id' => $kode])->one();
		} else {
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
	}
	public function actionView($id)
	{
		$klpcm = new KlpcmDetail();
		$klpcm_dokumen = new KlpcmDokumen();
		$klpcm_list = KlpcmDetail::find()->where(['idklpcm' => $id])->all();
		$klpcmdoc_list = KlpcmDokumen::find()->where(['idklpcm' => $id])->all();
		$model = $this->findModel($id);
		$rawat = Rawat::findOne($model->idrawat);
		$post = Yii::$app->request->post();
		if ($klpcm->load(Yii::$app->request->post())) {
			if ($klpcm->idformulir == null) {
				return $this->refresh();
			}

			$formulir = explode("-", $klpcm->idformulir);
			$klpcm->idformulir = $formulir[0];
			$klpcm->nama_formulir = $formulir[1];
			// return $jam[1];
			$postMo = $post['KlpcmDetail'];
			$postTdk = $postMo['idtidaklengkap'];
			if ($klpcm->idtidaklengkap == '""') {
				$klpcm->idtidaklengkap = '[""]';
			} else {
				$klpcm->idtidaklengkap = json_encode($postTdk);
			}

			$klpcm->idklpcm = $model->id;
			$klpcm->tgl = date('Y-m-d', strtotime('+6 hour'));
			$klpcm->save(false);
			return $this->refresh();
		} else if ($klpcm_dokumen->load(Yii::$app->request->post())) {
			$doc = UploadedFile::getInstance($klpcm_dokumen, 'dokumen');
			if (!$doc == null) {
				$klpcm_dokumen->dokumen = 'KLPCM_' . $klpcm_dokumen->id . $klpcm_dokumen->idklpcm . '_' . Yii::$app->algo->cleanFileName($doc->name);
				//upload dokumen dulu, baru simpan data kalau berhasil
				$path = Yii::$app->params['documentPath'] . '/' . $klpcm_dokumen->dokumen;
				$doc->saveAs($path);
			} else {
				$klpcm_dokumen->dokumen = '';
			}
			$klpcm_dokumen->save();
			return $this->refresh();
		}
		return $this->render('view', [
			'model' => $this->findModel($id),
			'klpcm' => $klpcm,
			'klpcm_list' => $klpcm_list,
			'klpcm_dokumen' => $klpcm_dokumen,
			'dokumen' => $klpcmdoc_list,
			'rawat' => $rawat,
		]);
	}
	public function actionHapusKelengkapan($id)
	{
		$model = KlpcmDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusDokumen($id)
	{
		$model = KlpcmDokumen::findOne($id);
		if ($model->dokumen != '') {
			$path = Yii::$app->params['documentPath'] . '/' . $model->dokumen;
			unlink($path);
		}
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionShowRm($id)
	{
		$rawat = Rawat::find()->where(['klpcm' => 0])->andwhere(['no_rm' => $id])->orderBy(['tglmasuk' => SORT_DESC])->andwhere(['<>', 'status', 5])->all();
		$pasien = Pasien::find()->where(['no_rm' => $id])->one();
		$klpcm = new Klpcm();
		return $this->renderAjax('show-rm', [
			'rawat' => $rawat,
			'pasien' => $pasien,
			'klpcm' => $klpcm,
		]);
	}

	/**
	 * Creates a new Klpcm model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionSelesai($id)
	{
		$model = $this->findModel($id);
		$rawat = Rawat::findOne($model->idrawat);
		$klpcm_list = KlpcmDetail::find()->where(['idklpcm' => $id])->all();
		if (count($klpcm_list) > 0) {
			$model->kelengkapan = 0;
		} else {
			$model->kelengkapan = 1;
		}
		$model->save();
		date_default_timezone_set("Asia/Jakarta");
		$tpstamp = strtotime(date('Y-m-d H:i:s')) . '000';
		if ($rawat->taksid > 2) {
			$taks = array(
				"kodebooking" => $rawat->idrawat,
				"taskid" => 5,
				"waktu" =>  $tpstamp,
			);

			$taksid = Yii::$app->hfis->update_taks($taks);
			if ($model->obat == 1) {
				$taks = array(
					"kodebooking" => $rawat->idrawat,
					"taskid" => 6,
					"waktu" =>  $tpstamp,
				);

				$taksid = Yii::$app->hfis->update_taks($taks);
				if ($taksid['metadata']['code'] == 200) {
					$taks2 = array(
						"kodebooking" => $rawat->idrawat,
						"taskid" => 7,
						"waktu" =>   $tpstamp,
					);
					$taksid2 = Yii::$app->hfis->update_taks($taks2);
					$rawat->taksid = 7;
					$rawat->save(false);
				}
			}

			$rawat->save(false);

			Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 4");
		}
		return $this->redirect(['/klpcm']);
	}
	public function actionCreate()
	{
		$klpcm = new Klpcm();

		if ($klpcm->load(Yii::$app->request->post())) {
			$rawat = Rawat::findOne($klpcm->idrawat);
			$rawat->klpcm = 1;
			$rawat->icdx = $klpcm->icdx;
			$rawat->kat_penyakit = $klpcm->kat_diagnosa;

			$klpcm->status = 1;
			if ($klpcm->save()) {
				date_default_timezone_set("Asia/Jakarta");
				$tpstamp = strtotime(date('Y-m-d H:i:s')) . '000';
				//		if($rawat->taksid == 3){
				//	$taks = array(
				//		"kodebooking"=> $rawat->idrawat,
				//		"taskid"=> 4,
				//		"waktu"=>  $tpstamp,
				//	);

				//	$taksid = Yii::$app->hfis->update_taks($taks);
				// return print_r($taksid);
				//if($taksid['metadata']['code'] == 200){
				//	$rawat->taksid = 4;

				// Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 4");
				//	}
				//	}
				$rawat->save(false);
				return $this->redirect(['view', 'id' => $klpcm->id]);
			}
		}

		return $this->render('create', [
			'klpcm' => $klpcm,
		]);
	}

	/**
	 * Updates an existing Klpcm model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$klpcm = $this->findModel($id);

		if ($klpcm->load(Yii::$app->request->post()) && $klpcm->save()) {
			return $this->redirect(['view', 'id' => $klpcm->id]);
		}

		return $this->render('update', [
			'klpcm' => $klpcm,
		]);
	}

	/**
	 * Deletes an existing Klpcm model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Klpcm model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Klpcm the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Klpcm::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
