<?php

namespace backend\controllers;

use Yii;
use common\models\Ruangan;
use yii\base\Model;
use common\models\RuanganBed;
use common\models\RuanganJenis;
use common\models\RuanganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RuanganController implements the CRUD actions for Ruangan model.
 */
class RuanganController extends Controller
{
    /**
     * {@inheritdoc}
     */
   	public $serializer = [
    'class' => 'yii\rest\Serializer',
    'collectionEnvelope' => 'items',
];

public static function allowedDomains()
{
    return [
       '*' ,  // star allows all domains
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
                    'Origin'=> static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST','GET','PUT','OPTIONS'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age'=> 260000,// Cache (seconds)
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Origin' => false,
					

                ],
				
            ],

        ]);
    }

    /**
     * Lists all Ruangan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RuanganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ruangan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionUpdateAplicares($id){
		$model = Ruangan::findOne($id);
	    Yii::$app->vclaim->updateKamar();
		Yii::$app->session->setFlash('success', "Data berhasil terupdate"); 
		return $this->redirect(Yii::$app->request->referrer);
	}
    public function actionView($id)
    {	
		$model = $this->findModel($id);
		$tambah_bed = new RuanganBed();
		$bed = RuanganBed::find()->where(['idruangan'=>$model->id])->all();
		if ($tambah_bed->load(Yii::$app->request->post())&&Model::validateMultiple([$tambah_bed])) {
			$tambah_bed->terisi = 0;
			$tambah_bed->genKode();
			if($tambah_bed->save(false)){
				$bedcount = RuanganBed::find()->where(['idruangan'=>$model->id])->andwhere(['status'=>1])->count();
				$model->kapasitas = $bedcount;
				$model->save(false);
				return $this->redirect(['view', 'id' => $model->id]);
			}else{
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}
        return $this->render('view', [
            'model' => $model,
            'bed' => $bed,
            'tambah_bed' => $tambah_bed,
        ]);
    }
    /**
     * Creates a new Ruangan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ruangan();

        if ($model->load(Yii::$app->request->post())) {
			$ruangan = RuanganJenis::find()->where(['id'=>$model->idjenis])->one();
			$model->jenis = $ruangan->ket;
			$model->kapasitas = 0;
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
     * Updates an existing Ruangan model.
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
     * Deletes an existing Ruangan model.
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
     * Finds the Ruangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ruangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ruangan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
