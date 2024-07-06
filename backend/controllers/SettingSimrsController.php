<?php

namespace backend\controllers;

use Yii;
use common\models\SettingSimrs;
use common\models\SettingSimrsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * SettingSimrsController implements the CRUD actions for SettingSimrs model.
 */
class SettingSimrsController extends Controller
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
     * Lists all SettingSimrs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSimrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SettingSimrs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SettingSimrs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SettingSimrs();

        if ($model->load(Yii::$app->request->post())) {
			$image=UploadedFile::getInstance($model,'logo_rs');
            if (!$image == null) {
                $model->logo_rs= $model->nama_rs.$image->name;
                $path = Yii::$app->params['imagePath'] .'/setting/'.$model->logo_rs;
                
                //create thumbnail
                if($image->saveAs($path)){
                    Image::thumbnail($path, 200, 200)->save(Yii::getAlias(Yii::$app->params['imagePath'] .'/setting/thumbnail/'.$model->logo_rs), ['quality' => 50]);
                }
            }
            else{
                $model->logo_rs='default-placeholder.png';
            }
            if($model->save(false)){
				 return $this->redirect(['index']);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SettingSimrs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			$image=UploadedFile::getInstance($model,'logo_rs');
			if (!$image == null) {
				
                $model->logo_rs= $model->nama_rs.$image->name;
                $path = Yii::$app->params['imagePath'] .'/setting/'.$model->logo_rs;
                
                //create thumbnail
                if($image->saveAs($path)){
					
                    Image::thumbnail($path, 200, 200)->save(Yii::getAlias(Yii::$app->params['imagePath'] .'/setting/thumbnail/'.$model->logo_rs), ['quality' => 50]);
                }
            }
            else{
				 $model->logo_rs= $model->logo_rs;
            }
			
			if($model->save(false)){
				 return $this->redirect(['index']);
			}
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SettingSimrs model.
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
     * Finds the SettingSimrs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SettingSimrs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SettingSimrs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
