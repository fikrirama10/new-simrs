<?php

namespace backend\controllers;

use Yii;
use common\models\UsulPesan;
use common\models\ObatBacth;
use common\models\UsulPesanDetail;
use common\models\UsulPesanDetailSearch;
use common\models\UsulPesanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * UsulPesanController implements the CRUD actions for UsulPesan model.
 */
class UsulPesanController extends Controller
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
     * Lists all UsulPesan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsulPesanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsulPesan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionEditPengajuan($id){
		$model = $this->findModel($id);
		$model->status = 10;
		if($model->save(false)){
			Yii::$app->session->setFlash('warning', "Data Bisa kembali di edit");
			return $this->redirect(['/usul-pesan/view?id='.$model->id]);
		}
	}
    public function actionSetuju($id){
		$model = $this->findModel($id);
		$model->status = 2;
		if($model->save(false)){
			Yii::$app->session->setFlash('success', "Pengajuan Telah disetujui");
			return $this->redirect(['/usul-pesan']);
		}
	}
	public function actionTolak($id){
		$model = $this->findModel($id);
		$model->status = 4;
		if($model->save(false)){
			Yii::$app->session->setFlash('warning', "Pengajuan Telah di tolak");
			return $this->redirect(['/usul-pesan']);
		}
	}
    public function actionAjukan($id){
		$model = $this->findModel($id);
		$model->status = 1;
		if($model->save(false)){
			Yii::$app->session->setFlash('success', "Data Berhasil diajukan");
			return $this->redirect(['/usul-pesan']);
		}
	}
	public function actionEditSetuju($id){
		if(Yii::$app->user->identity->userdetail->managemen != 1){
			return $this->redirect(['/usul-pesan']);
		}
		$model = UsulPesanDetail::findOne($id);
		$usul = UsulPesan::findOne($model->idup);
		if ($model->load(Yii::$app->request->post())) {
			$model->total = $model->jumlah * $model->harga;
			if($model->save()){
				$harga_total = 0;
				$data_obat = UsulPesanDetail::find()->where(['idup'=>$usul->id])->all();
				foreach($data_obat as $do){
					$harga_total += $do->total;
				}
				$usul->total_harga = $harga_total;
				$usul->save(false);
				return $this->redirect(['usul-pesan/view?id='.$usul->id]);
			}
		}
		return $this->render('edit-setuju',[
			'model'=>$model,
		]);
	}
    public function actionEdit($id){
		$model = UsulPesanDetail::findOne($id);
		$usul = UsulPesan::findOne($model->idup);
		if($usul->status == 1){
			return $this->redirect(['usul-pesan/edit-setuju?id='.$model->id]);
		}
		if ($model->load(Yii::$app->request->post())) {
			$model->total = $model->jumlah * $model->harga;
			if($model->save()){
				$harga_total = 0;
				$data_obat = UsulPesanDetail::find()->where(['idup'=>$usul->id])->all();
				foreach($data_obat as $do){
					$harga_total += $do->total;
				}
				$usul->total_harga = $harga_total;
				$usul->save(false);
				return $this->redirect(['usul-pesan/view?id='.$usul->id]);
			}
		}
		return $this->render('edit',[
			'model'=>$model,
		]);
	}
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$obat = new UsulPesanDetail();
		$searchModel = new UsulPesanDetailSearch();
		$where = ['idup'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		if ($obat->load(Yii::$app->request->post())) {
			$cek_obat = UsulPesanDetail::find()->where(['idup'=>$model->id])->andwhere(['idobat'=>$obat->idobat])->andwhere(['idbacth'=>$obat->idbacth])->count();
			if($cek_obat > 0){
				Yii::$app->session->setFlash('danger', "Obat Sudah masuk pengajuan"); 
				return $this->refresh();
			}
			$obat->total = $obat->jumlah * $obat->harga;
			$obat->jenis = $obat->obat->idjenis;
			$obat->status = 1;
			if($obat->save(false)){
				$harga_total = 0;
				$data_obat = UsulPesanDetail::find()->where(['idup'=>$model->id])->all();
				foreach($data_obat as $do){
					$harga_total += $do->total;
				}
				$model->total_harga = $harga_total;
				$model->save(false);
				return $this->refresh();
			}
			
			
		}
        return $this->render('view', [
            'model' =>$model,
            'obat' => $obat,
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionShowBatch($id,$jenis){
		$model = ObatBacth::find()->where(['idobat'=>$id])->andwhere(['idbayar'=>$jenis])->all();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
		]);
	}

    /**
     * Creates a new UsulPesan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsulPesan();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->status = 10;
			$model->iduser = Yii::$app->user->identity->id;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UsulPesan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UsulPesan model.
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
     * Finds the UsulPesan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UsulPesan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsulPesan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
