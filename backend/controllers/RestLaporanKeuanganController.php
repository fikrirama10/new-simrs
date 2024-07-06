<?php
namespace backend\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use common\models\BarangAmprah;
use common\models\PermintaanObat;
use common\models\BarangPenerimaan;
use common\models\BarangPenerimaanDetail;
use common\models\PenerimaanBarang;
use common\models\PenerimaanBarangDetail;
use common\models\ObatBacth;
use common\models\PermintaanObatRequest;
use common\models\DataBarang;
use common\models\BarangAmprahDetail;
use common\models\Transaksi;
use common\models\Rawat;
use common\models\Tarif;
use common\models\TransaksiDetailRinci;
use common\models\TransaksiDetail;
use common\models\TindakanKategori;
use common\models\TransaksiDetailBill;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;
use common\models\UserUnit;
use common\models\UnitRuangan;
use common\models\TarifRincian;
use common\models\Ruangan;
use common\models\Dokter;
use common\models\Poli;
use common\models\OperasiTindakan;

class RestLaporanKeuanganController extends \yii\rest\Controller
{
	//rincian Rajal
	public function actionRincianRajal($awal,$akhir,$idbayar){
		$poli = Poli::find()->where(['<>','id',1])->all();
		$jumlah = 0;
		$arrdip = array();
		foreach($poli as $r){
			$tarif = TarifRincian::find()->all();
			$arrtar = array();
			$total = 0;
			foreach($tarif as $t){
				$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['tarif.idpoli'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->andwhere(['tarif.idkategori'=>1])->sum('tarif.'.$t->nama_rincian);
				$total += $model;
				if($model > 1){
					array_push($arrtar,[
						'tindakan' => $t->keterangan,
						'jumlah' => $model,
					]);
				}
			}
			$jumlah += $total;
			array_push($arrdip,[
				'id'=>$r->id,
				'poli'=>$r->poli,
				'total'=>$total,
				'rincian'=>$arrtar,
			]);
		}
		return array(
			'total'=>$jumlah,
			'rincian'=>$arrdip,
		);
	}
	
	//rincian dokter UGD
	public function actionRincianDokterUgd($awal,$akhir,$idbayar){
		$dokter = Dokter::find()->where(['idpoli'=>1])->all();
		$arrdip = array();
		$jumlah = 0;
		foreach($dokter as $r){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.medis');
			$operator = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.operator');
			$transaksi = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['>','tarif.medis',0])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->groupBy('tindakan')->all();
			$arrtrx = array();
			foreach($transaksi as $tr){
				$transaksi_jumlah = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['idtarif'=>$tr->idtarif])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->count();
				$tarif = Tarif::findOne($tr->idtarif);
				array_push($arrtrx,[
					'tindakan'=>$tr->tindakan,
					'tarif'=>$tarif->medis,
					'jumlah'=>$transaksi_jumlah,
					'total'=>$transaksi_jumlah * $tarif->medis,
					//'rincian'=>$arrtar,
				]);
			}
			array_push($arrdip,[
				'id'=>$r->id,
				'dokter'=>$r->nama_dokter,
				'jasa'=>$model,
				'operator'=>$operator,
				'tindakan'=>$arrtrx,
				//'rincian'=>$arrtar,
			]);
		}
		return $arrdip;
	}
	//rincian dokter Poli
	public function actionRincianDokterPoli($awal,$akhir,$idbayar){
		$dokter = Dokter::find()->where(['>','idpoli',1])->all();
		$arrdip = array();
		$jumlah = 0;
		foreach($dokter as $r){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.medis');
			$operator = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.operator');
			
			$transaksi_op = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['>','tarif.operator',0])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->groupBy('tindakan')->all();
			$transaksi = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['>','tarif.medis',0])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->groupBy('tindakan')->all();
			$arrtrx = array();
			$arrop = array();
			foreach($transaksi_op as $trop){
				$transaksi_jumlah_op = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['idtarif'=>$trop->idtarif])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->count();
				$tarif = Tarif::findOne($trop->idtarif);
				array_push($arrop,[
					'tindakan'=>$trop->tindakan,
					'tarif'=>$tarif->operator,
					'jumlah'=>$transaksi_jumlah_op,
					'total'=>$transaksi_jumlah_op * $tarif->operator,
					//'rincian'=>$arrtar,
				]);
			}
			foreach($transaksi as $tr){
				$transaksi_jumlah = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['idtarif'=>$tr->idtarif])->andwhere(['transaksi_detail_bill.iddokter'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->count();
				$tarif = Tarif::findOne($tr->idtarif);
				array_push($arrtrx,[
					'tindakan'=>$tr->tindakan,
					'tarif'=>$tarif->medis,
					'jumlah'=>$transaksi_jumlah,
					'total'=>$transaksi_jumlah * $tarif->medis,
					//'rincian'=>$arrtar,
				]);
			}
			
			array_push($arrdip,[
				'id'=>$r->id,
				'dokter'=>$r->nama_dokter,
				'poli'=>$r->poli->poli,
				'jasa'=>$model,
				'operator'=>$operator,
				'tindakan'=>$arrtrx,
				'tindakanop'=>$arrop,
				//'rincian'=>$arrtar,
			]);
		}
		return $arrdip;
	}
	//rincian kategori tarif
	public function actionRincianRuangan($awal,$akhir,$idbayar){
		$ruangan = TindakanKategori::find()->all();
		$arrdip = array();
		$jumlah = 0;
		foreach($ruangan as $r){
			$tarif = TarifRincian::find()->all();
			$arrtar = array();
			$total = 0;
			foreach($tarif as $t){
				$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['tarif.idkategori'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.'.$t->nama_rincian);
				$total += $model;
				if($model > 1){
					array_push($arrtar,[
						'tindakan' => $t->keterangan,
						'jumlah' => $model,
					]);
				}
			}
			$jumlah += $total;
			array_push($arrdip,[
				'id'=>$r->id,
				'ruangan'=>$r->kategori,
				'total'=>$total,
				'rincian'=>$arrtar,
			]);
		}
		return array(
			'total'=>$jumlah,
			'rincian'=>$arrdip,
		);
	}
	//rincian ruangan rawat
	public function actionRincianRuanganRawat($awal,$akhir,$idbayar){
		$ruangan = Ruangan::find()->where(['jenis'=>2])->all();
		$model2 = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['tarif.idkategori'=>2])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.tarif');
		$arrdip = array();
		foreach($ruangan as $r){
			$tarif = TarifRincian::find()->all();
			$arrtar = array();
			$total = 0;
			foreach($tarif as $t){
				$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['tarif.idruangan'=>$r->id])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.'.$t->nama_rincian);
				$total += $model;
				if($model > 1){
					array_push($arrtar,[
						'tindakan' => $t->keterangan,
						'jumlah' => $model,
					]);
				}
			}
			array_push($arrdip,[
				'id'=>$r->id,
				'ruangan'=>$r->nama_ruangan,
				'total'=>$total,
				'rincian'=>$arrtar,
			]);
		}
		return array(
			'jumlah'=>$model2,
			'rincian'=>$arrdip,
		);
	}
	//rincian transaksi
	public function actionRincianKategori($awal,$akhir,$idbayar){
		$tarif = TarifRincian::find()->all();
		$arrdip = array();
		$total = 0;
		foreach($tarif as $t){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.'.$t->nama_rincian);
			$total += $model;
			array_push($arrdip,[
				'tindakan' => $t->nama_rincian,
				'jumlah' => $model,
			]);
		}
		
		return array(
			'total'=>$total,
			'rincian'=>$arrdip,
		);
	}
	//rincian total
	public function actionRincianTes(){
		$transaksi = Transaksi::find()->where(['hide'=>1])->sum('total');
		return array(
			'jumlah'=>$transaksi,
		);
	}
	public function actionRincian($awal,$akhir,$idbayar){
		$total_penerimaan = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['transaksi.hide'=>0])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi.status'=>2])->andwhere(['>','idtarif',0])->sum('tarif.tarif');
		$bhp_ok = OperasiTindakan::find()->joinWith(['transaksi as transaksi'])->where(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi.hide'=>0])->sum('harga_bhp');
		$total_penerimaan_manual = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['transaksi.hide'=>0])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi.status'=>2])->andwhere(['idtarif'=>0])->sum('transaksi_detail_bill.tarif');
		$total_penerimaan_farmasi = ObatTransaksiDetail::find()->joinWith(['transaksiobat as transaksi_obat','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi_obat.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_obat.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('obat_transaksi_detail.total');
		$total_penerimaan_farmasi_kronis = ObatTransaksiDetail::find()->joinWith(['transaksiobat as transaksi_obat','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi_obat.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>3])->andwhere(['transaksi_obat.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('obat_transaksi_detail.total');
		$tuslah = ObatTransaksiDetail::find()->joinWith(['transaksiobat as transaksi_obat','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi_obat.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_obat.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('obat_transaksi_detail.tuslah');
		$keuntungan = ObatTransaksiDetail::find()->joinWith(['transaksiobat as transaksi_obat','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi_obat.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_obat.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('obat_transaksi_detail.keuntungan');
		$jasaracik = ObatTransaksiDetail::find()->joinWith(['transaksiobat as transaksi_obat','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi_obat.tgl,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi_obat.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('transaksi_obat.jasa_racik');
		if($idbayar == 1){
			$judul = 'Penerimaan Umum';
		}else{
			$judul = 'Penerimaan BPJS';
		}
		
		$tarif = TarifRincian::find()->all();
		$arrdip = array();
		$total = 0;
		foreach($tarif as $t){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['between','DATE_FORMAT(transaksi.tgl_keluar,"%Y-%m-%d")',$awal,$akhir])->andwhere(['idbayar'=>$idbayar])->andwhere(['transaksi.status'=>2])->andwhere(['transaksi.hide'=>0])->sum('tarif.'.$t->nama_rincian);
			$total += $model;
			array_push($arrdip,[
				'tindakan' => $t->nama_rincian,
				'ket' => $t->keterangan,
				'jumlah' => $model,
			]);
		}
		if($idbayar == 1){
			$bg ='#b7b7b7';
		}else{
			$bg='#1b623e';
		}
		return array(
				'judul'=>$judul,
				'bg'=>$bg,
				'total_penerimaan'=>$total_penerimaan,
				'total_penerimaan_tarif_manual'=>$total_penerimaan_manual,
				'bhp_ok'=>$bhp_ok,
				'farmasi'=>array(
					'total_penerimaan_farmasi'=>round($total_penerimaan_farmasi),
					'total_penerimaan_farmasi_kronis'=>$total_penerimaan_farmasi_kronis,
					'tuslah'=>$tuslah,
					'keuntungan'=>$keuntungan,
					'jasa_racik'=>$jasaracik,
				),
				'rincian'=>array(
					'total'=>$total,
					'rincian'=>$arrdip,
				),
				
		);
	}
	public function actionTransaksiBulanan($tahun){
		$transaksi = Transaksi::find()->select(['transaksi.*','DATE_FORMAT(tgl_keluar,"%m") AS bulan'])->andwhere(['DATE_FORMAT(tgl_keluar,"%Y")'=>$tahun])->andwhere(['hide'=>0])->andwhere(['status'=>2])->groupBy('bulan')->all();
		$arrtrans = array();
		foreach($transaksi as $tr){
			$bhp_ok_umum = OperasiTindakan::find()->where(['DATE_FORMAT(tgl,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(tgl,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['idbayar'=>1])->sum('harga_bhp');
			$bhp_ok_bpjs = OperasiTindakan::find()->where(['DATE_FORMAT(tgl,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(tgl,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['idbayar'=>2])->sum('harga_bhp');
			$total_penerimaan_manual_umum = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as trx'])->where(['DATE_FORMAT(trx.tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(trx.tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['trx.hide'=>0])->andwhere(['trx.status'=>2])->andwhere(['idbayar'=>1])->andwhere(['idbayar'=>1])->andwhere(['idtarif'=>0])->sum('transaksi_detail_bill.tarif');
			$total_penerimaan_manual_bpjs = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as trx'])->where(['DATE_FORMAT(trx.tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(trx.tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['trx.hide'=>0])->andwhere(['trx.status'=>2])->andwhere(['idbayar'=>1])->andwhere(['idbayar'=>2])->andwhere(['idtarif'=>0])->sum('transaksi_detail_bill.tarif');
			$trx_umum = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as trx'])->where(['DATE_FORMAT(trx.tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(trx.tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['trx.hide'=>0])->andwhere(['trx.status'=>2])->andwhere(['idbayar'=>1])->sum('tarif.tarif');
			$trx_bpjs = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as trx'])->where(['DATE_FORMAT(trx.tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(trx.tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['trx.hide'=>0])->andwhere(['trx.status'=>2])->andwhere(['idbayar'=>2])->sum('tarif.tarif');
			// $trx_umum = Transaksi::find()->where(['DATE_FORMAT(tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['hide'=>0])->andwhere(['status'=>2])->sum('total_bayar');
			// $trx_bpjs = Transaksi::find()->where(['DATE_FORMAT(tgl_keluar,"%Y")'=>$tahun])->andwhere(['DATE_FORMAT(tgl_keluar,"%m")'=>date('m',strtotime($tr->tgl_keluar))])->andwhere(['hide'=>0])->andwhere(['status'=>2])->sum('total_ditanggung');
			array_push($arrtrans,[
				'bulan' => date('F',strtotime($tr->tgl_keluar)),
				'bpjs'=>round($trx_bpjs) + round($total_penerimaan_manual_bpjs) + round($bhp_ok_bpjs),
				'umum'=>round($trx_umum) + round($total_penerimaan_manual_umum) +round($bhp_ok_umum) ,
				'manual_umum'=>round($total_penerimaan_manual_umum),
				'manual_bpjs'=>round($total_penerimaan_manual_bpjs),
				'bhp_umum'=>round($bhp_ok_umum),
				'bhp_bpjs'=>round($bhp_ok_bpjs),
			]);
		}
		return $arrtrans;
	}
}