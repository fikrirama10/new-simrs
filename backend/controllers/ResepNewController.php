<?php

namespace backend\controllers;

use Yii;
use common\models\Rawat;
use common\models\RawatKunjungan;
use common\models\ObatTransaksi;
use common\models\ObatRacikDetail;
use common\models\ObatRacik;
use common\models\ObatTransaksiDetail;
use common\models\ObatTransaksiRetur;
use common\models\Transaksi;
use common\models\ObatTransaksiSearch;
use common\models\Pasien;
use common\models\Obat;
use common\models\RawatResep;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TemaController implements the CRUD actions for SettingSimrsTema model.
 */
class ResepNewController extends Controller
{
	public function actionIndexNamaObat(){
		$model = ObatTransaksiDetail::find()->where(['nama_obat'=>NULL])->andwhere(['satuan_obat'=>NULL])->all();
		foreach($model as $m){
			$obat = Obat::findOne($m->idobat);
			if($obat){
				if($m->nama_obat == null){
					$m->nama_obat = $obat->nama_obat;
				}
				if($m->satuan_obat == null){
					$m->satuan_obat = $obat->satuan->satuan;
				}
				// 
				$m->save();
			}
		}
		return 'Berhasil';
	}
	public function actionIndex(){
		$searchModel = new ObatTransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index',[
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
		]);
	}
	public function actionShowData($awal,$akhir){
		return $this->renderAjax('show-data',[
			'awal'=>$awal,
			'akhir'=>$akhir,
		]);
	}
	public function actionHistoriResep($id){
		$model = Pasien::find()->where(['no_rm'=>$id])->one();
		return $this->render('histori-resep',[
			'model'=>$model,
		]);
	}
	public function actionShowPasien($id){
		$rawat = Rawat::find()->where(['no_rm'=>$id])->andwhere(['<>','status',5])->orderBy(['tglmasuk'=>SORT_DESC])->limit(5)->all();
		return $this->renderAjax('show-pasien',[
			'rawat'=>$rawat,
		]);
	}
	public function actionRacikObat($id,$idrawat){
		$racik = new ObatRacik();
		$rawat = Rawat::findOne($idrawat);
		$racik->genKode();
		$racik->tgl = date('Y-m-d');
		$racik->status = 1;
		$racik->idrawat = $idrawat;
		$racik->idbayar = $rawat->idbayar;
		$racik->idresep = $id;
		if($racik->save(false)){
			$racikan = ObatRacikDetail::find()->where(['idresep'=>$id])->andwhere(['status'=>1])->all();
			foreach($racikan as $r){
				$r->idracik = $racik->id;
				$r->status = 2;
				$r->save();
			}
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);		
		}
		
		
	}
	public function actionHapusObat($id){
		$racik = ObatRacikDetail::findOne($id);
		$racik->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);	
	}
	public function actionHapusRacik($id){
		$racik = ObatRacik::findOne($id);
		$racikan = ObatRacikDetail::find()->where(['idracik'=>$racik->id])->all();
		foreach($racikan as $rn){
			$rn->delete();
		}
		$racik->delete();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);	
	}
	public function actionRacik($id,$idresep){
		$model = ObatTransaksi::findOne($idresep);
		$obat = ObatTransaksiDetail::findOne($id);
		$racikan = new ObatRacikDetail();
		$racikan->idobat = $obat->idobat;
		$racikan->nama_obat = $obat->nama_obat;
		$racikan->idresep = $idresep;
		$racikan->jumlah = $obat->qty;
		$racikan->status = 1;
		$racikan->save();
		return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);		
	}
	public function actionRetur($id){
		$resep = ObatTransaksi::findOne($id);
		$trx = ObatTransaksi::findOne($id);
		$racik = ObatRacik::find()->where(['idresep'=>$id])->all();;
		$model = Rawat::findOne($resep->idrawat);
		$retur = new ObatTransaksiRetur();
		$tgl = date('Y-m-d H:i:s',strtotime('+5 hour'));
		if($retur->load(Yii::$app->request->post())){
			$obatdetail = ObatTransaksiDetail::findOne($retur->iddetail);			
			if($retur->jumlah > $obatdetail->qty){
				Yii::$app->session->setFlash('warning', 'Jumlah Retur Lebih dari jumlah barang');
				return $this->refresh(); 
			}else if($retur->jumlah < 1){
				Yii::$app->session->setFlash('warning', 'Jumlah Retur Kurang dari 1');
				return $this->refresh(); 
			}
			$obat = Obat::findOne($obatdetail->idobat);
			$obatdetail->qty = $obatdetail->qty - $retur->jumlah;
			if($obatdetail->idbayar == 1){
				if($obatdetail->qty == 0){
					$obatdetail->total = 0;
					$obatdetail->tuslah = 0;
					$obatdetail->keuntungan = 0;
				}else{
					$obatdetail->total = $obatdetail->harga * $obatdetail->qty + 3000;
					$obatdetail->tuslah = 3000;
					$obatdetail->keuntungan = round(($obatdetail->harga - $obat->harga_beli) * $obatdetail->qty) ;
				}				
			}else{
				$obatdetail->total = round($obatdetail->harga * $obatdetail->qty);
			}
			
			if($obatdetail->save()){				
				Yii::$app->kazo->kartuStok($obatdetail->idobat,$obatdetail->idbatch,2,$retur->jumlah,2);	
				Yii::$app->kazo->mutasiStok($obatdetail->idobat,$obatdetail->idbatch,2,4,$retur->jumlah,$trx->idtrx,$obat->stok_apotek,2);
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$obatdetail->idobat,$retur->jumlah,$tgl,2);
				$obat->stok_apotek = $obat->stok_apotek + $retur->jumlah;
				if($obatdetail->qty < 1){
					$obatdetail->delete();
				}
				$total = 0;
				$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$obatdetail->idtrx])->all();
				foreach($detail as $d){
					$total += round($d->total);
				}
				$trx->total_harga = $total;
				$trx->total_bayar = $total;
				if($trx->total_harga < 1 ){
					$trx->jasa_racik = 0;
					$trx->jumlahracik = 0;
					$trx->save();
				}
				$trx->save();
				$retur->save();
				$obat->save();
				
				return $this->refresh();
			}
		}
		return $this->render('retur',[
			'model'=>$model,
			'resep'=>$resep,
			'retur'=>$retur,
			'racik'=>$racik,
		]);
	}
	public function actionReturRacik($id,$idresep){
		$model = ObatRacik::findOne($id);
		$resep = ObatTransaksi::findOne($idresep);
		$model->delete();		
		$racikan = ObatRacik::find()->where(['idresep'=>$resep->id])->andwhere(['status'=>2])->all();
		$resep->jasa_racik = count($racikan) * 10000;
		$resep->jumlahracik	= count($racikan);
		if($resep->save()){
			$total = 0;
			$totalu = 0;
			$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$resep->id])->all();
			$detail_umum = ObatTransaksiDetail::find()->where(['idtrx'=>$resep->id])->andwhere(['idbayar'=>1])->all();
			foreach($detail as $d){
				$total += round($d->total);
			}
			if(count($detail_umum) > 0){
				foreach($detail_umum as $du){
					$totalu += round($du->total);
				}
			}
			$resep->total_harga = $total;
			$resep->total_bayar = $totalu;
			$resep->save();
			return $this->redirect(['resep-new/retur?id='.$idresep]);
		}
	}
	public function actionView($id){
		$model = Rawat::findOne($id);
		$kunjungan = RawatKunjungan::find()->where(['idkunjungan'=>$model->idkunjungan])->one();
		$transaksi = Transaksi::find()->where(['idkunjungan'=>$kunjungan->id])->one();
		// return print_r($transaksi);
		$resep = new ObatTransaksi();
		$dataresep = ObatTransaksi::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->all();
		$data_resep = ObatTransaksi::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>2])->all();
		$resep_detail = new ObatTransaksiDetail();
		$retur = new ObatTransaksiRetur();
		$resep_trx = ObatTransaksi::find()->where(['idrawat'=>$model->id])->all();
		if($resep->load(Yii::$app->request->post())){
			$hitung_resep = ObatTransaksi::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->count();
			if($hitung_resep > 0){
				Yii::$app->session->setFlash('warning', 'Silahkan selesaikan resep ');
				return $this->refresh(); 
			}
			$resep->genKode();
			$resep->jam = date('H:i:s',strtotime('+5 hour',strtotime(date('H:i:s'))));
			$resep->idjenis = 1;
			$resep->status = 1;
			
			if($resep->save(false)){
				return $this->refresh();
			}
			
		}else if($resep_detail->load(Yii::$app->request->post())){
			$obat_transaksi = ObatTransaksi::find()->where(['idrawat'=>$model->id])->andwhere(['status'=>1])->one();
			$resep_detail->idtrx = $obat_transaksi->id;
			$resep_detail->idtransaksi = $transaksi->id;
			$obat = Obat::findOne($resep_detail->idobat);
			if($resep_detail->idbayar == 1){
				
				$resep_detail->harga = $obat->harga_jual;
				$resep_detail->total = $obat->harga_jual * $resep_detail->qty + 3000;
				$resep_detail->tuslah = 3000;
				$resep_detail->keuntungan = ($obat->harga_jual - $obat->harga_beli) * $resep_detail->qty ;
			}else{
				
				$resep_detail->total = $obat->harga_beli * $resep_detail->qty;
				$resep_detail->harga = $obat->harga_beli;
			}
			
			$resep_detail->idsatuan = $obat->idsatuan;
			$resep_detail->nama_obat = $obat->nama_obat;
			$resep_detail->satuan_obat = $obat->satuan->satuan;
			if($resep_detail->save(false)){
				return $this->refresh();
			}
		}else if($retur->load(Yii::$app->request->post())){
		
		}
		return $this->render('view',[
			'model'=>$model,
			'resep'=>$resep,
			'retur'=>$retur,
			'transaksi'=>$transaksi,
			'kunjungan'=>$kunjungan,
			'dataresep'=>$dataresep,
			'list_resep'=>$data_resep,
			'resep_detail'=>$resep_detail,
			'resep_trx'=>$resep_trx,
		]);
	}
	public function actionGetStok()
    {
		$kode = Yii::$app->request->post('id');	
		if($kode){
			$obat = Obat::findOne($kode);
			if($obat->stok_apotek < 1){
				$model = 201;
			}else{
				$model = $obat;
			}
		}else{
			$model = 201;
		}
		return \yii\helpers\Json::encode($model);
    }
	public function actionSelesai($id){
		$model = ObatTransaksi::findOne($id);
		$rawat = Rawat::findOne($model->idrawat);
		$resep = RawatResep::findOne($model->idresep);
		$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->all();
		$detail_umum = ObatTransaksiDetail::find()->where(['idtrx'=>$model->id])->andwhere(['idbayar'=>1])->all();
		$racik = ObatRacik::find()->where(['idresep'=>$id])->andwhere(['status'=>1])->all();
		$total = 0;
		$totalu = 0;
		$tgl = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
		
		foreach($detail as $d){
			$obat = Obat::findOne($d->idobat);
			Yii::$app->kazo->kartuStok($d->idobat,$d->idbatch,2,$d->qty,1);	
			Yii::$app->kazo->mutasiStok($d->idobat,$d->idbatch,1,1,$d->qty,$d->id,$obat->stok_apotek,2);
			$obat->stok_apotek = $obat->stok_apotek - $d->qty;
			
			Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$d->idobat,$d->qty,$tgl,1);
			$obat->save(false);
			$total += round($d->total);
		}
		if(count($detail_umum) > 0){
			foreach($detail_umum as $du){
				$obat = Obat::findOne($du->idobat);
				Yii::$app->kazo->kartuStok($du->idobat,$du->idbatch,2,$du->qty,1);	
				Yii::$app->kazo->mutasiStok($du->idobat,$du->idbatch,1,1,$du->qty,$du->id,$obat->stok_apotek,2);
				$obat->stok_apotek = $obat->stok_apotek - $du->qty;
				
				Yii::$app->kazo->gudangStok(Yii::$app->user->identity->userdetail->idgudang,$du->idobat,$du->qty,$tgl,1);
				$obat->save(false);
				$totalu += round($du->total);
			}
		}
		$model->total_harga = $total;
		$model->total_bayar = $totalu;
		$model->iduser = Yii::$app->user->identity->id;
		$model->status = 2;
		$model->jasa_racik = count($racik) * 10000;
		$model->jumlahracik	= count($racik);
		if($model->save(false)){
			foreach($racik as $rc){
				$rc->status =2;
				$rc->save();
			}
			if($rawat->taksid == 6){
				$taks = array(
					"kodebooking"=> $rawat->idrawat,
					"taskid"=> 7,
					"waktu"=>  $this->milliseconds(),
				);
				$taksid = Yii::$app->hfis->update_taks($taks);
				if($taksid['metadata']['code'] == 200){
					$rawat->taksid = 7;
					$rawat->save(false);
					
				}else{
					Yii::$app->session->setFlash('success', $taksid['metadata']['message']); 
					 return $this->redirect(Yii::$app->request->referrer);
				}
				Yii::$app->session->setFlash('success', "Waktu pelayanan poli Taks Id 7"); 
			}
			if($resep){
				$resep->status = 2;
				$resep->save(false);
			}	
			return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
		}
	}
}