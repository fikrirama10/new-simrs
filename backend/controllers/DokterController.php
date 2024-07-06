<?php

namespace backend\controllers;

use Yii;
use common\models\Hari;
use common\models\Dokter;
use common\models\DokterJadwal;
use common\models\DokterSeach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * DokterController implements the CRUD actions for Dokter model.
 */
class DokterController extends Controller
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
     * Lists all Dokter models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
        $searchModel = new DokterSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dokter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSelesai($id){
		$dokter = Dokter::findOne($id);
		$hari = date('N',strtotime(date('Y-m-d')));
		$jadwal = DokterJadwal::find()->where(['iddokter'=>$dokter->id])->andwhere(['status'=>1])->andwhere(['<>','idhari',$hari])->all();
		$arrjad = array();
		foreach($jadwal as $j){
			array_push($arrjad,[
				'hari'=>$j->idhari,
				'buka'=>date('H:i',strtotime($j->jam_mulai)),
				'tutup'=>date('H:i',strtotime($j->jam_selesai)),
			]);
		}
		$result =  array(
			'kodepoli'=>$dokter->poli->kode,
			'kodesubspesialis'=>$dokter->poli->kode,
			'kodedokter'=>$dokter->kode_dpjp,
			'jadwal'=>$arrjad,
			
		);
		$post_update = Yii::$app->hfis->update_jadwal($result);
		if($post_update['metadata']['code'] == '201'){
			Yii::$app->session->setFlash('danger', $post_update['metadata']['message']); 
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}else{
			return $this->redirect(['index']);
		}
		
	}
    public function actionView($id)
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		$model = $this->findModel($id);
		$hari = Hari::find()->all();
        return $this->render('view', [
            'model' => $model,
            'hari' => $hari,
        ]);
    }
	//Edit Jadwal Dokter
	public function actionEditJadwal($id){
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		$jadwal = DokterJadwal::find()->where(['id'=>$id])->one();
		$model = Dokter::find()->where(['id'=>$jadwal->iddokter])->one();
		 if ($jadwal->load(Yii::$app->request->post()) && $jadwal->save()) {
			 return $this->redirect(['dokter/'.$model->id]);
		 }
		return $this->render('edit-jadwal',[
			'model'=>$model,
			'jadwal'=>$jadwal,
		]);
	}
	public function actionTambahJadwal($id,$dokter){
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
		$hari = Hari::find()->where(['id'=>$id])->one();
		$model = Dokter::find()->where(['id'=>$dokter])->one();
		$jadwal = new DokterJadwal();
		if ($jadwal->load(Yii::$app->request->post()) && $jadwal->save()) {
			 return $this->redirect(['dokter/'.$model->id]);
		}
		return $this->render('tambah-jadwal',[
			'jadwal'=>$jadwal,
			'hari'=>$hari,
			'model'=>$model,
		]);
	}
    /**
     * Creates a new Dokter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dokter();
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$image=UploadedFile::getInstance($model,'foto');
            if (!$image == null) {
                $model->foto= $model->kode_dokter.$image->name;
                $path = Yii::$app->params['imagePath'] .'/dokter/'.$model->foto;
                
                //create thumbnail
                if($image->saveAs($path)){
                    Image::thumbnail($path, 400, 600)->save(Yii::getAlias(Yii::$app->params['imagePath'] .'/dokter/thumbnail/'.$model->foto), ['quality' => 50]);
                }
            }
            else{
                $model->foto='';
            }
			if($model->status == 0){
				$model->status = 2;
			}
			if($model->save(false)){
				 return $this->redirect(['view', 'id' => $model->id]);
			}else{
				return $this->render('create', [
            'model' => $model,
			]);
			}
           
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Dokter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$imageold=Yii::$app->params['imagePath'] .'/dokter/'.$model->foto;
		$imagethumb=Yii::$app->params['imagePath'] .'/dokter/thumbnail/'.$model->foto;
        if($model->load(Yii::$app->request->post())){
			
			$image=UploadedFile::getInstance($model,'foto');
			
			if (!$image == null) {
				$model->foto=$model->kode_dokter.$image->name;
				$path = Yii::$app->params['imagePath'] .'/dokter/'.$model->foto;
				if($image->saveAs($path)){
                    Image::thumbnail($path, 400, 600)->save(Yii::getAlias(Yii::$app->params['imagePath'] .'/dokter/thumbnail/'.$model->foto), ['quality' => 50]);
					
                }
				
				}
			else{
				$model->foto = $this->findModel($id)->foto;
			}
									
			if( $model->save()){
                return $this->redirect(['index']);
            } else {
                return $this->render('update', ['model' => $model,]);
            }
		}
		else{
			return $this->render('update', ['model' => $model,]);
		}
    }

    /**
     * Deletes an existing Dokter model.
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
     * Finds the Dokter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dokter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dokter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
