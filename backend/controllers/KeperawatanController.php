<?php
namespace backend\controllers;

use Yii;
use common\models\Operasi;
use common\models\Hari;
use common\models\Rawat;
use common\models\RawatSearch;
use common\models\Tarif;
use common\models\TransaksiDetailRinci;
use common\models\Pasien;
use common\models\RawatCppt;
use common\models\RawatRuangan;
use common\models\RawatKunjungan;
use common\models\RawatRingkasanpulang;
use common\models\RawatResepDetail;
use common\models\ObatBacth;
use common\models\SoapRadiologi;
use common\models\SoapLab;
use common\models\Tindakan;
use common\models\SoapRajalicdx;
use common\models\RawatTindakan;
use common\models\RawatAwalinap;
use common\models\RawatImplementasi;
use common\models\RawatPermintaanPindah;
use common\models\DokterJadwal;
use common\models\Transaksi;
use common\models\TransaksiDetail;
use common\models\TindakanTarif;
use common\models\DokterSeach;
use common\models\RawatResep;
use common\models\TindakanFisio;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use kartik\mpdf\Pdf;

class KeperawatanController extends Controller
{

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
	public function actionShowBatch($id){
		$obat = ObatBacth::find()->where(['idobat'=>$id])->andwhere(['>','stok_apotek',0])->all();
		$resep_obat = new RawatResepDetail();
		return $this->renderAjax('show-batch',[
			'obat'=>$obat,
			'resep_obat'=>$resep_obat,
		]);
	}
	public function actionRingkasanPulang($id) {
	  //tampilkan bukti proses
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/logout']);
		}
	  $model = RawatRingkasanpulang::find()->where(['idrawat' =>$id ])->one();
	  $content = $this->renderPartial('form-pulang',['model' => $model]);
	  
	  // setup kartik\mpdf\Pdf component
	  $pdf = new Pdf([
	   'mode' => Pdf::MODE_CORE,
	   'destination' => Pdf::DEST_BROWSER,
	   'format' => Pdf::FORMAT_A4, 
	   'content' => $content,  
	   
	   'cssFile' => '@frontend/web/css/paper-pulang.css',
	   //'options' => ['title' => 'Bukti Permohonan Informasi'],

		'methods' => [ 
            'SetFooter'=>['DRM. 15 - RI'],
        ]	   ]);
		 $response = Yii::$app->response;
			$response->format = \yii\web\Response::FORMAT_RAW;
			$headers = Yii::$app->response->headers;
			$headers->add('Content-Type', 'application/pdf');
	  
