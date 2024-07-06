<table class='table table-bordered'>
	<tr>
		<th>No Register</th>
		<th>Tgl Daftar</th>
		<th>Nama Peserta</th>
		<th>Tgl Vaksin</th>
		<th>Vaksin Ke</th>
	</tr>
	<?php if($kuota['metadata']['code'] == 404 ){ ?>
	<tr>
		<th colspan=5>Data tidak ditemukan</th>
	</tr>
	<?php }else{ ?>
    <tr>
		<td><?= $kuota['response']['noregister'] ?></td>
		<td><?= $kuota['response']['tgldaftar'] ?></td>
		<td><?= $kuota['response']['nama'] ?></td>
		<td><?= $kuota['response']['tglvaksin'] ?></td>
		<td><?= $kuota['response']['vaksin'] ?></td>
	</tr>
	<?php } ?>
</table>
<table class='table table-bordered'>
    <tr>
        <th colspan=2>
            	<div class="alert alert-success" role="alert">
                  <b>SILAHKAN DOWNLOAD & PRINT FORM BERIKUT DAN DI BAWA PADA SAAT JADWAL VAKSINASI</b>
                </div>
        </th>
    </tr>
    <tr>
        <th>Form SURAT PERSETUJUAN VAKSINASI COVID</th>
        <th><a href='https://daftarsulaiman.rsausulaiman.com/frontend/images/SURAT PERSETUJUAN VAKSINASI COVID.pdf' id="show-all" class="btn btn-success" download ><span class="fa fa-download" style="width: 20px;"></span>download</a></th>
    </tr>
        <tr>
        <th>Form KARTU KENDALI PELAYANAN VAKSINASI COVID</th>
        <th><a href='https://daftarsulaiman.rsausulaiman.com/frontend/images/KARTU KENDALI PELAYANAN VAKSINASI COVID.pdf' id="show-all" class="btn btn-warning" download ><span class="fa fa-download" style="width: 20px;"></span>download</a></th>
    </tr>
</table>