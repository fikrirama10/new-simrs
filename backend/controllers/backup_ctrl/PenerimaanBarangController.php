<?php

namespace backend\controllers;

use Yii;
use common\models\UsulPesan;
use common\models\ObatBacth;
use common\models\Obat;
use common\models\PenerimaanBarang;
use common\models\PenerimaanBarangDetail;
use common\models\PenerimaanBarangDetailSearch;
use common\models\PenerimaanBarangSearch;
use kartik\mpdf\Pdf;	
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenerimaanBarangController implements the CRUD actions for PenerimaanBarang model.
 */
class PenerimaanBarangController extends Controller
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
     * Lists all PenerimaanBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenerimaanBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    	public function actionPrintRekap($start,$end) {
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/penerimaan-barang?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('rekap',['model' => $json,'start'=>$start,'judul'=>'Obat & Alkes']);
	  
	  // setup kartik\mpdf\Pdf component
				$pdf = new Pdf([
		   'mode' => Pdf::MODE_CORE,
		   'destination' => Pdf::DEST_BROWSER,
		   'format' => Pdf::FORMAT_A4, 
		   'orientation' => Pdf::ORIENT_LANDSCAPE,
		   'marginTop' => '10',
		   'marginLeft' => '20',
		   'marginRight' => '0',
		   'marginBottom' => '10',
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
		public function actionPrintRekapAtk($start,$end) {
		$url = Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/penerimaan-atk?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		$content = $this->renderPartial('rekap',['model' => $json,'start'=>$start,'judul'=>'Barang & ATK']);
	  
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

    /**
     * Displays a single PenerimaanBarang model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id){
		$model = PenerimaanBarangDetail::findOne($id);
		$penerimaan = $this->findModel($model->idpenerimaan);
		if ($model->load(Yii::$app->request->post())) {
			$model->total = $model->jumlah * $model->harga;
			$diskon = $model->diskon / 100;
				$harga_diskon = $model->total - ($model->total * $diskon);
		    	$ppn = $harga_diskon * ($model->ppn/100);
				$model->ppn = $ppn;
				//$barang->ppn = $ppn;
				$model->total_diskon = $harga_diskon;
			if($model->save()){
				$harga_total = 0;
				$data_obat = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$penerimaan->id])->all();
				foreach($data_obat as $do){
					$harga_total += $do->total_diskon + $do->ppn;
				}
				$penerimaan->nilai_faktur = $harga_total;
				$penerimaan->save(false);
				return $this->redirect(['penerimaan-barang/view?id='.$penerimaan->id]);
			}
		}
		return $this->render('edit',[
			'model'=>$model, 
		]);
	}
    public function actionSelesai($id){
		$model = $this->findModel($id);
		$penerimaan = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		foreach($penerimaan as $p){
			$obat_bacth = ObatBacth::find()->where(['id'=>$p->idbacth])->one();
			$obat_bacth->harga_beli = ($p->total_diskon + $p->ppn) / $p->jumlah;
			$obat_bacth->harga_jual = $obat_bacth->harga_beli  + ($obat_bacth->harga_beli * 0.20);
			$obat_bacth->save();
		}
		return $this->redirect(['/penerimaan-barang']);
	}
    public function actionPenerimaanSelesai($id){
		$model = $this->findModel($id);
		$tgl = date('Y-m-d H:i:s');
		$penerimaan = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		foreach($penerimaan as $pr){
			$obat_bacth = ObatBacth::find()->where(['id'=>$pr->idbacth])->one();
			$obat_bacth_gudang = ObatBacth::find()->where(['idobat'=>$pr->idbarang])->all();
			Yii::$app->kazo->kartuStok($pr->idbarang,$pr->idbacth,1,$pr->jumlah,2);	
			Yii::$app->kazo->mutasiStok($pr->idbarang,$pr->idbacth,2,2,$pr->jumlah,$pr->id,$obat_bacth->stok_gudang,1);
			$obat_bacth->stok_gudang = $obat_bacth->stok_gudang + $pr->jumlah;
			$ob = 0;
			foreach($obat_bacth_gudang as $obg){
				$ob += $obg->stok_gudang;
			}
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$pr->idbarang,$pr->jumlah,$tgl,2);
			$obat_bacth->save(false);
		}
		$model->status = 2;
		$model->save(false);
		Yii::$app->session->setFlash('success', 'Penerimaan Obat Selesai');
		return $this->redirect(['/penerimaan-barang/index']);	
	}
      public function actionView($id)
    {
		$barang = new PenerimaanBarangDetail();
		$bacth = new ObatBacth();
		$dataBarang = new Obat();
		$searchModel = new PenerimaanBarangDetailSearch();
		$model = $this->findModel($id);
		$where = ['idpenerimaan'=>$model->id];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);
		if($barang->load(Yii::$app->request->post()) && $bacth->load(Yii::$app->request->post())) {	
			$cek_bacth = ObatBacth::findOne($barang->idbacth);
			if($barang->diskon == null){
				$barang->diskon = 0;
			}
			if($cek_bacth){
				$barang->harga = $bacth->harga_beli;
				$barang->total = $bacth->harga_beli * $barang->jumlah;
				$diskon = $barang->diskon / 100;
				$harga_diskon = $barang->total - ($barang->total * $diskon);
		    	$ppn = $harga_diskon * ($barang->ppn/100);
				$barang->ppn = $ppn;
				//$barang->ppn = $ppn;
				$barang->total_diskon = $harga_diskon;
				if($barang->save(false)){
					$cek_bacth->save();
					$harga_total = 0;
					$data_obat = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
					foreach($data_obat as $do){
						$harga_total += $do->total_diskon + $do->ppn;
					}
					$model->nilai_faktur = $harga_total;
					$model->save(false);
					return $this->refresh();
					
				}
			}else{
				$bacth->idobat = $barang->idbarang;
				$bacth->harga_jual = $bacth->harga_beli  + ($bacth->harga_beli * 0.20);
				//$bacth->idbayar = $model->idbayar;
				$bacth->idsuplier = $model->idsuplier;
				$bacth->stok_apotek = 0;
				$bacth->stok_gudang = 0;
				if($bacth->save(false)){
					$barang->idbacth = $bacth->id;
					$barang->harga = $bacth->harga_beli;
					$barang->total = $bacth->harga_beli * $barang->jumlah;
					$diskon = $barang->diskon / 100;
					$harga_diskon = $barang->total - ($barang->total * $diskon);
					$ppn = $harga_diskon * ($barang->ppn/100);
					$barang->ppn = $ppn;
					//$barang->ppn = $ppn;
					$barang->total_diskon = $harga_diskon;
					if($barang->save()){
						$harga_total = 0;
						$data_obat = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
						foreach($data_obat as $do){
							$harga_total += $do->total_diskon + $do->ppn;
						}
						$model->nilai_faktur = $harga_total;
						$model->save(false);
						return $this->refresh();
					}
				}
			}
			
		}else if($dataBarang->load(Yii::$app->request->post())) {
			$dataBarang->abjad = substr($dataBarang->nama_obat,0,1);
			if($dataBarang->save(false)){
				return $this->refresh();
			}
			
            
        }
        return $this->render('view', [
            'model' => $model,
            'dataBarang' => $dataBarang,
            'barang' => $barang,
            'bacth' => $bacth,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
        public function actionPenerimaanBatal($id){
		$model = $this->findModel($id);
		$detail = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all();
		foreach($detail as $d){
			$d->delete();
		}
		$model->delete();
		return $this->redirect(['index']);
	}
	public function actionDeleteBarang($id)
    {
        $model = PenerimaanBarangDetail::findOne($id);
		$model2 = PenerimaanBarang::findOne($model->idpenerimaan);
		$model->delete();
		$harga_total = 0;
		$data_obat = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->idpenerimaan])->all();
		foreach($data_obat as $do){
			$harga_total += $do->total;
		}
		$model2->nilai_faktur = $harga_total;
		$model2->save(false);
		return $this->redirect(Yii::$app->request->referrer);

        return $this->redirect(['index']);
    }
    /**
     * Creates a new PenerimaanBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PenerimaanBarang();

        if ($model->load(Yii::$app->request->post())) {
			$model->genKode();
			$model->iduser = Yii::$app->user->identity->id;
			$model->status = 1;
			$model->nilai_faktur = 0;
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	public function actionShowUp($id){
		$model = new PenerimaanBarang();
		$up = UsulPesan::find()->where(['kode_up'=>$id])->andwhere(['status'=>2])->one();
		
		return $this->renderAjax('show-up',[
			'up'=>$up,
			'model'=>$model,
		]);
	}
	public function actionShowMerk($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->all();
		$bacth = new ObatBacth();
		return $this->renderAjax('show-merk',[
			'model'=>$model,
			'id'=>$id,
			'bacth'=>$bacth,
			
		]);
	}
	public function actionShowBatch($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->all();
		$bacth = new ObatBacth();
		return $this->renderAjax('show-batch',[
			'model'=>$model,
			'id'=>$id,
			'bacth'=>$bacth,
			
		]);
	}
	public function actionShowForm($id){
		$model = ObatBacth::find()->where(['idobat'=>$id])->all();
		$bacth = new ObatBacth();
		return $this->renderAjax('show-form',[
			'model'=>$model,
			'id'=>$id,
			'bacth'=>$bacth,
			
		]);
	}
    /**
     * Updates an existing PenerimaanBarang model.
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
     * Deletes an existing PenerimaanBarang model.
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
     * Finds the PenerimaanBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenerimaanBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenerimaanBarang::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
