<?php

namespace backend\controllers;

use Yii;
use common\models\Operasi;
use common\models\Transaksi;
use common\models\OperasiTindakan;
use common\models\OperasiTindakanBhp;
use common\models\TransaksiDetailRinci;
use common\models\Tarif;
use common\models\Rawat;
use common\models\Pasien;
use common\models\RadiologiHasildetail;
use common\models\LaboratoriumHasil;
use common\models\OperasiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperasiController implements the CRUD actions for Operasi model.
 */
class OperasiController extends Controller
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
     * Lists all Operasi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Operasi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHapusTindakan($id){
		$model = OperasiTindakan::findOne($id);
		$transaksi = TransaksiDetailRinci::find()->where(['idtarif'=>$model->idtindakan])->andwhere(['idrawat'=>$model->idrawat])->one();
		if($transaksi->delete()){
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}
	public function actionHapusBhp($id){
		$model = OperasiTindakanBhp::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
    public function actionBhpSelesai($id){
		$model = OperasiTindakan::findOne($id);
		$bhp_list = OperasiTindakanBhp::find()->where(['idtindakan'=>$model->id])->all();
		$total =0;
		foreach($bhp_list as $bl){
			$total += $bl->harga;
		}
		$model->harga_bhp = $total;
		$model->harga_total = $model->harga_tindakan + $total;
		if($model->save()){
			$detail = TransaksiDetailRinci::find()->where(['idtarif'=>$model->idtindakan])->andwhere(['idrawat'=>$model->idrawat])->one();
			$detail->tarif = $model->harga_total;
			$detail->save();
		}
		return $this->redirect(['operasi/'.$model->idok]);
	}
    public function actionTindakanBhp($id){
		$model = OperasiTindakan::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		$bhp = new OperasiTindakanBhp();
		$bhp_list = OperasiTindakanBhp::find()->where(['idtindakan'=>$model->id])->all();
		if ($bhp->load(Yii::$app->request->post())) {
			$bhp->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$bhp->status = 1;
			if($bhp->save()){
				return $this->refresh();
			}
		}
		return $this->render('bhp',[
			'model'=>$model,
			'rawat'=>$rawat,
			'pasien'=>$pasien,
			'bhp'=>$bhp,
			'bhp_list'=>$bhp_list,
		]);
	}
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$tindakan  = new OperasiTindakan();
		$rawat = Rawat::findOne($model->idrawat);
		$lab = LaboratoriumHasil::find()->where(['idrawat'=>$rawat->id])->all();
		$radi = RadiologiHasildetail::find()->where(['idrawat'=>$rawat->id])->all();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$rawat->kunjungans->id])->one();
		$transaksi_detail = new TransaksiDetailRinci();
		$tindakan_ok = OperasiTindakan::find()->where(['idrawat'=>$rawat->id])->andwhere(['idok'=>$model->id])->all();
		if ($tindakan->load(Yii::$app->request->post())) {
		    
		    $tarif = Tarif::findOne($tindakan->idtindakan);
			$tindakan->harga_tindakan = $tarif->tarif;
			$hitung_tindakan = OperasiTindakan::find()->where(['idtindakan'=>$tindakan->idtindakan])->andwhere(['idok'=>$model->id])->andwhere(['idrawat'=>$rawat->id])->count();
			if($hitung_tindakan){
				Yii::$app->session->setFlash('danger', "Tindakan Sudah ada ");
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
			if($tindakan->idtindakan == null){
			    	Yii::$app->session->setFlash('danger', "Tindakan belum diisi");
				return $this->refresh();
			}
			$tindakan->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$tindakan->idtrx = $transaksi->id;
           if($tindakan->save()){
    
			   $transaksi_detail->idtransaksi = $transaksi->id; 
			   $transaksi_detail->idtarif = $tindakan->idtindakan;
			   $transaksi_detail->tarif = $tindakan->tarif->tarif;
			   $transaksi_detail->iddokter = $tindakan->iddokter;
			   $transaksi_detail->tgl = $tindakan->tgl;
			   $transaksi_detail->idrawat = $tindakan->idrawat;
			   $transaksi_detail->idbayar = $tindakan->idbayar;
			   $transaksi_detail->jumlah = 1;
			   $transaksi_detail->idpaket = 0;
			   $transaksi_detail->save();
			   return $this->refresh();
		   }
        }
        return $this->render('view', [
            'model' => $model,
            'rawat' => $rawat,
            'pasien' => $pasien,
            'tindakan' => $tindakan,
            'tindakan_ok' => $tindakan_ok,
            'lab' => $lab,
            'radi' => $radi,
        ]);
    }

    /**
     * Creates a new Operasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Operasi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Operasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 3;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Operasi model.
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
     * Finds the Operasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operasi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
