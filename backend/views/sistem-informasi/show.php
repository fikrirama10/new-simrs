<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
?>
<div class='row'>
<div class='col-md-12'>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Judul</th>
		<th>Lihat</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Macam Penyakit / Jumlah Penderita</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/macam-penyakit?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>2</td>
		<td>Pelayanan Rawat Jalan</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/pelayanan-rajal?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>3</td>
		<td>Pelayanan Rawat Inap</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/pelayanan-ranap?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>4</td>
		<td>Kelahiran / Kematian Ibu Melahirkan</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kelahiran?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>5</td>
		<td>Pemberantasan Penyakit TBC</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>6</td>
		<td>Pengunjung dan kunjungan Poliklinik Gigi</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kunjungan-gigi?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>7</td>
		<td>Pengunjung dan kunjungan Poliklinik</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kunjungan-poli?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>8</td>
		<td>Kasus Penyakit Gigi dan Mulut</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/penyakit-gilut?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>9</td>
		<td>Pengobatan Penyakit Gigi & Mulut</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>10</td>
		<td>Pelayanan UGD</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kunjungan-ugd?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>11</td>
		<td>Kegiatan UGD</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kegiatan-ugd?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>12</td>
		<td>Pemeriksaan Laboratorium Klinik</td>
		<td><a target='_blank' href='<?= Url::to(['sistem-informasi/kegiatan-LAB?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>13</td>
		<td>Kegiatan Radiologi / USG</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>14</td>
		<td>Penerimaan Resep</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>15</td>
		<td>Kehilangan Hari Kerja</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>16</td>
		<td>Uji Kesehatan</td>
		<td><a class='btn btn-info'>Lihat</a></td>
	</tr>
</table>
</div>
</div>