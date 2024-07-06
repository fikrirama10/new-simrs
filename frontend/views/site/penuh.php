<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\date\DatePicker;
$this->title = 'Daftar Vaksin';
$tgl = date('Y-m-d');
$hari = date('N',strtotime($tgl));
?>
<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
<br>
Pendaftaran Vaksin RSAU LANUD SULAIMAN
<hr>
	<div class="alert alert-danger" role="alert">
		<h4 class="alert-heading">Mohon maaf</h4>
		
		Kuota peserta vaksin gratis sudah penuh , Pendaftaran dibuka kembali hari Sabtu
	
	</div>
	<hr>
</div>
