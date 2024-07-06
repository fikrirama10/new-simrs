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

class RestKeuanganController extends \yii\rest\Controller
{
		public function actionRincianBill($id){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idtransaksi'=>$id])->groupBy(['tindakan','idbayar'])->orderBy(['tarif.idkategori'=>SORT_ASC])->all();
			$arrdip = array();
			foreach($model as $m){
				$hitung = TransaksiDetailBill::find()->where(['idtransaksi'=>$id])->andwhere(['tindakan'=>$m->tindakan])->andwhere(['idbayar'=>$m->idbayar])->count();
				//$tarif = Tarif::findOne($m->idtarif);
				array_push($arrdip,[
					'idtrx'=>$m->idtransaksi,
					'idtarif'=>$m->idtarif,
					'nama_tarif'=>$m->tindakan,
					'idbayar'=>$m->idbayar,
					'bayar'=>$m->bayar->bayar,
					'harga'=>$m->tarif,
					'jumlah'=>$hitung,
					'total'=>$hitung * $m->tarif,
				]);
			}
			return $arrdip;
		}
		public function actionRincianBillBayarDua($id,$idbayar){
			$model = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>$idbayar])->groupBy(['idbayar','tarif.idkategori'])->all();
			$arrdip = array();
			
			foreach($model as $m){
				$tarif = Tarif::findOne($m->idtarif);
				$kategori = TindakanKategori::findOne($tarif->idkategori);
				$model_dua = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['tarif.idkategori'=>$kategori->id])->andwhere(['idbayar'=>$idbayar])->andwhere(['tindakan'=>$m->tindakan])->all();
				
				foreach($model_dua as $md){
					array_push($arrdip_dua,[
						'idtarif'=>$md->idtarif,
						'nama_tarif'=>$md->tindakan,
						'idbayar'=>$md->idbayar,
						'bayar'=>$md->bayar->bayar,
						'harga'=>$md->tarif,
					]);
				}
				array_push($arrdip,[
					'idtrx'=>$kategori->kategori,
					'tindakan'=>$arrdip_dua,
					// 'idtarif'=>$m->idtarif,
					// 'nama_tarif'=>$m->tindakan,
					// 'idbayar'=>$m->idbayar,
					// 'bayar'=>$m->bayar->bayar,
					// 'harga'=>$m->tarif,
					// 'jumlah'=>$hitung,
					// 'total'=>$hitung * $m->tarif,
				]);
			}
			return $arrdip;
		}
		public function actionRincianBillBayar($id,$idbayar){
			$model = TransaksiDetailBill::find()->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>$idbayar])->groupBy(['tindakan','idbayar'])->all();
			$arrdip = array();
			foreach($model as $m){
				$hitung = TransaksiDetailBill::find()->where(['idtransaksi'=>$id])->andwhere(['idbayar'=>$idbayar])->andwhere(['tindakan'=>$m->tindakan])->count();
				//$tarif = Tarif::findOne($m->idtarif);
				array_push($arrdip,[
					'idtrx'=>$m->idtransaksi,
					'idtarif'=>$m->idtarif,
					'nama_tarif'=>$m->tindakan,
					'idbayar'=>$m->idbayar,
					'bayar'=>$m->bayar->bayar,
					'harga'=>$m->tarif,
					'jumlah'=>$hitung,
					'total'=>$hitung * $m->tarif,
				]);
			}
			return $arrdip;
		}
		public function actionRincianTindakanTrx($id){
			$model = TransaksiDetailRinci::find()->joinWith(['transaksi as transaksi'])->where(['transaksi.idkunjungan'=>$id])->groupBy(['idtarif'])->all();
			$arrdip = array();
			foreach($model as $m){
				$hitung = TransaksiDetailRinci::find()->joinWith(['transaksi as transaksi'])->where(['transaksi.idkunjungan'=>$id])->andwhere(['idtarif'=>$m->idtarif])->count();
				$tarif = Tarif::findOne($m->idtarif);
				array_push($arrdip,[
					'idtarif'=>$m->idtarif,
					'nama_tarif'=>$tarif->nama_tarif,
					'harga'=>$tarif->tarif,
					'jumlah'=>$hitung,
					'total'=>$hitung * $tarif->tarif,
				]);
			}
			return $arrdip;
		}
		public function actionRincianTindakan($id){
			$model = TransaksiDetailRinci::find()->where(['idrawat'=>$id])->groupBy(['idtarif'])->all();
			$arrdip = array();
			foreach($model as $m){
				$hitung = TransaksiDetailRinci::find()->where(['idrawat'=>$id])->andwhere(['idtarif'=>$m->idtarif])->count();
				$tarif = Tarif::findOne($m->idtarif);
				array_push($arrdip,[
					'idtarif'=>$m->idtarif,
					'nama_tarif'=>$tarif->nama_tarif,
					'harga'=>$tarif->tarif,
					'jumlah'=>$hitung,
					'total'=>$hitung * $tarif->tarif,
				]);
			}
			return $arrdip;
		}
		public function actionRincianBpjs($id){
			$rawat = Rawat::findOne($id);
			$rincian = TransaksiDetailRinci::find()->where(['idrawat'=>$id])->all();
			$rincian_obat = ObatTransaksi::find()->where(['idrawat'=>$id])->all();
			$arrdip = array();
			$arrobat = array();
			$arrdetail = array();
			foreach($rincian as $r){
				array_push($arrdip,[
					'idtransaksi' => $r->idtransaksi,
					'idtarif' => $r->idtarif,
					'nama_tindakan' => $r->tindakan->nama_tarif,
					'tarif' => $r->tarif,
				]);
			}
			foreach($rincian_obat as $ro){
				$detail_bpjs = ObatTransaksiDetail::find()->where(['idtrx'=>$ro->id])->andwhere(['idbayar'=>2])->sum('total'); 
				$detail_kronis = ObatTransaksiDetail::find()->where(['idtrx'=>$ro->id])->andwhere(['idbayar'=>3])->sum('total'); 
				array_push($arrobat,[
					'kode_resep' => $ro->kode_resep,
					'total_harga' => $ro->total_harga,
					'bpjs' => $detail_bpjs,
					'kronis' => $detail_kronis,
				]);
			}
			if($rawat->idjenisrawat == 2){
				$ruangan = Tarif::find()->where(['idruangan'=>$rawat->idruangan])->one();
				$tanggal = date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))));
				$date1=date_create($rawat->tglmasuk);
				if($rawat->tglpulang == null){
					$date2=date_create($tanggal);
				}else{
					$date2=date_create($rawat->tglpulang);
				}
				
				$diff=date_diff($date1,$date2);
				$kamar = $ruangan->tarif * $diff->format("%d");
			}else{
				$kamar = 0;
			}
			$lab = TransaksiDetailRinci::find()->joinWith(['tindakan as tindakan'])->where(['idrawat'=>$id])->andwhere(['tindakan.idkategori'=>4])->sum('transaksi_detail_rinci.tarif');
			$rad = TransaksiDetailRinci::find()->joinWith(['tindakan as tindakan'])->where(['idrawat'=>$id])->andwhere(['tindakan.idkategori'=>5])->sum('transaksi_detail_rinci.tarif');
			$bed = TransaksiDetailRinci::find()->joinWith(['tindakan as tindakan'])->where(['idrawat'=>$id])->andwhere(['tindakan.idkategori'=>7])->sum('transaksi_detail_rinci.tarif');
			$konsul = TransaksiDetailRinci::find()->joinWith(['tindakan as tindakan'])->where(['idrawat'=>$id])->andwhere(['tindakan.idkategori'=>2])->andwhere(['>','tindakan.medis',0])->sum('transaksi_detail_rinci.tarif');
			$keperawatan = TransaksiDetailRinci::find()->joinWith(['tindakan as tindakan'])->where(['idrawat'=>$id])->andwhere(['tindakan.idkategori'=>2])->andwhere(['tindakan.medis'=>0])->andwhere(['<>','tindakan.kat_tindakan',8])->sum('transaksi_detail_rinci.tarif');
			array_push($arrdetail,[
					'kamar'=>$kamar,
					'lab'=>$lab,
					'radiologi'=>$rad,
					'bedah'=>$bed,
					'konsultasi'=>$konsul,
					'keperawatan'=>$keperawatan,
			]);
			return array(
				'tindakan'=>$arrdip,
				'obat'=>$arrobat,
				'detail'=>$arrdetail,
			);
		}
    	public function actionPenerimaanBarang($start,$end){
		$penerimaan = PenerimaanBarang::find()->where(['between','DATE_FORMAT(tgl_faktur,"%Y-%m-%d")',$start,$end])->groupBy(['idsuplier'])->orderBy(['tgl_faktur'=>SORT_ASC])->all();
		$arrpenerimaan = array();
		foreach($penerimaan as $p){
			$detail = PenerimaanBarangDetail::find()->joinWith(['penerimaan as penerimaan'])->where(['between','DATE_FORMAT(tgl_faktur,"%Y-%m-%d")',$start,$end])->andwhere(['penerimaan.idsuplier'=>$p->idsuplier])->all();
			$arrdetail = array();
			foreach($detail as $d){
				array_push($arrdetail,[
					'barang' => $d->obat->nama_obat,
					'satuan' => $d->obat->satuan->satuan,
					'jumlah' => $d->jumlah,
					'harga' => $d->harga,
					'total' => $d->total,
					'diskon' => $d->diskon,
					'ppn' => $d->ppn,
					'total_diskon' => $d->total_diskon,
				]);
			}
			array_push($arrpenerimaan,[
				'tgl' => $p->tgl_faktur,
				'idsuplier' => $p->idsuplier,
				'suplier' => $p->suplier->suplier,
				'nilai_faktur' => $p->nilai_faktur,
				'obat' => $arrdetail,
			]);
		}
		return $arrpenerimaan;
	}
	public function actionPenerimaanAtk($start,$end){
		$penerimaan = BarangPenerimaan::find()->where(['between','DATE_FORMAT(tgl,"%Y-%m-%d")',$start,$end])->groupBy(['idsuplier'])->all();
		$arrpenerimaan = array();
		foreach($penerimaan as $p){
			$detail = BarangPenerimaanDetail::find()->joinWith(['penerimaan as penerimaan'])->where(['between','DATE_FORMAT(penerimaan.tgl,"%Y-%m-%d")',$start,$end])->andwhere(['penerimaan.idsuplier'=>$p->idsuplier])->all();
			$arrdetail = array();
			foreach($detail as $d){
			    $barang = DataBarang::findOne($d->idbarang);
			    if($barang){
			        $dbarang = $barang->nama_barang;
			        $dsatuan = $barang->satuan->satuan;
			    }else{
			        $dbarang='-';
			        $dsatuan ='-';
			    }
				array_push($arrdetail,[
					'barang' => $dbarang,
					'satuan' => $dsatuan,
					'jumlah' => $d->qty,
					'harga' => $d->harga,
					'total' => $d->total,
				]);
			}
			array_push($arrpenerimaan,[
				'tgl' => $p->tgl,
				'idsuplier' => $p->idsuplier,
				'suplier' => $p->suplier->suplier,
				'obat' => $arrdetail,
			]);
		}
		return $arrpenerimaan;
	}
	//public function actionUnit
	public function actionUnit(){
		$unit = UnitRuangan::find()->all();
		$aunit = array();
		foreach($unit as $um){
			array_push($aunit,[
					'id' => $um->id,
					'unit' => $um->ket,
				]);
		}
		return $aunit;
	}
	public function actionBekkes($start,$end){
		$model = PermintaanObatRequest::find()->joinWith(['permintaan as permintaan'])->where(['between','permintaan.tgl_permintaan',$start,$end])->andwhere(['>','permintaan_obat_request.status',1])->groupBy('nama_obat')->all();
		$arrdip = array();
		
		foreach($model as $m){
			if($m->idobat == null){
				$satuan = '-';
			}else{
				$satuan = $m->obat->satuan->satuan;
			}
			$user = UnitRuangan::find()->all();
			$arrunit = array();
			if($m->idobat != null){
					$bacth = ObatBacth::find()->where(['idobat'=>$m->idobat])->all();
					$obatStok = 0;
					foreach($bacth as $os){
						$obatStok += $os->stok_gudang;
					}
					$stok = $obatStok;
				}else{
					$stok = 0;
				}
			$totalObat = 0;
			foreach($user as $user){
				
				$totalPermintaan = PermintaanObatRequest::find()->joinWith(['permintaan as permintaan'])->where(['permintaan.idruangan'=>$user->id])->andwhere(['nama_obat'=>$m->nama_obat])->sum('jumlah_setuju');
				$totalObat += $totalPermintaan;
				array_push($arrunit,[
					'id' => $user->id,
					'unit' => $user->ket,
					'jumlah' => $totalPermintaan,
					
					
				]);
			}
			if($stok > $totalObat){
				$beli = 0;
			}else{
				$beli = $totalObat - $stok;
			}
		array_push($arrdip,[
				'id' => $m->idobat,
				'nama_barang' => $m->nama_obat,
				'harga' => $m->harga,
				'satuan' => $satuan,
				'stok' => $stok,
				'total' => $totalObat,
				'beli' => $beli,
				'unit' => $arrunit,
				
				]);
		}
		return $arrdip;
	}
	public function actionBarangAmprah($start,$end){
		$model = BarangAmprahDetail::find()->joinWith(['amprahan as amprahan'])->where(['between','amprahan.tgl_permintaan',$start,$end])->andwhere(['barang_amprah_detail.status'=>2])->groupBy('nama_barang')->all();
		$arrdip = array();
		foreach($model as $m){
			$user = UnitRuangan::find()->all();
			$arruser = array();
			if($m->idbarang != null){
					$barang = DataBarang::findOne($m->idbarang);
					$stok = $barang->stok;
				}else{
					$stok = 0;
				}
			$hargaTotal = 0;
			$barangTotal = 0;
			foreach($user as $user){
				
				$totalPermintaan = BarangAmprahDetail::find()->joinWith(['amprahan as amprahan'])->where(['amprahan.idruangan'=>$user->id])->andwhere(['nama_barang'=>$m->nama_barang])->sum('qty_setuju');
				$totalHarga = BarangAmprahDetail::find()->joinWith(['amprahan as amprahan'])->where(['amprahan.idruangan'=>$user->id])->andwhere(['nama_barang'=>$m->nama_barang])->sum('barang_amprah_detail.total');
				$hargaTotal += $totalHarga;
				$barangTotal += $totalPermintaan;
				array_push($arruser,[
					'id' => $user->id,
					'unit' => $user->ket,
					'jumlah' => $totalPermintaan,
					'harga' => $totalHarga,
					
				]);
			}
			if($m->idbarang == null){
				$satuan = '-';
			}else{
				$satuan = $m->barang->satuan->satuan;
			}
			if($stok > $barangTotal){
				$beli = 0;
			}else{
				$beli = $barangTotal - $stok;
			}
			array_push($arrdip,[
				'id' => $m->id,
				'nama_barang' => $m->nama_barang,
				'satuan' => $satuan,
				'stok' => $stok,
				'harga' => $m->harga,
				'total' => $hargaTotal,
				'totalBarang' => $barangTotal,
				'beli' => $beli,
				'unit' => $arruser,
			]);
		}
		return $arrdip;
	}
	public function actionRincian($start,$end,$idbayar){
		$response= Yii::$app->kazo->content_noid(Yii::$app->params['baseUrl'].'dashboard/rest-keuangan/rincian-transaksi?start='.$start.'&end='.$end.'&idbayar='.$idbayar);	
		$data_json=json_decode($response, true);
		$medis = 0;$paramedis = 0;$petugas = 0;$medis = 0;$gizi = 0;$bhp = 0;$sewakamar = 0;$sewaalat = 0; $makanpasien = 0;$laundry = 0; $cs = 0;$opsrs = 0;$nova = 0; 
		$r_medis = 0;$radiografer = 0;$radiolog = 0;
		$assisten = 0;$operator = 0;$ass_tim = 0;
		$dokter_a = 0;$cssd = 0;$bbm = 0;
		$ranmor = 0;$sopir = 0;$dokter = 0;
		$rs = 0;$harbang = 0;$atlm = 0;
		$asisten_anastesi = 0;$sewa_ok	 = 0;$apoteker = 0;
		$total = 0;
		$no=1; for($a=0; $a < count($data_json); $a++){
			foreach($data_json[$a]['rincian'] as $ja): 				
				$medis += $ja['medis'];$paramedis += $ja['paramedis'];$petugas += $ja['petugas'];
				$gizi += $ja['gizi'];$bhp += $ja['bhp'];$sewakamar += $ja['sewakamar'];
				$sewaalat += $ja['sewaalat'];$makanpasien += $ja['makanpasien'];
				$laundry += $ja['laundry'];$cs += $ja['cs'];
				$opsrs += $ja['opsrs'];$nova += $ja['nova'];
				$r_medis += $ja['r_medis'];$radiografer += $ja['radiografer'];
				$radiolog += $ja['radiolog'];$assisten += $ja['assisten'];
				$operator += $ja['operator'];$ass_tim += $ja['ass_tim'];
				$dokter_a += $ja['dokter_a'];$cssd += $ja['cssd'];$bbm += $ja['bbm'];
				$ranmor += $ja['ranmor'];$sopir += $ja['supir'];
				$dokter += $ja['dokter'];$rs += $ja['rs'];
				$harbang += $ja['harbang'];$atlm += $ja['atlm'];
				$asisten_anastesi += $ja['asisten_anastesi'];$sewa_ok += $ja['sewa_ok'];
				$apoteker += $ja['apoteker'];
			endforeach;
			$arrdip = array();
		}
		
		return array(
			'rincianTarif'=>array(
				'asisten_anastesi'=>$asisten_anastesi,
				'sewa_ok'=>$sewa_ok,
				'apoteker'=>$apoteker,
				'medis'=>$medis,
				'paramedis'=>$paramedis,
				'petugas'=>$petugas,
				'gizi'=>$gizi,
				'bhp'=>$bhp,
				'sewakamar'=>$sewakamar,
				'sewaalat'=>$sewaalat,
				'makanpasien'=>$makanpasien,
				'laundry'=>$laundry,
				'cs'=>$cs,
				'opsrs'=>$opsrs,
				'nova'=>$nova,
				'r_medis'=>$r_medis,
				'radiografer'=>$radiografer,
				'radiolog'=>$radiolog,
				'assisten'=>$assisten,
				'operator'=>$operator,
				'ass_tim'=>$ass_tim,
				'dokter_a'=>$dokter_a,
				'cssd'=>$cssd,
				'bbm'=>$bbm,
				'ranmor'=>$ranmor,
				'sopir'=>$sopir,
				'dokter'=>$dokter,
				'rs'=>$rs,
				'harbang'=>$harbang,
				'atlm'=>$atlm,
				'total'=>$medis + $paramedis + $petugas + $gizi + $bhp + $sewakamar + $sewaalat + $makanpasien + $laundry + $cs + $opsrs + $nova + $r_medis + $radiografer + $radiolog + $assisten + $operator + $ass_tim + $dokter_a + $cssd + $bbm + $ranmor + $sopir + $dokter +$rs + $harbang + $atlm + $sewa_ok + $asisten_anastesi + $apoteker,
			),
			
			
		);
	}
	public function actionRincianUnit($start='',$end='',$bayar=''){
		$tindakanKat = TindakanKategori::find()->all();
			$arrkat = array();
			$total = 0;
			foreach($tindakanKat as $tk){
				$transaksiBill = TransaksiDetailBill::find()->joinWith(['tarif as tarif','transaksi as transaksi'])->where(['tarif.idkategori'=>$tk->id])->andwhere(['transaksi.status'=>2])->andwhere(['idbayar'=>$bayar])->andwhere(['between','DATE_FORMAT(transaksi.tgltransaksi,"%Y-%m-%d")',$start,$end])->sum('transaksi_detail_bill.tarif');
				array_push($arrkat,[
					'id' => $tk->id,
					'kategori'=>$tk->kategori,
					'total'=>$transaksiBill,
				]);
				$total +=$transaksiBill;
			}
			return $arrkat;
	}
	public function actionRincianTransaksi($start,$end,$idbayar=''){
		$query = Transaksi::find()->where(['status'=>2])->andwhere(['between','DATE_FORMAT(tgltransaksi,"%Y-%m-%d")',$start,$end])->all();
		$arrdip=array();
		foreach ($query as $q){
			$rincian = TransaksiDetailBill::find()->where(['idtransaksi'=>$q->id])->all();
			$arrinci=array();
			$totalmedis = 0; $totalpara = 0;
			foreach($rincian as $r){
				if($r->jumlah == null){
					$jumlah = 1;
				}else{
					$jumlah = $r->jumlah;
				}
				$medis = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.medis');
				$paramedis = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.paramedis') * $jumlah;
				$petugas = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.petugas') * $jumlah;
				$apoteker = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.apoteker') * $jumlah;
				$gizi = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.gizi') * $jumlah;
				$bhp = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.bph') * $jumlah;
				$sewakamar = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.sewakamar') * $jumlah;
				$sewaalat = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.sewaalat') * $jumlah;
				$makanpasien = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.makanpasien') * $jumlah;
				$laundry = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.laundry') * $jumlah;
				$cs = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.cs') * $jumlah;
				$opsrs = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.opsrs') * $jumlah;
				$nova = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.nova_t') * $jumlah;
				$r_medis = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.perekam_medis') * $jumlah;
				$radiografer = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.radiografer') * $jumlah;
				$radiolog = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.radiolog') * $jumlah;
				$assisten = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.assisten') * $jumlah;
				$operator = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.operator') * $jumlah;
				$ass_tim = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.ass_tim') * $jumlah;
				$dokter_a = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.dokter_anastesi') * $jumlah;
				$asisten_anastesi = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.asisten_anastesi') * $jumlah;
				$cssd = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.cssd') * $jumlah;
				$bbm = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.bbm') * $jumlah;
				$ranmor = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.ranmor') * $jumlah;
				$supir = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.supir') * $jumlah;
				$dokter = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.dokter') * $jumlah;
				$rs = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.rs') * $jumlah;
				$harbang = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.harbang') * $jumlah;
				$atlm = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.atlm') * $jumlah;
				$sewa_ok = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idbayar'=>$idbayar])->andwhere(['transaksi_detail_bill.id'=>$r->id])->sum('tarif.sewa_ok') * $jumlah;
				
				array_push($arrinci,[
					'id' => $r->id,
					'idtarif' => $r->idtarif,
					'tindakan' => $r->tindakan,					
					'medis' => $medis,
					'paramedis' => $paramedis,
					'petugas' => $petugas,
					'gizi' => $gizi,
					'bhp' => $bhp,
					'sewakamar' => $sewakamar,
					'sewaalat' => $sewaalat,
					'makanpasien' => $makanpasien,
					'laundry' => $laundry,
					'cs' => $cs,
					'opsrs' => $opsrs,
					'nova' => $nova,
					'r_medis' => $r_medis,
					'radiografer' => $radiografer,
					'radiolog' => $radiolog,
					'assisten' => $assisten,
					'operator' => $operator,
					'ass_tim' => $ass_tim,
					'dokter_a'=>$dokter_a,
					'cssd'=>$cssd,
					'bbm'=>$bbm,
					'ranmor'=>$ranmor,
					'supir'=>$supir,
					'dokter'=>$dokter,
					'rs'=>$rs,
					'harbang'=>$harbang,
					'atlm'=>$atlm,
					'asisten_anastesi'=>$asisten_anastesi,
					'sewa_ok'=>$sewa_ok,
					'apoteker'=>$apoteker,
					
				]);
			}
			$tindakanKat = TindakanKategori::find()->all();
			$arrkat = array();
			foreach($tindakanKat as $tk){
				$transaksiBill = TransaksiDetailBill::find()->joinWith(['tarif as tarif'])->where(['idtransaksi'=>$q->id])->andwhere(['tarif.idkategori'=>$tk->id])->andwhere(['idbayar'=>$idbayar])->sum('transaksi_detail_bill.tarif');
				array_push($arrkat,[
					'id' => $tk->id,
					'kategori'=>$tk->kategori,
					'total'=>$transaksiBill,
				]);
				
			}
			array_push($arrdip,[
			'id' => $q->id,
			'mana' => $q->pasien->nama_pasien,
			'idtrx' => $q->idtransaksi,
			'tgltransaksi' => $q->tgltransaksi,
			'rincian'=>$arrinci,
			'trxRinci'=>$arrkat,
			
			]);
		}
		return $arrdip;
		
	}
}