<?php

namespace backend\controllers;

use Yii;
use common\models\Rawat;
use common\models\RawatKunjungan;
use common\models\Pasien;
use common\models\RawatSpri;
use common\models\Transaksi;
use common\models\Tarif;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\RuanganBed;
use common\models\RawatRuangan;
use common\models\RawatPermintaanPindahSearch;
use common\models\RawatPermintaanPindah;
use common\models\RawatSpriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\LZCompressor\LZString;
/**
 * RawatBayarController implements the CRUD actions for RawatBayar model.
 */
class PasienOnlineController extends Controller
{
	public function actionIndex(){
		return 'Hello';
	}
}