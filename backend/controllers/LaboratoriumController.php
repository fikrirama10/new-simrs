<?php

namespace backend\controllers;

use Yii;
use common\models\RawatKunjungan;
use common\models\SoapLab;
use common\models\LaboratoriumHasil;
use common\models\RawatSearch;
use common\models\LaboratoriumHasilSearch;
use common\models\LaboratoriumForm;
use common\models\TransaksiDetailRinci;
use common\models\LaboratoriumPemeriksaan;
use common\models\LabHasil;
use common\models\LaboratoriumHasildetail;
use kartik\mpdf\Pdf;
use common\models\Rawat;
use common\models\Pasien;
use common\models\Dokter;
use common\models\DokterJadwal;
use common\models\DokterSeach;
use common\models\Tindakan;
use common\models\TindakanTarif;
use common\models\Transaksi;
use common\models\TransaksiDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * DokterController implements the CRUD actions for Dokter model.
 */
class LaboratoriumController extends Controller
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
	public function actionKwitansiDua($id) {
		$hasil = LaboratoriumHasil::findOne($id);
		$rawat = Rawat::findOne($hasil->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
		$model = LaboratoriumHasildetail::find()->where(['idhasil'=>$id])->all();
		$content = $this->renderPartial('print-kwitansi2',['model' => $model,'pasien'=>$pasien,'hasil'=>$hasil]);
		// setup kartik\mpdf\Pdf component
		$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'orientation' => Pdf::ORIENT_PORTRAIT, 
		'format' => [76,279],
		'marginTop' => '2',
		'marginRight' => '2',
		'marginLeft' => '2',
		'marginBottom' => '2',
		'content' => $content,  
		'cssFile' => '@frontend/web/css/paper-faktur.css',
		//'options' => ['title' => 'Bukti Permohonan Informasi'],
		]);
		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/pdf');

		// return the pdf output as per the destination setting
		return $pdf->render(); 
	}
    public function actionKwitansi($id) {
	$hasil = LaboratoriumHasil::findOne($id);
	$rawat = Rawat::findOne($hasil->idrawat);
	$pasien = Pasien::find()->where(['no_rm'=>$rawat->no_rm])->one();
	$model = LaboratoriumHasildetail::find()->where(['idhasil'=>$id])->all();
	$content = $this->renderPartial('print-kwitansi',['model' => $model,'pasien'=>$pasien,'hasil'=>$hasil]);
	  
	  // setup kartik\mpdf\Pdf component
	$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'format' => ['148','210'], 
		'marginTop'=>4,
		'marginLeft'=>4,
		'marginRight'=>4,
		'content' => $content,  
		'cssFile' => '@frontend/web/css/paper-faktur.css',
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
	public function actionLaporan(){
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pemeriksaan-lab?start='.date('Y-m-').'01&end='.$tgl;
		$content = file_get_contents($url);
		$json = json_decode($content, true);
        return $this->render('laporan',
			['model'=>$json]
		);
		// return $tgl;
	}
	public function actionShowKunjungan($start='',$end=''){
		$url = Yii::$app->params['baseUrl'].'dashboard/kunjungan-rest/pemeriksaan-lab?start='.$start.'&end='.$end;
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
		$where = ['<>','status',5];
		$searchModel = new RawatSearch();
        $dataProvider = $searchModel->search($this->request->queryParams,$where);
       
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
	public function actionKerjakan($id){
		$model = SoapLab::findOne($id);
		$hasil = LaboratoriumHasil::find()->where(['idrawat'=>$model->idrawat])->one();
		if(!$hasil){
			
			$hasillab = new LaboratoriumHasil();
			$hasillab->genKode();
			$hasillab->tgl_hasil = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$hasillab->tgl_permintaan = $model->tgl_permintaan;
		}
	}
	public function actionInputHasil($iditem='',$id){
		$model = LaboratoriumHasildetail::findOne($id);
		$hasil = LaboratoriumHasil::findOne($model->idhasil);
		$detail = new TransaksiDetailRinci();
		$rawat = Rawat::find()->where(['id'=>$hasil->idrawat])->one();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$hasil->rawat->kunjungans->id])->one();
		$itemHasil = LaboratoriumForm::find()->where(['idpemeriksaan'=>$model->idpemeriksaan])->orderBy(['urutan'=>SORT_ASC])->all();
		$tindakanlab = LaboratoriumPemeriksaan::findOne($model->idpemeriksaan);
		$tindakan = Tindakan::findOne($tindakanlab->idtindakan);
		// $tarif = TindakanTarif::find()->where(['idtindakan'=>$tindakan->id])->andWhere(['idbayar'=>$hasil->rawat->idbayar])->one();
		$labHasil = LabHasil::find()->where(['idlayanan'=>$model->id])->all();
        $aHasil=[];
        foreach ( $itemHasil as $ih) {
			if($rawat->pasien->jenis_kelamin == 'L'){
				$nilai_normal = $ih->nilai_normallaki;
			}else{
				$nilai_normal = $ih->nilai_normalp;
			}
            $nilai = new LabHasil([
                'iditem'=>$ih->id,
                'item'=>$ih->form,
                'satuan'=>$ih->satuan,
                'nilai_rujukan'=> $nilai_normal,
            ]);
            $aHasil[$ih->id] = $nilai;
        }

        if(LabHasil::loadMultiple($aHasil,  Yii::$app->request->post()) && LabHasil::validateMultiple($aHasil)&&$model->load(Yii::$app->request->post())){
            foreach ($aHasil as $nilai) {
				$nilai->idhasil = $model->idhasil;
				$nilai->idlayanan = $model->id;
				$nilai->idpemeriksaan = $model->idpemeriksaan;
				//$nilai->no_ = $model->id;
                 $nilai->save(false);
            }
			// $model->idpemeriksa = Yii::$app->user->identity->id ;
			// $model->status = 1 ;
			// $model->tgl_peniksa = date('Y-m-d G:i:s',strtotime('+5 hour',strtotime(date('Y-m-d G:i:s'))));
			$tanggal = date('Y-m-d G:i:s',strtotime('+5 hour',strtotime(date('Y-m-d G:i:s'))));
			$model->status = 2 ;
			$model->tgl_hasil = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$model->jam_hasil = date('H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
			$model->kat_pasien = $model->hasil->rawat->kat_pasien;
			if($model->save(false)){
			    if($model->pemeriksaan->idtarif != null){
					$detail->idtransaksi = $transaksi->id;
					$detail->idtarif = $model->pemeriksaan->idtarif;
					$detail->tarif = $model->pemeriksaan->tarif->tarif;
					$detail->iddokter = $hasil->iddokter;
					$detail->idrawat = $hasil->idrawat;
					$detail->idbayar = $model->idbayar;
					$detail->idlab = $model->id;
					$detail->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
					$detail->jumlah = 1;
					$detail->save();
				}
				// Yii::$app->kazo->trantindakan($transaksi->id,$transaksi->idkunjungan,$hasil->idrawat,$tindakanlab->idtindakan,$tanggal,$tindakan->nama_tindakan,$tarif->tarif,$hasil->rawat->idjenisrawat,$tarif->id,$hasil->rawat->idbayar,4);
				return $this->refresh();
			}
            
        }
		if($model->status == 2){
			foreach($labHasil as $lh){
				if ($lh->load(Yii::$app->request->post())) {
					$hlab = LabHasil::find()->where(['id'=>$iditem])->andwhere(['idlayanan'=>$id])->one();
					$hlab->hasil = $lh->hasil;
					$hlab->save(false);
					return $this->refresh();
				}
			}
		}
		// return $tarif->id;
		return $this->render('input-hasil',[
			'model'=>$model,
			'itemHasil'=>$itemHasil,
			'aHasil'=>$aHasil,
			'labHasil'=>$labHasil,
		]);
	}
		public function actionHapusSoap($id){
		$model = SoapLab::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusPelayanan($id){
		$model = LaboratoriumHasil::findOne($id);
		
		$detail = LaboratoriumHasilDetail::find()->where(['idhasil'=>$model->id])->all();
		$soap = SoapLab::find()->where(['idhasil'=>$model->id])->all();
		
		foreach($detail as $d){
			
			$detailtrx = TransaksiDetailRinci::find()->where(['idtarif'=>$d->pemeriksaan->idtarif])->andWhere(['idlab'=>$d->id])->one();
			if($detailtrx){
			$detailtrx->delete();
			}
			$lab = LabHasil::find()->where(['idlayanan'=>$d->id])->all();
			if(count($lab) >0){
				foreach($lab as $l){
					$l->delete();
				}
			}
			
			$d->delete();
			
		}
		foreach($soap as $s){
			$s->delete();
		}
		
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
public function actionHapusPemeriksaan($id){
		$model = LaboratoriumHasilDetail::findOne($id);
		$hasil = LaboratoriumHasil::findOne($model->idhasil);
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$hasil->rawat->kunjungans->id])->one();
		$detail = TransaksiDetailRinci::find()->where(['idtransaksi'=>$transaksi->id])->andWhere(['idtarif'=>$model->pemeriksaan->idtarif])->andWhere(['idrawat'=>$hasil->idrawat])->andWhere(['idlab'=>$model->id])->one();
		if($detail){
			$detail->delete();
		}
		$labHasil = LabHasil::find()->where(['idlayanan'=>$model->id])->all();
		if(count($labHasil) >0){
			foreach($labHasil as $lh){
				$lh->delete();
			}
		}
		
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionEditPelayanan($id){
		$model = LaboratoriumHasil::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		if($model->load(Yii::$app->request->post())){
			if($model->save()){
				return $this->redirect(['laboratorium/view/'.$rawat->id]);
			}
		}
		return $this->render('edit',[
			'model'=>$model,
			'rawat'=>$rawat,
		]);
	}
	public function actionView($id){
		$model = $this->findModel($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$soaplab = SoapLab::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->all();
		$hasil = new LaboratoriumHasil();
		$tambahPeriksa = new LaboratoriumHasildetail();
		$tambahSoap = new SoapLab();
		$hasillab = LaboratoriumHasil::find()->joinWith('rawat as rawat')->where(['laboratorium_hasil.idrawat'=>$model->id])->andwhere(['rawat.idjenisrawat'=>$model->idjenisrawat])->all();
		if($hasil->load(Yii::$app->request->post())){
			if(count($soaplab) < 1){
				Yii::$app->session->setFlash('Gagal', 'Tidak ada permintaan Lab');
				return $this->refresh();
			}else{
				$hasil->tgl_hasil=date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$hasil->status = 1;
				$hasil->idrawat = $model->id;
				$hasil->genKode();
				if($hasil->save(false)){
					foreach($soaplab as $sl){						
						$hasildetail = new LaboratoriumHasildetail();
						$hasildetail->idhasil = $hasil->id;
						$hasildetail->idpengantar = $sl->id;						
						$hasildetail->idpemeriksaan = $sl->idpemeriksaan;
						$hasildetail->nama_pemeriksaan = $sl->pemeriksaan->nama_pemeriksaan;
						$hasildetail->status = 0;
						$hasildetail->kat_pasien = $model->kat_pasien;
						if($hasildetail->save(false)){
							$sl->status = 2;
							$sl->idhasil = $hasil->id;
							$sl->save(false);
						}
						
					}
					return $this->refresh();					
				}
				
			}
 		}else if($tambahSoap->load(Yii::$app->request->post())){
			$labsoap = SoapLab::find()->where(['idpemeriksaan'=>$tambahSoap->idpemeriksaan])->andwhere(['idrawat'=>$tambahSoap->idrawat])->andwhere(['status'=>1])->count();
			if($tambahSoap->idpemeriksaan == null){
			    Yii::$app->session->setFlash('danger', 'Pemeriksaan belum dipilih');
				return $this->refresh();
			}
			if($labsoap > 0){
				Yii::$app->session->setFlash('danger', 'Pemeriksaan Sudah ada');
				return $this->refresh();
			}else{				
				$tambahSoap->save();
				return $this->refresh();
			}
		}else if($tambahPeriksa->load(Yii::$app->request->post())){
			$layanan = LaboratoriumPemeriksaan::findOne($tambahPeriksa->idpemeriksaan);
			$tambahPeriksa->kat_pasien = $model->kat_pasien;
			$tambahPeriksa->status =0;
			$soapLab = new SoapLab();
				$soapLab->idrawat = $model->idrawat;
				$soapLab->iddokter = $model->iddokter;
				$soapLab->idpemeriksaan = $tambahPeriksa->idpemeriksaan;
				$soapLab->tgl_permintaan = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));;
				$soapLab->status = 2;
			$tambahPeriksa->nama_pemeriksaan = $layanan->nama_pemeriksaan;
			if($tambahPeriksa->save()){
			
				$soapLab->idhasil = $tambahPeriksa->id;
				if($soapLab->save(false)){
					$tambahPeriksa->idpengantar = $soapLab->id;
					$tambahPeriksa->save();
				}
				return $this->refresh();
			}
			
		}
		return $this->render('view',[
			'model'=>$model,
			'pasien'=>$pasien,
			'soaplab'=>$soaplab,
			'hasil'=>$hasil,
			'hasillab'=>$hasillab,
			'tambahSoap'=>$tambahSoap,
			'tambahPeriksa'=>$tambahPeriksa,
		]);
	}
	public function actionHasilPrint($id) {

	$model = LaboratoriumHasil::find()->where(['id'=>$id])->one();
	$content = $this->renderPartial('print-hasil',['model' => $model]);
	  
	  // setup kartik\mpdf\Pdf component
	$pdf = new Pdf([
		'mode' => Pdf::MODE_CORE,
		'destination' => Pdf::DEST_BROWSER,
		'format' => Pdf::FORMAT_A4, 
		'marginTop'=>6,
		'marginBottom'=>2,
		'content' => $content,  
		'cssFile' => '@frontend/web/css/paper-lab.css',
		//'options' => ['title' => 'Bukti Permohonan Informasi'],

		'methods' => [ 
		//'SetFooter' => ['|{PAGENO}|'],
		]	   
	]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
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