	  // return the pdf output as per the destination setting
	  return $pdf->render(); 
	}
    public function actionIndex()
    {
		$url = Yii::$app->params['baseUrl']."dashboard/rest/bed";
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
        $searchModel = new RawatSearch();
		$where = ['status'=>2];
		$andWhere = ['idjenisrawat'=>2];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere);
        return $this->render('index',[
			'bed'=>$json,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionShowImp($idrawat,$tgl=''){
		if($tgl == ''){
			$implementasi = RawatImplementasi::find()->where(['idrawat'=>$idrawat])->all();
		}else{
			$implementasi = RawatImplementasi::find()->where(['idrawat'=>$idrawat])->andwhere(['tgl'=>$tgl])->all();
		}
		return $this->renderAjax('show-imp',[
			'implementasi'=>$implementasi,
		]);
	}
	public function actionShowPpa($ppa='',$idrawat,$tgl=''){
		if($tgl == ''){
			if($ppa == ''){
				$cppta = RawatCppt::find()->where(['idrawat'=>$idrawat])->all();
			}else{
				$cppta = RawatCppt::find()->where(['idrawat'=>$idrawat])->andwhere(['profesi'=>$ppa])->all();
			}
			
		}else if($ppa == ''){
			if($tgl == ''){
				$cppta = RawatCppt::find()->where(['idrawat'=>$idrawat])->all();
			}else{
				$cppta = RawatCppt::find()->where(['idrawat'=>$idrawat])->andwhere(['tgl'=>$tgl])->all();
			}
			
		}else{
			$cppta = RawatCppt::find()->where(['idrawat'=>$idrawat])->andwhere(['tgl'=>$tgl])->andwhere(['profesi'=>$ppa])->all();
		}
		return $this->renderAjax('show-ppa',[
			'cppta'=>$cppta,
		]);
	}
	public function actionShow($id){
		$model = Rawat::find()->where(['no_rm'=>$id])->andwhere(['idjenisrawat'=>2])->andwhere(['status'=>2])->all();
		
		return $this->renderAjax('show',[
			'model'=>$model,
		]);
	}
	public function actionEditAwal($id){
		$awalinap = RawatAwalinap::find()->where(['id'=>$id])->one();
		if($awalinap->load($this->request->post())){
			if($awalinap->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
	public function actionHapusCppt($id){
	    $cppt = RawatCppt::find()->where(['id'=>$id])->one();
	    $cppt->delete();
	    return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionHapusImp($id){
	    $cppt = RawatImplementasi::find()->where(['id'=>$id])->one();
	    $cppt->delete();
	    return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
	}
	public function actionEditCppt($id){
		$cl = RawatCppt::find()->where(['id'=>$id])->one();
		if($cl->load($this->request->post())){
		    $cl->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			if($cl->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
	public function actionEditImp($id){
		$il = RawatImplementasi::find()->where(['id'=>$id])->one();
		if($il->load($this->request->post())){
		    $il->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			if($il->save(false)){
				return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
			}
		}
	}
	public function actionPulang($id){
		$model = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$pulang = new RawatRingkasanpulang();
		return $this->render('pulang',[
			'model'=>$model,
			'pasien'=>$pasien,
			'pulang'=>$pulang,
		]);
	}
	public function actionRincian($id){
		// $model = RawatKunjungan::findOne($id);
		// $transaksi = Transaksi::find()->where(['idkunjungan'=>$model->id])->one();
		// $trxdetail = TransaksiDetail::find()->where(['idtransaksi'=>$transaksi->id])->groupBy(['DATE_FORMAT(tgl,"%Y-%m-%d")'])->all();
		// return $this->render('rincian',['model'=>$model,'trxdetail'=>$trxdetail,'transaksi'=>$transaksi]);
		$model = Rawat::findOne($id);
		$transaksi = TransaksiDetailRinci::find()->where(['idrawat'=>$model->id])->groupBy(['tgl'])->all();
		return $this->render('rincian',[
			'model'=>$model,
			'transaksi'=>$transaksi,
		]);
	}
	public function actionHapusTindakan($id){
		$soap = RawatTindakan::findOne($id);
		$trx = TransaksiDetailRinci::find()->where(['idrawat'=>$soap->idrawat])->andWhere(['idtarif'=>$soap->idtindakan])->one();
		$soap->delete();
		$trx->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusPenunjang($id){
		$trx = TransaksiDetailRinci::find()->where(['id'=>$id])->one();
		$trx->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionDeleteRadiologi($id){
	    $model = SoapRadiologi::findOne($id);
	    $model->delete();
	    return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionOperasi($id){
		$model = Rawat::findOne($id);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$operasi = new Operasi();
		if($operasi->load($this->request->post())){
			$model->ok = 1;
			$operasi->genKode();
			$operasi->idasal = $model->idruangan;
			$operasi->no_rm = $model->no_rm;
			$operasi->idrawat = $model->id;
			$operasi->status = 1;
			if($operasi->save(false)){
				$model->save(false);
				Yii::$app->session->setFlash('success', 'Pasien Sudah masuk antrian OK');
				return $this->redirect(['keperawatan/'.$model->id]);
			}
		}
		return $this->render('operasi',[
			'model'=>$model,
			'pasien'=>$pasien,
			'operasi'=>$operasi,
		]);
	}
	public function actionView($id){
		$tgl = date('Y-m-d');
		$model = Rawat::findOne($id);
		$fisio = new TindakanFisio();
		$list_fisio = TindakanFisio::find()->where(['idrawat'=>$model->id])->all();
		$resep = new RawatResep();
		$awalinap = RawatAwalinap::find()->where(['idrawat'=>$model->id])->one();
		$cppt = new RawatCppt();
		$implementasi = new RawatImplementasi();
		$pindah = new RawatPermintaanPindah();
		$awal = new RawatAwalinap();
		$icdx = new SoapRajalicdx();
		$tindakan = new RawatTindakan();
		$tindakanp = new RawatTindakan();
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$rawat_list = Rawat::find()->where(['no_rm'=>$pasien->no_rm])->orderBy(['tglmasuk'=>SORT_DESC])->limit(6)->all();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$model->kunjungans->id])->one();
		$pulang = new RawatRingkasanpulang();
		$pindah_list = RawatRuangan::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>2])->all();
		$soapradiologilist = SoapRadiologi::find()->where(['idrawat'=>$model->id])->all();
		$soapradiologi = new SoapRadiologi();
		$soaplablist = SoapLab::find()->where(['idrawat'=>$model->id])->all();;
		$soaplab = new SoapLab();
		$tindakanDokter = RawatTindakan::find()->where(['idrawat'=>$model->id])->orderBy(['tgl'=>SORT_DESC,'jam'=>SORT_DESC])->all();
		$tarif_rinci = new TransaksiDetailRinci();
		$tarif_rinci_list = TransaksiDetailRinci::find()->where(['idrawat'=>$model->id])->andwhere(['idjenis'=>1])->all();
		$list_resep = RawatResep::find()->where(['idrawat'=>$model->id])->all();
		if ($cppt->load($this->request->post())) {
			$cppt->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			$cppt->jam = date('H:i:s',strtotime('+7 hour',strtotime(date('H:i:s'))));
			$cppt->idruangan = $model->idruangan;
			if($cppt->profesi == 'Dokter'){
				$cppt->iddokter = $model->iddokter;
			}
			if($cppt->save(false)){
				return $this->refresh();
			}
		}else if($implementasi->load($this->request->post())){
			$implementasi->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			if($implementasi->save(false)){
				return $this->refresh();
			}
		}else if($pindah->load($this->request->post())){
			$pindah->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			$pindah->status = 1;
			$pindah->idkunjungan = $model->idkunjungan;
			if($pindah->save(false)){
				return $this->refresh();
			}
		}else if($awal->load($this->request->post())){
			$awal->tgl = date('Y-m-d',strtotime('+7 hour',strtotime(date('Y-m-d'))));
			$awal->idruangan = $model->idruangan;
			if($awal->save(false)){
				return $this->refresh();
			}
		}else if($tindakan->load($this->request->post())){
			if($tindakan->idtindakan == null){
				Yii::$app->session->setFlash('danger', 'Tindakan tidak boleh kosong');
				return $this->refresh();
			}
			$bayar = Yii::$app->request->post('bayar');	
		
			$tarif_trx = new TransaksiDetailRinci();
			$tarif = Tarif::find()->where(['id'=>$tindakan->idtindakan])->one();
			$tindakan->tgl = date('Y-m-d H:i:s',strtotime('+7 hour',strtotime(date('Y-m-d H:i:s'))));
			$tindakan->jam = date('H:i:s',strtotime('+7 hour',strtotime(date('H:i:s'))));
			$tarif_trx->idpaket = 0;
		
			$tarif_trx->idtransaksi = $transaksi->id;
			if($tindakan->idbayar < 1){
				$tindakan->idbayar = $model->idbayar;
			}
			$tarif_trx->idbayar = $tindakan->idbayar;
			$tarif_trx->idrawat = $model->id;
			$tarif_trx->tarif = $tarif->tarif;
			$tarif_trx->idtarif = $tarif->id;
			$tarif_trx->iddokter = $tindakan->iddokter;
			$tarif_trx->tgl = date('Y-m-d',strtotime('+7 hour',strtotime(date('Y-m-d G:i:s'))));
			$tarif_trx->save(false);
			// $trxdetail->idtransaksi = $transaksi->id;
			// $trxdetail->idrawat = $model->id;					
			// $trxdetail->idpelayanan = $tindakan->idtindakan;
			// $trxdetail->tgl =date('Y-m-d G:i:s',strtotime('+6 hour',strtotime(date('Y-m-d G:i:s'))));
			// $trxdetail->nama_tindakan= $tarif->tindakans->nama_tindakan;
			// $trxdetail->tarif = $tarif->tarif;
			// $trxdetail->idkunjungan = $model->kunjungans->id;
			// $trxdetail->jumlah = 1;
			// $trxdetail->status = 1;
			// $trxdetail->total = $tarif->tarif * $trxdetail->jumlah;
			// $trxdetail->jenis = $model->idjenisrawat;
			// $trxdetail->idtindakan = $tarif->id;
			// $trxdetail->idbayar = $model->idbayar;
			// $trxdetail->idjenispelayanan = 7;
			
			// $trxdetail->save(false);
			if($tindakan->save(false)){
				// $trxdetail->idraw = $tindakan->id;
				// $trxdetail->save(false);
				return $this->refresh();
			}
		}else if($soapradiologi->load(Yii::$app->request->post())){
		    if($soapradiologi->idtindakan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan radiologi"); 
				return $this->refresh();
		    }
			$soapradiologi->no_rm = $model->no_rm;
			if($soapradiologi->save(false)){
				return $this->refresh();
			}
			
		}else if($soaplab->load(Yii::$app->request->post())){
		    if($soaplab->idpemeriksaan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan Lab"); 
				return $this->refresh();
		    }
		    $soaplab->save();
			return $this->refresh();
		}else if($fisio->load(Yii::$app->request->post())){
		    if($fisio->idtindakan == null){
		        Yii::$app->session->setFlash('danger', "Silahkan Isi tindakan Lab"); 
				return $this->refresh();
		    }
		    $fisio->save();
			return $this->refresh();
		}else if($icdx->load(Yii::$app->request->post())){
			$icdxx = SoapRajalicdx::find()->where(['idrawat'=>$model->id])->andwhere(['idjenisdiagnosa'=>1])->count();
			if($icdxx > 0){
				Yii::$app->session->setFlash('danger', "Diagnosa Primer sudah ada ."); 
				return $this->refresh();
			}else{
				$icdx->kat_pasien = $model->kat_pasien;
				$icdx->save();
				return $this->refresh(false);
			}
			
		}else if($pulang->load(Yii::$app->request->post())){
			$pulang->idrawat = $model->id;
			$pulang->no_rm = $pasien->no_rm;
			$pulang->tgl_pulang = $pulang->tgl_pulang.' '.$pulang->jam_pulang;
			$transaksi = Transaksi::find()->where(['idkunjungan'=>$model->kunjungans->id])->one();
			if($pulang->save()){
				$model->status = Yii::$app->kazo->updatestatusRanap($pulang->kondisi_waktupulang);
				$ruangan_pulang_hitung = RawatRuangan::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->count();
				if($ruangan_pulang_hitung > 0){
					$ruangan_pulang = RawatRuangan::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->one();
					$ruangan_pulang->tgl_keluar = $pulang->tgl_pulang;
					$ruangan_pulang->los = Yii::$app->kazo->hitungLos($ruangan_pulang->tgl_masuk,$pulang->tgl_pulang);
					
					
					$ruangan_pulang->status = 2;
					if($ruangan_pulang->save(false)){
						
						Yii::$app->kazo->updateBed($ruangan_pulang->idbed);
						$total_los = 0;
						$ruangan_pulang_all = RawatRuangan::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>2])->all();
						foreach($ruangan_pulang_all as $rpa){
							$total_los += $rpa->los;
						}
						$model->los = $total_los;
						$model->tglpulang = $pulang->tgl_pulang;
						if($model->save(false)){
							$kunjungan = RawatKunjungan::find()->where(['idkunjungan'=>$model->idkunjungan])->one();
							$kunjungan->status = $model->status;
							$kunjungan->save();
						}
					}
				}else{
					Yii::$app->kazo->updateBed($model->idbed);
						$total_los = 0;
						$ruangan_pulang_all = RawatRuangan::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>2])->all();
						foreach($ruangan_pulang_all as $rpa){
							$total_los += $rpa->los;
						}
						$model->los = $total_los;
						$model->tglpulang = $pulang->tgl_pulang;
						if($model->save(false)){
							$kunjungan = RawatKunjungan::find()->where(['idkunjungan'=>$model->idkunjungan])->one();
							$kunjungan->status = $model->status;
							$kunjungan->save();
						}
				}

				
				
				return $this->refresh();
			}
			
		}else if($resep->load(Yii::$app->request->post())){
			$resep->genKode();
			$resep->status = 1;
			$resep->jam_resep = date('G:i:s',strtotime('+6 hour',strtotime(date('G:i:s'))));
			if($resep->save(false)){
				return $this->redirect(['tambah-obat?id='.$resep->id]);
			}
		}else if($tarif_rinci->load(Yii::$app->request->post())){
				$bayar = Yii::$app->request->post('bayar');	
				$dokter = Yii::$app->request->post('dokter');	
				$tarif = Tarif::find()->where(['id'=>$tarif_rinci->idtarif])->one();	
				$tarif_rinci->iddokter = $model->iddokter;
				$tarif_rinci->idbayar = $model->idbayar; 
				$tarif_rinci->idjenis = 1;
				$tarif_rinci->tarif = $tarif->tarif;
				$tarif_rinci->tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$tarif_rinci->idpaket = 0;
				$tarif_rinci->idrawat = $model->id;
				$tarif_rinci->idtransaksi = $transaksi->id;
				$tarif_rinci->save();
				return $this->refresh();
		}
		$operasi = Operasi::find()->where(['idrawat'=>$model->id])->all();
		return $this->render('view',[
			'model'=>$model,
			'operasi'=>$operasi,
			'pasien'=>$pasien,			
			'tarif_rinci_list'=>$tarif_rinci_list,			
			'pulang'=>$pulang,			
			'resep'=>$resep,			
			'cppt'=>$cppt,
			'pindah'=>$pindah,
			'implementasi'=>$implementasi,
			'tarif_rinci'=>$tarif_rinci,
			'awal'=>$awal,
			'tindakan'=>$tindakan,
			'tindakanDokter'=>$tindakanDokter,
			'soaplab'=>$soaplab,
			'soapradiologi'=>$soapradiologi,
			'soapradiologilist'=>$soapradiologilist,
			'soaplablist'=>$soaplablist,
			'rawat_list'=>$rawat_list,
			'icdx'=>$icdx,
			'pindah_list'=>$pindah_list,
			'list_resep'=>$list_resep,
			'list_fisio'=>$list_fisio,
			'fisio'=>$fisio,
		]);
	}
	public function actionListPulang()
    {
		$url = Yii::$app->params['baseUrl']."dashboard/rest/bed";
		$content = Yii::$app->kazo->fetchApiData($url);
		$json = json_decode($content, true);
		
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/index']);
		}
        $searchModel = new RawatSearch();
		$where = ['status'=>4];
		$andWhere = ['idjenisrawat'=>2];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where,$andWhere);
        return $this->render('index2',[
			'bed'=>$json,
			'searchModel'=>$searchModel,
			'dataProvider'=>$dataProvider,
		]);
    }
	public function actionHapusObat($id){
		$model = RawatResepDetail::findOne($id);
		$model->delete();
		return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionHapusResep($id){
		$model = RawatResep::findOne($id);
		$list_resep = RawatResepDetail::find()->where(['idresep'=>$model->id])->all();
	
			foreach($list_resep as $lr):
				$lr->delete();
			endforeach;
			$model->delete();
			return $this->redirect(Yii::$app->request->referrer);
	}
	public function actionTambahObat($id){
		$model = RawatResep::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
		$obat_list = RawatResepDetail::find()->where(['idresep'=>$model->id])->all();
		$resep_obat = new RawatResepDetail();
		if($resep_obat->load(Yii::$app->request->post())){
			$resep_obat->idresep = $model->id;
			$resep_obat->status = 1;
			if($resep_obat->save(false)){
				return $this->refresh();
			}
		}
		return $this->render('tambah-obat',[
			'model'=>$model,
			'pasien'=>$pasien,
			'rawat'=>$rawat,
			'resep_obat'=>$resep_obat,
			'obat_list'=>$obat_list,
		]);
	}
	public function actionShowRuangan($id){
		$ruangan = RuanganBed::find()->where(['idruangan'=>$id])->andwhere(['terisi'=>0])->all();
		$cruangan = RuanganBed::find()->where(['idruangan'=>$id])->andwhere(['terisi'=>0])->count();;
		return $this->renderAjax('show-ruangan',[
			'ruangan'=>$ruangan,
			'cruangan'=>$cruangan,
		]);
	}

    
}
