<?php
use yii\helpers\Url;
?>
<h4>Rincian Pendapatan</h4>
<table class='table table-bordered'>
	<tr>
		<th width=500>Nama Laporan</th>
		<th>Lihat</th>
	</tr>
	<tr>
		<td>Laporan Rincian Pendapatan</td>
		<td><a target='_blank' href='<?= Url::to(['jasa/pendapatan'])?>' class='btn btn-success'>Lihat</a></td>
	</tr>
	<tr>
		<td>Laporan Rincian Pendapatan Ruangan</td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	<tr>
		<td>Laporan Rincian Pendapatan Farmasi </td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	
</table>
<h4>Rincian Jasa Dokter</h4>
<table class='table table-bordered'>
	<tr>
		<th width=500>Nama Laporan</th>
		<th>Lihat</th>
	</tr>
	<tr>
		<td>Laporan Jasa Dokter Spesialis</td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	<tr>
		<td>Laporan Tindakan Dokter Spesialis</td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	<tr>
		<td>Laporan Jasa Dokter UGD</td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	<tr>
		<td>Laporan Tindakan Dokter UGD</td>
		<td><a class='btn btn-success'>Lihat</a></td>
	</tr>
	
</table>