<?php

use yii\helpers\Html;
use common\models\SettingSimrs;

$simrs = SettingSimrs::findOne(3);
?>
<aside class="main-sidebar">

	<section class="sidebar">

		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<?php if ($simrs) { ?>
					<?= Html::img(Yii::$app->params['baseUrl2'] . '/frontend/images/setting/' . $simrs->logo_rs, ['alt' => 'no picture', 'class' => 'user-image']) ?>
				<?php } else { ?>
					<?= Html::img(Yii::$app->params['baseUrl'] . '/frontend/images/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png', ['alt' => 'no picture', 'class' => 'user-image']) ?>
				<?php } ?>
			</div>
			<div class="pull-left info">
				<p><?= Yii::$app->user->identity->userdetail->nama ?></p>

				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>



		<?php
		$statistik = [
			'label' => 'Statistik',
			'icon' => 'fas fa-bar-chart',
			'url' => '#',
			'items' => [

				[
					'label' => 'Kunjungan Pasien', 'icon' => 'fas fa-users','url'=>'#',
					'items' => [
						['label' => 'Laporan Kunjungan', 'icon' => 'fas fa-medkit', 'url' => ['/kunjungan-pasien'],],
															
						
					],

				],
				['label' => 'Laporan Poliklinik', 'icon' => 'fas fa-users', 'url' => ['/poliklinik/laporan'],],
				['label' => '10 Diagnosa Terbanyak', 'icon' => 'fas fa-users', 'url' => ['/diagnosa'],],
				['label' => 'Sistem Informasi', 'icon' => 'fas fa-users', 'url' => ['/sistem-informasi'],],
				['label' => 'Data BOR', 'icon' => 'fas fa-users', 'url' => ['/pasien'],],

			],
		];
		$personel = [
			'label' => 'Personel',
			'icon' => 'fas  fa-users',
			'url' => '#',
			'items' => [
				['label' => 'Data Personel', 'icon' => 'fas fa-users', 'url' => ['/personel'],],
				['label' => 'Jabatan', 'icon' => 'fas fa-users', 'url' => ['/personel-jabatan'],],
				['label' => 'Pangkat', 'icon' => 'fas fa-users', 'url' => ['/personel-pangkat'],],
				['label' => 'Profesi', 'icon' => 'fas fa-users', 'url' => ['/personel-profesi'],],
				['label' => 'Jenis', 'icon' => 'fas fa-users', 'url' => ['/personel-jenis'],],

			],


		];
		$permintaan = [
			'label' => 'Permintaan Barang',
			'icon' => 'fas  fa-shopping-cart',
			'url' => '#',
			'items' => [
				['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
				['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


			],
		];

		$humas = [
			'label' => 'Humas',
			'icon' => 'fas  fa-users',
			'url' => '#',
			'items' => [
				['label' => 'Humas', 'icon' => 'fas fa-users', 'url' => ['/'],],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],


			],


		];
		$pendaftaran = [
			'label' => 'Rekam medis',
			'icon' => 'fas  fa-list',
			'url' => '#',
			'items' => [
				[
					'label' => 'Pendaftaran',
					'icon' => 'fas  fa-desktop',
					'url' => '#',
					'items' => [
						['label' => 'Data Pasien', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pasien'],],
						['label' => 'Pendaftaran Pasien Baru', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pasien/create'],],
						['label' => 'Pendaftaran Online', 'icon' => 'fas  fa-file-text-o', 'url' => ['/poliklinik/index-online'],],
						['label' => 'Data Pasien Rawat', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pasien/list-poliklinik'],],
						['label' => 'Kuota Pasien', 'icon' => 'fas fa-users', 'url' => ['/pasien/kuota-pasien'],],
						['label' => 'Data Dokter', 'icon' => 'fas fa-users', 'url' => ['/dokter'],],
						['label' => 'KLPCM', 'icon' => 'fas fa-book', 'url' => ['/klpcm'],],

					],
				],
				[
					'label' => 'Rujukan',
					'icon' => 'fas  fa-keyboard-o',
					'url' => '#',
					'items' => [
						['label' => 'Rujukan BPJS ', 'icon' => 'fas fa-users', 'url' => ['/rujukan'],],
						['label' => 'Rujukan Balik ', 'icon' => 'fas fa-users', 'url' => ['/rujukan-prb'],],
						['label' => 'Rujukan Umum ', 'icon' => 'fas fa-users', 'url' => ['/rujukan-umum'],],
					],
				],
				[
					'label' => 'Vclaim',
					'icon' => 'fas  fa-vimeo',
					'url' => '#',
					'items' => [
						['label' => 'Rujukan BPJS ', 'icon' => 'fas fa-users', 'url' => ['/rujukan-faskes'],],
						['label' => 'Surat Kontrol ', 'icon' => 'fas fa-users', 'url' => ['/surat-kontrol'],],
						['label' => 'Update Pulang ', 'icon' => 'fas fa-users', 'url' => ['/update-pulang'],],
						['label' => 'Data Kunjungan ', 'icon' => 'fas fa-users', 'url' => ['/data-kunjungan-bpjs'],],
					],
				],
				[
					'label' => 'Monitoring Antrol',
					'icon' => 'fas  fa-keyboard-o',
					'url' => '#',
					'items' => [
						['label' => 'Dashboard Tanggal ', 'icon' => 'fas fa-users', 'url' => ['/monitoring-antrean/pertanggal'],],
						['label' => 'Dashboard Bulan ', 'icon' => 'fas fa-users', 'url' => ['/monitoring-antrean/per-bulan'],],
						['label' => 'Antrian ', 'icon' => 'fas fa-users', 'url' => ['/antrean/index-tgl'],],
					],
				],
				[
					'label' => 'Admisi',
					'icon' => 'fas  fa-keyboard-o',
					'url' => '#',
					'items' => [
						['label' => 'Ruangan', 'icon' => 'fas fa-users', 'url' => ['/ruangan'],],
						['label' => 'Pindah Ruangan', 'icon' => 'fas fa-users', 'url' => ['/admisi/pindah-ruangan'],],
						['label' => 'Buat SPRI', 'icon' => 'fas fa-users', 'url' => ['/admisi'],],
						['label' => 'SPRI Jadwal', 'icon' => 'fas fa-users', 'url' => ['/admisi/index-semua'],],
					],
				],
				[
					'label' => 'Statistik',
					'icon' => 'fas fa-bar-chart',
					'url' => '#',
					'items' => [

						[
							'label' => 'Kunjungan Pasien', 'icon' => 'fas fa-users','url'=>'#',
							'items' => [
								['label' => 'Laporan Kunjungan', 'icon' => 'fas fa-medkit', 'url' => ['/kunjungan-pasien'],],
																	
								
							],

						],
						['label' => 'Laporan Poliklinik', 'icon' => 'fas fa-users', 'url' => ['/poliklinik/laporan'],],
						['label' => '10 Diagnosa Terbanyak', 'icon' => 'fas fa-users', 'url' => ['/diagnosa'],],
						['label' => 'Sistem Informasi', 'icon' => 'fas fa-users', 'url' => ['/sistem-informasi'],],
						// ['label' => 'Data BOR', 'icon' => 'fas fa-users', 'url' => ['/pasien'],],

					],
				],
				// [
				// 	'label' => 'Permintaan Barang',
				// 	'icon' => 'fas  fa-shopping-cart',
				// 	'url' => '#',
				// 	'items' => [
				// 		['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
				// 		['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


				// 	],
				// ],

			],

		];
		$rawatjalan = [
			'label' => 'Poliklinik',
			'icon' => 'fas fa-hospital-o',
			'url' => '#',
			'items' => [
				[
					'label' => 'Daftar Pasien',
					'icon' => 'fas  fa-list',
					'url' => '#',
					'items' => [
						['label' => 'Pasien Hari ini', 'icon' => 'fas fa-hospital-o', 'url' => ['/poliklinik'],],
						['label' => 'Semua Pasien', 'icon' => 'fas fa-cart-plus', 'url' => ['/poliklinik/all-pasien'],],

					],
				],
				[
					'label' => 'Laporan Kunjungan',
					'icon' => 'fas  fa-list',
					'url' => '#',
					'items' => [
						['label' => 'Data Pasien Umum', 'icon' => 'fas fa-hospital-o', 'url' => ['/poliklinik/history-pasien-umum'],],
						['label' => 'Data Pasien BPJS', 'icon' => 'fas fa-hospital-o', 'url' => ['/poliklinik/history-pasien'],],

					],
				],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],

			],
		];
		$spri = [
			'label' => 'SPRI',
			'icon' => 'fas fa-hospital-o',
			'url' => '#',
			'items' => [
				['label' => 'SPRI', 'icon' => 'fas fa-hospital-o', 'url' => ['/admisi'],],
				['label' => 'SPRI Selesai', 'icon' => 'fas fa-hospital-o', 'url' => ['/admisi/index-selesai'],],
				['label' => 'SPRI Semua', 'icon' => 'fas fa-hospital-o', 'url' => ['//admisi/index-semua'],],
			],
		];
		$ugd = [
			'label' => 'UGD',
			'icon' => 'fas fa-ambulance',
			'url' => '#',
			'items' => [
				['label' => 'UGD', 'icon' => 'fas fa-money', 'url' => ['/poliklinik/ugd'],],
				['label' => 'Pasien UGD', 'icon' => 'fas fa-hospital-o', 'url' => ['/poliklinik/all-pasien-ugd'],],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],


		];
		$tb = [
			'label' => 'TB',
			'icon' => 'fas fa-ambulance',
			'url' => '#',
			'items' => [
				['label' => 'Pasien TB', 'icon' => 'fas fa-money', 'url' => ['/tb'],],

			],


		];
		$obat = [
			'label' => 'Obat & Alkes',
			'icon' => 'fas fa-medkit',
			'url' => '#',
			'items' => [
				['label' => 'List Obat & Alkes', 'icon' => 'fas fa-money', 'url' => ['/obat'],],
				['label' => 'List Obat & Alkes Habis', 'icon' => 'fas fa-money', 'url' => ['/obat/barang-habis'],],
				['label' => 'List Obat & Alkes ED', 'icon' => 'fas fa-money', 'url' => ['/obat/kadaluarsa'],],
				['label' => 'Obat Keluar Masuk', 'icon' => 'fas fa-money', 'url' => ['/obat/keluar-masuk'],],
			],
		];
		$tindakan = [
			'label' => 'Tindakan',
			'icon' => 'fas  fa-stethoscope',
			'url' => '#',
			'items' => [
				['label' => 'List Tindakan', 'icon' => 'fas fa-money', 'url' => ['/tindakan'],],
			],
		];
		$user = [
			'label' => 'User ',
			'icon' => 'fas fa-users',
			'url' => '#',
			'items' => [
				['label' => 'List User', 'icon' => 'fas fa-money', 'url' => ['/user-detail'],],
			],
		];
		$ok = [
			'label' => 'Kamar OK',
			'icon' => 'fas fa-users',
			'url' => '#',
			'items' => [
				['label' => 'List Operasi ', 'icon' => 'fas fa-money', 'url' => ['/operasi'],],
			],
			[
				'label' => 'Permintaan Barang',
				'icon' => 'fas  fa-shopping-cart',
				'url' => '#',
				'items' => [
					['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
					['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


				],
			],
		];
		$radiologi = [
			'label' => 'Radiologi',
			'icon' => 'fas fa-certificate',
			'url' => '#',
			'items' => [
				['label' => 'Radiologi', 'icon' => 'fas fa-certificate', 'url' => ['/radiologi-order'],],
				[
					'label' => 'Laporan radiologi',
					'icon' => 'fas  fa-file',
					'url' => '#',
					'items' => [
						['label' => 'Laporan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/radiologi-order/laporan'],],

					],
				],

				[
					'label' => 'Konfigurasi',
					'icon' => 'fas fa-gears',
					'url' => '#',
					'items' => [
						['label' => 'List Layanan radiologi', 'icon' => 'fas  fa-file-text-o', 'url' => ['/radiologi'],],

					],
				],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],

			],
		];

		$lab = [
			'label' => 'Laboratorium',
			'icon' => 'fas  fa-flask',
			'url' => '#',
			'items' => [
				['label' => 'Pelayanan Lab', 'icon' => 'fas fa-money', 'url' => ['/laboratorium'],],
				[
					'label' => 'Laporan Lab',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Laporan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/laboratorium/laporan'],],

					],
				],
				[
					'label' => 'Konfigurasi',
					'icon' => 'fas  fa-cogs',
					'url' => '#',
					'items' => [
						['label' => 'List Layanan Lab', 'icon' => 'fas  fa-file-text-o', 'url' => ['/laboratorium-layanan'],],
						['label' => 'List Pemeriksaam Lab', 'icon' => 'fas  fa-file-text-o', 'url' => ['/laboratorium-pemeriksaan'],],

					],
				],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],
		];

		$dokter = [
			'label' => 'Dokter',
			'icon' => 'fas fa-user-md',
			'url' => '#',
			'items' => [
				['label' => 'Data Dokter', 'icon' => 'fas  fa-user-md', 'url' => ['/dokter'],],
			],
		];

		$utitity = [
			'label' => 'Master Data',
			'icon' => 'fas fa-expeditedssl',
			'url' => '#',
			'items' => [
				['label' => 'Data Poli', 'icon' => 'fas fa-h-square', 'url' => ['/poli'],],
				['label' => 'Data Kelas', 'icon' => 'fas  fa-user-md', 'url' => ['/ruangan-kelas'],],
				['label' => 'Data Ruangan', 'icon' => 'fas fa-users', 'url' => ['/ruangan'],],
			],
		];
		$sistem_informasi = [
			'label' => 'Sistem Informasi',
			'icon' => 'fas fa-expeditedssl',
			'url' => '#',
			'items' => [
				['label' => 'Sistem Informasi', 'icon' => 'fas fa-h-square', 'url' => ['/sistem-informasi'],],
			],
		];
		$laporan = [
			'label' => 'Laporan',
			'icon' => 'fas fa-expeditedssl',
			'url' => '#',
			'items' => [
				['label' => 'Laporan Penerimaan &  Jasa Dokter', 'icon' => 'fas fa-h-square', 'url' => ['/jasa'],],
				[
					'label' => 'Laporan Mutasi',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Mutasi Obat', 'icon' => 'fas fa-medkit', 'url' => ['/mutasi/obat'],],


					],
				],
				[
					'label' => 'Laporan Penerimaan',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Penerimaan Obat', 'icon' => 'fas fa-medkit', 'url' => ['/pjk/pjk-obat'],],
						['label' => 'Penerimaan Barang', 'icon' => 'fas fa-medkit', 'url' => ['/pjk/pjk-barang'],],


					],
				],
				['label' => 'Laporan Kunjungan Pasien', 'icon' => 'fas fa-h-square', 'url' => ['/jasa'],],
			],
		];
		$keperawatan = [
			'label' => 'Kebidanan',
			'icon' => 'fas fa-expeditedssl',
			'url' => '#',
			'items' => [
				['label' => 'List Pasien', 'icon' => 'fas fa-h-square', 'url' => ['/keperawatan'],],
				['label' => 'List Pasien Pulang', 'icon' => 'fas fa-h-square', 'url' => ['/keperawatan/list-pulang'],],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],
		];
		$gudang = [
			'label' => 'Gudang',
			'icon' => 'fas fa-expeditedssl',
			'url' => '#',
			'items' => [
				[
					'label' => 'Obat / Alkes',
					'icon' => 'fas  fa-medkit',
					'url' => '#',
					'items' => [
						['label' => 'Data Obat / Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat'],],
						//['label' => 'Daftar Permintaan Obat', 'icon' => 'fas  fa-file-text-o', 'url' => ['/permintaan-obat'],],
						['label' => 'Stok Opname Obat', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-stokopname'],],

					],
				],
				[
					'label' => 'Barang',
					'icon' => 'fas fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/data-barang'],],
						// 	['label' => 'Data Permintaan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/list-amprah'],],
						['label' => 'Stok Opname Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-stokopname'],],


					],
				],
				[
					'label' => 'Data Barang Dropping',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Obat Droping', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-droping'],],
						['label' => 'Obat Droping Transaksi', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-droping-transaksi'],],

					],
				],

				[
					'label' => 'Penerimaan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						[
							'label' => 'Penerimaan Alkes / Obat', 'icon' => 'fas  fa-file-text-o', 'url' => '',
							'items' => [
								['label' => 'Penerimaan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/barang-terima'],],
								['label' => 'Penerimaan Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/barang-terima-selesai'],],
								['label' => 'Rekap Penerimaan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pjk/pjk-obat'],],

							],
						],

						[
							'label' => 'Penerimaan Barang / ATK', 'icon' => 'fas  fa-file-text-o', 'url' => '',
							'items' => [
								['label' => 'Penerimaan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/barang-terima-atk'],],
								['label' => 'Penerimaan Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/barang-terima-atk-selesai'],],
								['label' => 'Rekap Penerimaan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pjk/pjk-barang'],],

							],
						],

					],
				],
				[
					'label' => 'Penyerahan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						[
							'label' => 'Penyerahan Alkes / Obat', 'icon' => 'fas  fa-file-text-o', 'url' => '',
							'items' => [
								['label' => 'Penyerahan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/amprah-gudangobat'],],
								['label' => 'Penyerahan Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/amprah-gudangobat/selesai'],],
								['label' => 'Rekap Penyerahan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/rekap-serah'],],

							],
						],

						[
							'label' => 'Penyerahan Barang / ATK', 'icon' => 'fas  fa-file-text-o', 'url' => '',
							'items' => [
								['label' => 'Penyerahan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/amprah-gudangatk'],],
								['label' => 'Penyerahan Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/amprah-gudangatk-selesai'],],
								['label' => 'Rekap Penyerahan', 'icon' => 'fas  fa-file-text-o', 'url' => ['/gudang/rekap-gudangatk'],],

							],
						],

					],
				],

				[
					'label' => 'Amprah Gudang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],


			],
		];
		$billing = [
			'label' => 'Billing',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [
				[
					'label' => 'Pembayaran Pasien',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Casa', 'icon' => 'fas  fa-file-text-o', 'url' => ['/billing'],],
						['label' => 'Pembayaran Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/billing/list-selesai'],],
						['label' => 'List Tarif', 'icon' => 'fas  fa-file-text-o', 'url' => ['/tarif'],],

					],
				],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan obat/alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan ATK', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-amprah'],],

					],
				],
			],
		];
		$farmasi = [
			'label' => 'Farmasi',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [
				['label' => 'Resep', 'icon' => 'fas fa-h-square', 'url' => ['/resep'],],
				['label' => 'Resep Umum', 'icon' => 'fas fa-h-square', 'url' => ['/resep-luar'],],
				['label' => 'Data Resep', 'icon' => 'fas fa-h-square', 'url' => ['/resep/list-resep'],],
				['label' => 'Stok Opname', 'icon' => 'fas fa-h-square', 'url' => ['/obat-stokopname'],],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],
		];
		$dukkes = [
			'label' => 'Dukkes',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],
		];
		$gizi = [
			'label' => 'Gizi',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-shopping-cart',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat / Alkes', 'icon' => 'fas fa-medkit', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas fa-cart-plus', 'url' => ['/barang-amprah'],],


					],
				],
			],
		];
		$kajangkes = [
			'label' => 'Kajangkes',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [

				[
					'label' => 'Data Barang, Obat & alkes',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Obat & Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat'],],
						['label' => 'Data Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/data-barang'],],

					],
				],
				[
					'label' => 'Rekap Permintaan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Rekap', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pengadaan/rekap'],],

					],
				],
				[
					'label' => 'Permintaan Ruangan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat & Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/jangkes/list-permintaan-obat'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-amprah/list-permintaan'],],
						['label' => 'Permintaan Barang Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-amprah/list-permintaan-setuju'],],
						['label' => 'Permintaan Obat Selesai', 'icon' => 'fas  fa-file-text-o', 'url' => ['/jangkes/list-permintaan-setuju'],],

					],
				],
				[
					'label' => 'Permintaan Jangkes',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat & Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-amprah'],],

					],
				],
			],
		];
		$pengadaan = [
			'label' => 'Pengadaan',
			'icon' => 'fas fa-money',
			'url' => '#',
			'items' => [
				[
					'label' => 'Data Barang, Obat & alkes',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Obat & Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat'],],
						['label' => 'Obat & Alkes Detail', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat/index-bacth'],],
						['label' => 'Data Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/data-barang'],],

					],
				],
				[
					'label' => 'Rekap Permintaan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Rekap', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pengadaan/rekap'],],

					],
				],
				[
					'label' => 'Data Barang Dropping',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Obat Droping', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-droping'],],
						['label' => 'Obat Droping Transaksi', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-droping-transaksi'],],

					],
				],

				[
					'label' => 'Penerimaan',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Penerimaan Obat / Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/penerimaan-barang'],],
						['label' => 'Penerimaan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-penerimaan'],],

					],
				],
				[
					'label' => 'Rekap Pembelian',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Rekap Pembelian', 'icon' => 'fas  fa-file-text-o', 'url' => ['/pengadaan/rekap-pembelian'],],

					],
				],
				[
					'label' => 'Permintaan Barang',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Permintaan Obat & Alkes', 'icon' => 'fas  fa-file-text-o', 'url' => ['/permintaan-obat/unit'],],
						['label' => 'Permintaan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/barang-amprah'],],

					],
				],
				[
					'label' => 'Data Lainnya',
					'icon' => 'fas  fa-money',
					'url' => '#',
					'items' => [
						['label' => 'Data Suplier', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-suplier'],],
						['label' => 'Data Satuan Barang', 'icon' => 'fas  fa-file-text-o', 'url' => ['/data-satuan'],],
						['label' => 'Data Satuan Obat', 'icon' => 'fas  fa-file-text-o', 'url' => ['/obat-satuan'],],

					],
				],
			],

		];

		?>
		<?php if (Yii::$app->user->identity->idpriv == 1) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$dokter,
						$pendaftaran,
						$spri,
						$rawatjalan,
						$ugd,
						$radiologi,
						$farmasi,
						$laporan,
						$lab,
						$utitity,
						$obat,
						$tindakan,
						$sistem_informasi,
						$user,
						$gudang,
						$keperawatan,
						$billing,
						$personel,
						$kajangkes,
						$pengadaan,
						$personel,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 2) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$sistem_informasi,
						$laporan,
					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 3) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$statistik,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 4) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$billing,
						$laporan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 5) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],

						$gudang,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 6) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$gizi,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 7) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$rawatjalan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 8) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$pendaftaran,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 9) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$billing,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 10) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$obat,
						$farmasi,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 11) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$keperawatan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 12) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$keperawatan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 13) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$ok,
						$permintaan

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 14) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$ugd,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 15) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$radiologi,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 16) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$lab,
						$pendaftaran,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 17) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$spri,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 18) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$rawatjalan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 22) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$permintaan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 24) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$dukkes,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 25) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$kajangkes,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 26) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$pengadaan,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 27) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$humas,

					],
				]
			) ?>
		<?php } else if (Yii::$app->user->identity->idpriv == 30) { ?>
			<?= dmstr\widgets\Menu::widget(
				[
					'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
					'items' => [
						['label' => 'Menu', 'options' => ['class' => 'header']],
						['label' => 'Dashboard', 'icon' => 'fas fa-hospital-o', 'url' => ['/'],],
						$tb,

					],
				]
			) ?>
		<?php } ?>
	</section>

</aside>