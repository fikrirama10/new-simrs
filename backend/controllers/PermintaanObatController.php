<?php

namespace backend\controllers;

use Yii;
use common\models\Obat;
use common\models\ObatKartustok;
use common\models\ObatBacth;
use common\models\ObatBacthSearch;
use common\models\ObatMutasi;
use common\models\GudangInventori;
use common\models\ObatSeacrh;
use common\models\PermintaanObat;
use common\models\PermintaanObatdetail;
use common\models\PermintaanObatRequest;
use common\models\PermintaanObatRequestSearch;
use common\models\PermintaanObatdetailSearch;
use common\models\PermintaanObatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * PermintaanObatController implements the CRUD actions for PermintaanObat model.
 */
class PermintaanObatController extends Controller
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
     * Lists all PermintaanObat models.
     * @return mixed
     */
    public function actionPrintRekap($start,$end) {
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/bekkes?start='.$start.'&end='.$end;
		$content_model = file_get_contents($url);
		$json = json_decode($content_model, true);
		$url_unit = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/unit';
		$content_unit = file_get_contents($url_unit);
		$json_unit = json_decode($content_unit, true);
		$content = $this->renderPartial('rekap',['model' => $json,'start'=>$start,'unit'=>$json_unit]);
	  
	  // setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'format' => Pdf::FORMAT_A4, 
		   'orientation' => Pdf::ORIENT_LANDSCAPE,
		   'marginTop' => '10',
		   'marginLeft' => '20',
		   'marginRight' => '0',
		   'marginBottom' => '20',
		   'content' => $content,  
		   
		   'cssFile' => '@frontend/web/css/paperbarang.css',
		   //'options' => ['title' => 'Bukti Permohonan Informasi'],

			
		]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}
    public function actionIndex()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
        $searchModel = new PermintaanObatSearch();
		$where = ['iduser_peminta'=>Yii::$app->user->identity->id];
		if(Yii::$app->user->identity->userdetail->idgudang == 1){
		    $where = ['status'=>13];
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
				return $this->render('index2', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}else{
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
				return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}

    }
	
	public function actionUnit()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
        $searchModel = new PermintaanObatSearch();
		$where = ['asal_permintaan'=>Yii::$app->user->identity->userdetail->idunit];

			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
				return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);

    }

    /**
     * Displays a single PermintaanObat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionGetBacth()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = ObatBacth::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionGetObat()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$model = ObatBacth::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
    	public function actionBatalPermintaan($id) {
    	    
    	    $model = PermintaanObat::findOne($id);
    	    $detail = PermintaanObatDetail::find()->where(['idpermintaan'=>$model->id])->all();
    	    foreach($detail as $d){
    	        $d->delete();
    	    }
    	    $model->delete();
    	    return $this->redirect(['index']);
        }
    public function actionEditPermintaanAlkes($id){
        $model = PermintaanObat::findOne($id);
        $model->status = 10;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
        
    }
	public function actionFormPermintaan($id) {
	  //tampilkan bukti proses
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$model = PermintaanObat::find()->where(['id' =>$id ])->one();
		$model_list = PermintaanObatdetail::find()->where(['idpermintaan'=>$model->id])->all();
		$request = PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->orderBy(['nama_obat'=>SORT_ASC])->all();
		$content = $this->renderPartial('form-pengajuan',['model' => $model,'model_list'=>$model_list,'request'=>$request]);
	  
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
	public function actionFormSetuju($id) {
	  //tampilkan bukti proses
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$model = PermintaanObat::find()->where(['id' =>$id ])->one();
		$model_list = PermintaanObatdetail::find()->where(['idpermintaan'=>$model->id])->all();
		$request = PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->orderBy(['nama_obat'=>SORT_ASC])->all();
		$content = $this->renderPartial('form-setuju',['model' => $model,'model_list'=>$model_list,'request'=>$request]);
	  
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
	
	public function actionSelesaiPengajuan($id){
		$model = $this->findModel($id);
		$model->status = 1;
		if($model->save()){
			Yii::$app->session->setFlash('success', 'Pengajuan permintaan obat telah di ajukan ke bagian gudang');
			return $this->redirect(['/permintaan-obat/index']);
		}
	}
	public function actionSelesaiPersetujuan($id){
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$model = $this->findModel($id);
		$permintaan  = PermintaanObatdetail::find()->where(['idpermintaan'=>$model->id])->where(['status'=>1])->all();
		$mutasi = new ObatMutasi();	
		$mutasi2 = new ObatMutasi();		
		$model->status = 5;
		$model->iduser_persetujuan =  Yii::$app->user->identity->id ;
		
		foreach($permintaan as $pr){
		$obat_bacth = ObatBacth::find()->where(['id'=>$pr->idbacth])->one();
		Yii::$app->kazo->kartuStok($pr->idobat,$pr->idbacth,1,$pr->jumlah_setuju,1);	
		Yii::$app->kazo->mutasiStok($pr->idobat,$pr->idbacth,1,5,$pr->jumlah_setuju,$pr->id,$obat_bacth->stok_gudang,1);	
		if($model->asal_permintaan == 5){
			Yii::$app->kazo->kartuStok($pr->idobat,$pr->idbacth,$model->user->userdetail->idgudang,$pr->jumlah_setuju,2);	
			Yii::$app->kazo->mutasiStok($pr->idobat,$pr->idbacth,2,6,$pr->jumlah_setuju,$pr->id,$obat_bacth->stok_apotek,$model->user->userdetail->idgudang);	
		}
		$gudang = GudangInventori::find()->where(['idgudang'=>1])->andwhere(['idobat'=>$pr->idobat])->one();
		$gudang_dua = GudangInventori::find()->where(['idgudang'=>$model->user->userdetail->idgudang])->andwhere(['idobat'=>$pr->idobat])->one();
		$obat_bacth->stok_gudang = $obat_bacth->stok_gudang - $pr->jumlah_setuju;
		$obat_bacth->stok_apotek = $obat_bacth->stok_apotek + $pr->jumlah_setuju;
		$obat_bacth->save(false);
		$ob = 0;
		$oba = 0;
		$obat_bacth_gudang = ObatBacth::find()->where(['idobat'=>$pr->idobat])->all();
		foreach($obat_bacth_gudang as $obg){
			$ob += $obg->stok_gudang;
			$oba += $obg->stok_apotek;
		}
		if($gudang){				
			$gudang->stok = $ob;
			$gudang->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			$gudang->save(false);
		}else{					
			$buat_gudang = new GudangInventori();
			$buat_gudang->idgudang = 1;
			$buat_gudang->idobat = $pr->idobat;
			$buat_gudang->stok = $ob;
			$buat_gudang->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			$buat_gudang->save(false);
		}
		if($gudang_dua){				
			$gudang_dua->stok = $oba;
			$gudang_dua->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			$gudang_dua->save(false);
		}else{					
			$buat_gudang = new GudangInventori();
			$buat_gudang->idgudang = $model->user->userdetail->idgudang;
			$buat_gudang->idobat = $pr->idobat;
			$buat_gudang->stok = $oba;
			$buat_gudang->tgl_update = date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			$buat_gudang->save(false);
		}
		
		
	}
		$model->save();
		Yii::$app->session->setFlash('success', 'Permintaan Obat Selesai');
		return $this->redirect(['/permintaan-obat/index']);		
	}
    public function actionView($idbacth='',$id)
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$model = $this->findModel($id);
		$detail_permintaan = new PermintaanObatdetail();
		$detail_permintaan_list = PermintaanObatdetail::find()->where(['idpermintaan'=>$model->id])->all();
		$permintaan = new PermintaanObatRequest();
		$permintaan_list = PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->all();
		
		if(Yii::$app->user->identity->idpriv  != 5){
		   
			$searchModel = new ObatBacthSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			if ($permintaan->load(Yii::$app->request->post()) ) {
				$data_permintaan = PermintaanObatRequest::find()->where(['idobat'=>$permintaan->idobat])->andwhere(['idpermintaan'=>$model->id])->count();
				if($data_permintaan > 0){
					Yii::$app->session->setFlash('warning', 'Data obat sudah terdaftar dalam permintaan');
					return $this->refresh();
				}
		    	if($permintaan->idobat == null){
					$permintaan->baru = 1;
				}
				$permintaan->total = $permintaan->harga * $permintaan->jumlah;
				$permintaan->status = 1;
				if($permintaan->save()){
					return $this->refresh();
				}
			}
			return $this->render('view2', [
				'model' => $this->findModel($id),
				'permintaan' => $permintaan,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'permintaan_list' => $permintaan_list,
				'detail_permintaan_list' => $detail_permintaan_list,
			]);
		}else{
		     if($model->iduser_peminta == 24){
		        	$searchModel = new ObatBacthSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			if ($permintaan->load(Yii::$app->request->post()) ) {
				$data_permintaan = PermintaanObatRequest::find()->where(['idobat'=>$permintaan->idobat])->andwhere(['idpermintaan'=>$model->id])->count();
				if($data_permintaan > 0){
					Yii::$app->session->setFlash('warning', 'Data obat sudah terdaftar dalam permintaan');
					return $this->refresh();
				}
		    	if($permintaan->idobat == null){
					$permintaan->baru = 1;
				}
				$permintaan->total = $permintaan->harga * $permintaan->jumlah;
				$permintaan->status = 1;
				if($permintaan->save()){
					return $this->refresh();
				}
			}
			return $this->render('view2', [
				'model' => $this->findModel($id),
				'permintaan' => $permintaan,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'permintaan_list' => $permintaan_list,
				'detail_permintaan_list' => $detail_permintaan_list,
			]);
		    }else{
		       	$searchModel = new PermintaanObatRequestSearch();
			$where= ['idpermintaan'=>$model->id];
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
			 foreach($permintaan_list as $index => $pl){
				if ($pl->load(Yii::$app->request->post())) {
					$bacthobat = ObatBacth::find()->where(['id'=>$pl->idbacth])->one();
					if($bacthobat->stok_gudang < $pl->jumlah_setuju){
						Yii::$app->session->setFlash('warning', 'Gagal , Jumlah melebihi stok yang ada');
						return $this->refresh();
					}else{
						$data_permintaan = PermintaanObatdetail::find()->where(['id'=>$idbacth])->one();
						$data_permintaan->idbacth = $pl->idbacth;
						$data_permintaan->jumlah_setuju = $pl->jumlah_setuju;
						$data_permintaan->keterangan = $pl->keterangan;
						$data_permintaan->status = 10;
						$data_permintaan->save();
						return $this->redirect(['view', 'id' => $model->id]);
					}
					
				}
			 }
			 if($detail_permintaan->load(Yii::$app->request->post())){
				 $obat_bacth = ObatBacth::find()->where(['id'=>$detail_permintaan->idbacth])->one();
				 if($obat_bacth->stok_gudang < $detail_permintaan->jumlah_setuju){
					 Yii::$app->session->setFlash('warning', 'Jumlah stok tidak cukup');
					 return $this->refresh();
				 }
				 $detail_permintaan->status = 1;
				 if($detail_permintaan->save()){
					 return $this->refresh();
				 }
			 }
			return $this->render('view', [
				'model' => $this->findModel($id),
				'permintaan' => $permintaan,
				'permintaan_list' => $permintaan_list,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'detail_permintaan' => $detail_permintaan,
				'detail_permintaan_list' => $detail_permintaan_list,
				
			]); 
		    }
		
		}
	
		
    }
	public function actionEditItem($id,$jumlah){
		$detail = PermintaanObatRequest::find()->where(['id'=>$id])->one();
		$detail->jumlah = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				if($detail->save(false)){	
					if($detail->jumlah == 0){
						$detail->delete();
					}
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	public function actionEditItemSetuju($id,$jumlah){
		$detail = PermintaanObatdetail::find()->where(['id'=>$id])->one();
		$detail->jumlah_setuju = $jumlah;
			if($jumlah < 0){
				$model = 404;
			}else{
				if($detail->save(false)){	
					if($jumlah == 0){
						$detail->status = 2;
						$detail->jumlah_setuju = 0;
						$detail->keterangan = 'ditolak';
						$detail->save(false);
					}
					$model = "Sukses";
				}
			}
			
		return \yii\helpers\Json::encode($model);
	}
	
    public function actionCreate()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
		$permintaan = PermintaanObat::find()->where(['iduser_peminta'=>Yii::$app->user->identity->id])->andwhere(['status'=>10])->one();
		if($permintaan){
			Yii::$app->session->setFlash('warning', 'Silahkan Selesaikan pengajuan sebelumnya');
			return $this->redirect(['/permintaan-obat/view?id='.$permintaan->id]);
		}
        $model = new PermintaanObat();
		
        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->iduser_peminta = Yii::$app->user->identity->id ;
			$model->tgl_permintaan = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
			$model->status = 10;
			$model->asal_permintaan = Yii::$app->user->identity->userdetail->idunit;
				$model->idruangan = Yii::$app->user->identity->userdetail->idruangan;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PermintaanObat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermintaanObat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermintaanObat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermintaanObat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
