<?php

namespace backend\controllers;

use Yii;
use common\models\RawatKunjungan;
use common\models\SoapRadiologi;
use common\models\SoapRadiologiSearch;
use common\models\TransaksiDetailRinci;
use common\models\RadiologiTindakan;
use common\models\RadiologiHasil;
use common\models\RadiologiHasilSearch;
use common\models\RawatSearch;
use common\models\RadiologiHasildetail;
use common\models\RadiologiHasilfoto;
use kartik\mpdf\Pdf;
use common\models\Rawat;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\Transaksi;
use common\models\TransaksiDetail;
use common\models\Pasien;
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
class RadiologiOrderController extends Controller
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
	
	public function beforeAction($action){
		if(parent::beforeAction($action)){
			$headers = Yii::$app->user->identity->idpriv;
			if($headers == 15 ||  $headers == 7 || $headers == 1 || $headers == 2){
					return true;				
			}else{
				return $this->redirect(['/site']);
			}
		}
		throw new \yii\web\UnauthorizedHttpException('Error');
		return false;
	}
    /**
     * Lists all Dokter models.
     * @return mixed
     */
	public function actionDeletePeriksa($id){
		$model= RadiologiHasildetail::findOne($id);
		$soap= SoapRadiologi::findOne($model->idpengantar);
		$trx = TransaksiDetailRinci::find()->where(['idrawat'=>$model->idrawat])->andwhere(['idrad'=>$model->id])->andwhere(['idtarif'=>$model->tindakan->idtindakan])->one();
		$soap->delete();
		if($trx){
			$trx->delete();
		}
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionBatalkan($id){
		$model = RadiologiHasil::findOne($id);
		$detail = RadiologiHasildetail::find()->where(['idhasil'=>$model->id])->all();
		$foto = RadiologiHasilfoto::find()->where(['idhasil'=>$model->id])->all();
		
		foreach($detail as $d){
			$hasil = SoapRadiologi::find()->where(['idrawat'=>$model->idrawat])->andwhere(['idtindakan'=>$d->idtindakan])->one();
			$d->delete();
			$hasil->delete();
		}
		if($foto){
			foreach($foto as $f){
				$f->delete();
			}
		}
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionLaporan(){
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pemeriksaan-radiologi?start='.date('Y-m-').'01&end='.$tgl;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
        return $this->render('laporan',
			['model'=>$json]
		);
		// return $tgl;
	}
	public function actionShowKunjungan($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pemeriksaan-radiologi?start='.$start.'&end='.$end;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
        return $this->renderAjax('show-cari',
			['model'=>$json]
		);
	}
   public function actionIndex()
    {
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
       
        $searchModel = new SoapRadiologiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('index',[
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	
	public function actionShow($id){
		$model = Pasien::find()->where(['no_rm'=>$id])->one();
		$rawat = Rawat::find()->where(['no_rm'=>$id])->andwhere(['between','status',1,4])->orderBy(['tglmasuk'=>SORT_DESC])->limit(5)->all();
		return $this->renderAjax('show',[
			'model'=>$model,
			'rawat'=>$rawat,
		]);
	}
	public function actionView($id){
		$model = $this->findModel($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$soapradiologi = SoapRadiologi::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->all();
		$hasil = new RadiologiHasil();
		$tambahSoap= new SoapRadiologi();
		$tambahPeriksa = new RadiologiHasildetail();
		$hasilrad = RadiologiHasil::find()->joinWith('rawat as rawat')->where(['radiologi_hasil.idrawat'=>$model->id])->andwhere(['rawat.idjenisrawat'=>$model->idjenisrawat])->all();
		if($hasil->load(Yii::$app->request->post())){
			if(count($soapradiologi) < 1){
				Yii::$app->session->setFlash('Gagal', 'Tidak ada permintaan Radiologi');
				return $this->refresh();
			}else{
				$hasil->genKode();
				$hasil->tgl_hasil=date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$hasil->status = 1;
				$hasil->idrawat = $model->id;
				if($hasil->save(false)){
					foreach($soapradiologi as $sr){
						$radiologi = RadiologiTindakan::find()->where(['id'=>$sr->idtindakan])->one();
						$hasildetail = new RadiologiHasildetail();
						$hasildetail->klinis = $sr->klinis;
						$hasildetail->status = 1;
						$hasildetail->idpengantar = $sr->id;
						$hasildetail->idrawat = $model->id;
						$hasildetail->idhasil = $hasil->id;
						$hasildetail->idrawat = $model->id;
						$hasildetail->idjenisrawat = $model->idjenisrawat;
						$hasildetail->idradiologi = $radiologi->idrad;
						$hasildetail->idpelayanan = $radiologi->idpelayanan;
						$hasildetail->idtindakan = $radiologi->id;
						if($hasildetail->save(false)){
							$sr->status = 2;
							$sr->idhasil = $hasildetail->id;
							$sr->save(false);
						}
					}
					return $this->refresh();
					
				}
				
			}
		}else if($tambahSoap->load(Yii::$app->request->post())){
			$radsoap = SoapRadiologi::find()->where(['idtindakan'=>$tambahSoap->idtindakan])->andwhere(['idrawat'=>$tambahSoap->idrawat])->count();
			if($radsoap > 0){
				Yii::$app->session->setFlash('danger', 'Pemeriksaan Sudah ada');
				return $this->refresh();
			}else{				
				if($tambahSoap->idtindakan == null){
					Yii::$app->session->setFlash('danger', 'Pemeriksaan Kosong');
					return $this->refresh();
				}else{
					$tambahSoap->save();
					return $this->refresh();
				}
			}
		}
		return $this->render('view',[
			'model'=>$model,
			'pasien'=>$pasien,
			'soapradiologi'=>$soapradiologi,
			'hasil'=>$hasil,
			'hasilrad'=>$hasilrad,
			'tambahSoap'=>$tambahSoap,
			'tambahPeriksa'=>$tambahPeriksa,
		]);
	}
	public function actionHasilPrint($id) {

	$model = RadiologiHasildetail::find()->where(['id'=>$id])->one();
	$content = $this->renderPartial('print-hasil',['model' => $model]);
	  
	  // setup kartik\mpdf\Pdf component
	$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'format' => Pdf::FORMAT_A4, 
		'marginTop'=>10,
		'content' => $content,  
		'cssFile' => '@frontend/web/css/paper-radiologi.css',
		//'options' => ['title' => 'Bukti Permohonan Informasi'],

		'methods' => [ 
		]	   
	]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}
	public function actionBaca($id){
		$model = RadiologiHasildetail::find()->where(['id'=>$id])->one();
		$hasil = RadiologiHasil::find()->where(['id'=>$model->idhasil])->one();
		$rawat = Rawat::find()->where(['id'=>$model->idrawat])->one();
		$pengantar = SoapRadiologi::find()->where(['id'=>$model->idpengantar])->andwhere(['status'=>1])->one();
		$template = RadiologiHasildetail::find()->where(['idpelayanan'=>1])->andwhere(['template'=>1])->andFilterWhere(['like', 'klinis', $model->klinis])->all();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$model->rawat->kunjungans->id])->one();
		$rad = RadiologiTindakan::findOne($model->idtindakan);
		$tindakan = Tindakan::findOne($rad->idtindakan);
		$detail = new TransaksiDetailRinci();
	//	$tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$model->rawat->idbayar])->one();
		if ($model->load(Yii::$app->request->post())) {
		    if($model->idbayar == null){
		        $bayar = $rawat->idbayar;
		    }else{
		        $bayar = $model->idbayar;
		    }
			$model->tgl_hasil = date('Y-m-d G:i:s',strtotime('+5 hour',strtotime(date('Y-m-d G:i:s'))));
			
			if($model->status == 1){
				$tanggal = date('Y-m-d G:i:s',strtotime('+5 hour',strtotime(date('Y-m-d G:i:s'))));
			//	Yii::$app->kazo->trantindakan($transaksi->id,$transaksi->idkunjungan,$model->idrawat,$tindakan->id,$tanggal,$tindakan->nama_tindakan,$tarif->tarif,$model->rawat->idjenisrawat,$tarif->id,$model->idbayar,3);
			}
			$model->kat_pasien = $rawat->kat_pasien;
			$model->status = 2;	
			$hasil->status = 2;	
			if($model->save()){
			    if($model->tindakan->idtindakan != null){
					$detail->idtransaksi = $transaksi->id;
					$detail->idtarif = $model->tindakan->idtindakan;
					$detail->tarif = $model->tindakan->tarif->tarif;
					$detail->iddokter = $hasil->iddokter;
					$detail->idrawat = $hasil->idrawat;
					$detail->idbayar = $bayar;
					$detail->idrad = $model->id;
					$detail->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
					$detail->jumlah = 1;
					$detail->save();
				}
				$hasil->save(false);
				if($pengantar){
					$pengantar->idhasil = $model->id;
					$pengantar->tgl_hasil = $model->tgl_hasil;
					$pengantar->status = 2;
					$pengantar->save();
				}
				return $this->refresh();
			}
		}
		// return $tarif->id;
		return $this->render('baca-hasil',[
			'model'=>$model,
			'template'=>$template,
		]);
	}
	
	public function actionGetDataTemplate()
    {
		$kode = Yii::$app->request->post('id');
		if($kode){
			$model = RadiologiHasildetail::find()->where(['id'=>$kode])->one();
		}else{
			$model = "";
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionDeleteFoto($id)
    {
		$model = RadiologiHasilfoto::find()->where(['id'=>$id])->one();
        unlink(Yii::getAlias(Yii::$app->params['imagePath'] .'/radiologi/x-ray/'.$model->foto)); 
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
    }
	public function actionUploadFoto($id){
		$model = RadiologiHasildetail::find()->where(['id'=>$id])->one();
		$rawat = Rawat::find()->where(['id'=>$model->idrawat])->one();
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		$foto = new RadiologiHasilfoto();
		if ($foto->load(Yii::$app->request->post())) {
			$foto->idhasil = $model->id;
			$image=UploadedFile::getInstance($foto,'foto');
            if (!$image == null) {
                $foto->foto= $foto->nofoto.'-'.$image->name;
                $path = Yii::$app->params['imagePath'] .'/radiologi/x-ray/'.$foto->foto;
                $image->saveAs($path);
                //create thumbnail
            }
            else{
                $foto->foto='';
            }
			if($foto->save(false)){
				 return $this->refresh();
			}else{
				return $this->render('foto-hasil',[
					'model'=>$model,
					'pasien'=>$pasien,
					'rawat'=>$rawat,
					'foto'=>$foto,
				]);
			}
           
        }
		return $this->render('foto-hasil',[
			'model'=>$model,
			'pasien'=>$pasien,
			'rawat'=>$rawat,
			'foto'=>$foto,
		]);
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
        if (($model = Rawat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
