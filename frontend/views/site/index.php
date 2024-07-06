<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Pendaftaran Online';
?>
<div class="site-index">
		<!-- <div class="alert alert-success" role="alert">
		<strong>Untuk sementara pendaftaran online hanya berlaku untuk pasien BPJS</strong>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div> -->
	<br>
	<br>
	<center><img width='200px' src='https://rsausulaiman.com/config/img/logors.png'></center>
	
    <div class="jumbotron">
        <h3>SELAMAT DATANG DI WEBSITE PENDAFTARAN ONLINE </h3>
        <h1>RSAU LANUD SULAIMAN</h1>

		<hr>
        <p>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['site/daftar'])?>">Daftar Rawat Jalan</a>
			<a class="btn btn-lg btn-primary" href="<?= Url::to(['site/vaksin'])?>">Daftar Vaksin</a>
		</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
               <center> <h2>Perhatian</h2>

                <p style='font-size:20px;'>Pendaftaran pelayanan rawat jalan secara online hanya berlaku bagi pasien
					lama yang sudah memiliki nomer rekamedis atau pasien yang sudah terdaftar
					secara langsung di RSAU LANUD SULAIMAN</p></center>

               
            </div>
           
        </div>

    </div>
</div>
