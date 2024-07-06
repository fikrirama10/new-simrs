<?php

namespace backend\controllers;

use Yii;
use common\models\Obat;
use common\models\ObatKartustok;
use common\models\ObatMutasi;
use common\models\ObatSubjenismutasi;
use common\models\GudangInventori;
use common\models\ObatBacth;
use common\models\ObatSeacrh;
use common\models\ObatBacthSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObatController implements the CRUD actions for Obat model.
 */
class ObatController extends Controller
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
	public function actionBarangHabis(){		
		$url = Yii::$app->params['baseUrl'].'dashboard/obat-rest/obat-barang-habis?jenis='.Yii::$app->user->identity->userdetail->idgudang;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('barang-habis',[
			'json'=>$json,
		]);
	}
	public function actionKadaluarsa(){		
		$url = Yii::$app->params['baseUrl'].'dashboard/obat-rest/obat-ed?jenis='.Yii::$app->user->identity->userdetail->idgudang;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('barang-ed',[
			'json'=>$json,
		]);
	}
	public function actionKeluarMasuk(){		
		$tgl = date('Y-m',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$tgli = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		$url = Yii::$app->params['baseUrl'].'dashboard/obat-rest/obat-keluar-masuk?idgudang='.Yii::$app->user->identity->userdetail->idgudang.'&start='.$tgl.'-01&end='.$tgli;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('barang-keluar-masuk',[
			'json'=>$json,
		]);
	}
	
	public function actionMutasiStok($id,$asal){
		$tgl = date('Y-m');
		$url = Yii::$app->params['baseUrl'].'dashboard/rest/mutasi-stok?&awal='.$tgl.'-01&akhir='.date('Y-m-d').'&idobat='.$id.'&asal='.$asal;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('mutasi-stok',[
			'json'=>$json,
			'id'=>$id,
			'asal'=>$asal,
		]);
	}
	public function actionListSub($id)
	{
		$models=ObatSubjenismutasi::find()->where(['idjenis' => $id])->all();

		foreach($models as $k){
		  echo "<option value='".$k->id."'>".$k->subjenis."</option>";
		}
	}
    /**
     * Lists all Obat models.
     * @return mixed
     */
	public function actionKartuStok($id,$asal){
		$tgl = date('Y-m');
		$url = Yii::$app->params['baseUrl'].'dashboard/rest/kartu-stok?idobat='.$id.'&awal='.$tgl.'-01&akhir='.date('Y-m-d').'&asal='.$asal;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->render('kartu-stok',[
			'json'=>$json,
			'id'=>$id,
			'asal'=>$asal,
		]);
	}
	public function actionShowMutasi($id,$idbacth,$awal,$akhir,$asal,$jenis,$subjenis){
		$url = Yii::$app->params['baseUrl'].'dashboard/rest/mutasi-stok?awal='.$awal.'&akhir='.$akhir.'&idobat='.$id.'&asal='.$asal.'&jenis='.$jenis.'&subjenis='.$subjenis.'&idbatch='.$idbacth;
		$url = Yii::$app->params['baseUrl'].'dashboard/rest/mutasi-stok?awal='.$awal.'&akhir='.$akhir.'&idobat='.$id.'&asal='.$asal.'&jenis='.$jenis.'&subjenis='.$subjenis.'&idbatch='.$idbacth;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show-mutasi',[
			'json'=>$json,
		]);
	}
	public function actionShowKartu($id,$idbacth,$awal,$akhir,$asal,$jenis){
		if($idbacth == 0){
			if($jenis == 0){
				$url = Yii::$app->params['baseUrl'].'dashboard/rest/kartu-stok?idobat='.$id.'&awal='.$awal.'&akhir='.$akhir.'&asal='.$asal;
			}else{
				$url = Yii::$app->params['baseUrl'].'dashboard/rest/kartu-stok?idobat='.$id.'&awal='.$awal.'&akhir='.$akhir.'&asal='.$asal.'&jenis='.$jenis;
			}			
		}else{
			if($jenis == 0){
				$url = Yii::$app->params['baseUrl'].'dashboard/rest/kartu-stok?idobat='.$id.'&awal='.$awal.'&akhir='.$akhir.'&asal='.$asal;
			}else{
				$url = Yii::$app->params['baseUrl'].'dashboard/rest/kartu-stok?idobat='.$id.'&awal='.$awal.'&akhir='.$akhir.'&asal='.$asal.'&jenis='.$jenis.'&idbatch='.$idbacth;
			}
		}
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		return $this->renderAjax('show-kartu',[
			'json'=>$json,
		]);
	}
    public function actionEditBacth($id){
		$model = ObatBacth::findOne($id);
		$tgl = date('Y-m-d H:i:s');
		if ($model->load(Yii::$app->request->post())) {
			$koreksi = Yii::$app->request->post('koreksi');	
			$koreksi_apotek = Yii::$app->request->post('koreksi2');	
			if($koreksi == null){
				$model->stok_gudang = $model->stok_gudang;
			}else if($koreksi < $model->stok_gudang){
				$jumlah = $model->stok_gudang - $koreksi;
				Yii::$app->kazo->kartuStok($model->idobat,$model->id,1,$jumlah,1);	
				Yii::$app->kazo->mutasiStok($model->idobat,$model->id,5,8,$jumlah,0,$model->stok_gudang,1);
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$model->idobat,$jumlah,$tgl,1);
				$model->stok_gudang = $koreksi;
			}else if($koreksi > $model->stok_gudang){
				$jumlah = $koreksi - $model->stok_gudang;
				Yii::$app->kazo->kartuStok($model->idobat,$model->id,1,$jumlah,2);	
				Yii::$app->kazo->mutasiStok($model->idobat,$model->id,3,7,$jumlah,0,$model->stok_gudang,1);
				$model->stok_gudang = $koreksi;
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$model->idobat,$jumlah,$tgl,2);
			}
			if($model->save()){				
				return $this->redirect(['/obat/view?id='.$model->idobat]);				
			}
			
		}
		return $this->render('edit-bacth',[
			'model'=>$model,
		]);
	}
    public function actionIndex()
    {
        $searchModel = new ObatSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Obat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$obat = new ObatBacth();
		$inventori = new GudangInventori();		
		$model = $this->findModel($id);
		$gudang_inventori = GudangInventori::find()->where(['idobat'=>$model->id])->andwhere(['idgudang'=>1])->count();
		$bacth = ObatBacth::find()->where(['idobat'=>$model->id])->all();
		$searchModel = new ObatBacthSearch();
		$where = ['idobat'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);		
		if ($obat->load(Yii::$app->request->post())) {
			if($gudang_inventori < 1){
				$inventori->idgudang = 1;
				$inventori->idobat = $model->id;
				$inventori->stok = $obat->stok_gudang;
				$inventori->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
				$inventori->save();
					
			}else{
				$gi = GudangInventori::find()->where(['idobat'=>$model->id])->where(['idgudang'=>1])->one();
				$gi->stok = $gi->stok + $obat->stok_gudang;
				$gi->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
				$gi->save();
			}
			$obat->stok_apotek = 0;
			if($obat->save()){
				if($obat->stok_gudang > 0){
					$kartustok = new ObatKartustok();
					$kartustok->idobat = $obat->idobat;
					$kartustok->idbatch = $obat->id;
					$kartustok->jumlah = $obat->stok_gudang;
					$kartustok->idasal = 1;
					$kartustok->jenis = 2;
					$kartustok->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
					$kartustok->save(false);	
					Yii::$app->kazo->mutasiStok($obat->idobat,$obat->id,4,7,$obat->stok_gudang,$obat->id,0,Yii::$app->user->identity->userdetail->idgudang);					
				}
				return $this->refresh();
			}			
           
        }else if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('view', [
            'model' => $model,
            'obat' => $obat,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Obat();

        if ($model->load(Yii::$app->request->post())) {
			
			$model->abjad = substr($model->nama_obat,0,1);
			if($model->save(false)){
				return $this->redirect(['view', 'id' => $model->id]);
			}
			
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Obat model.
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
     * Deletes an existing Obat model.
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
     * Finds the Obat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Obat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Obat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
