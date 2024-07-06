<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\Rawat;
use common\models\RawatBayar;
use common\models\KategoriPenyakit;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
?>
<div class="row" id="divJadwal" style="">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-calendar"></i>
				<h3 class="box-title">Jadwal Praktek dan Sarana Rumah Sakit Rujukan</h3>
			</div>
			<div class="box-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-3 control-label">PPK Rujuk</label>
						<div class="col-md-4 col-sm-4 col-xs-4">
								<?= Select2::widget([
									'name' => 'txtnmppkjadwal',
									'id'=>'dataPpk',
									'options' => ['placeholder' => 'ketik kode atau nama ppk minimal 3 karakter'],
									'pluginOptions' => [
									'allowClear' => true,
									'minimumInputLength' => 3,
									'ajax' => [
									'url' => "https://new-simrs.rsausulaiman.com/dashboard/rest/faskes",
									'dataType' => 'json',
									'delay' => 250,
									'data' => new JsExpression('function(params) { return {q:params.term};}'),
									'processResults' => new JsExpression($resultsJs),
									'cache' => true
									],
									'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
									'templateResult' => new JsExpression('formatRepo'),
									'templateSelection' => new JsExpression('formatRepoSelection'),
									],
								]);?>
						</div>
						
						<button type="button" id="btnApprovePPK" class="btn btn-success" >
							<span><i class="fa fa-check"></i></span> Pilih PPK Rujuk
						</button>
					</div>
					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rencana Rujukan</label>
						<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="input-group date">
								<input type="date" class="form-control datepicker_rencana" id="txttglrencanarujukan" placeholder="yyyy-MM-dd" maxlength="10">
								
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3 col-sm-3 col-xs-12"></div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<button class="btn btn-primary" id="btnCariJadwal" type="button"> <i class="fa fa-search"></i> Cari</button>
							<button class="btn btn-danger" id="btnBatalJadwal" type="button"> <i class="fa fa-undo"></i> Batal</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<input type='hidden' id='nameFaskes'>
	<input type='hidden' id='kodeFaskes'>
<div id='loading' style='display:none;'>
				<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
			</div>
<div class='col-md-12'>
	<div id='fasilitas'></div>
</div>

</div>	
<?php 
$urlShowAll = Url::to(['rujukan-faskes/get-faskes']);
$urlShowFasilitas = Url::to(['rujukan-faskes/get-fasilitas']);
$this->registerJs("
	$('#btnApprovePPK').on('click',function(e) {
		faskes = $('#dataPpk').val();
		arrfaskes = faskes.split(',');
		kode = arrfaskes[0];
		nama = arrfaskes[1];
		$('#nameFaskes').val(nama);
		$('#kodeFaskes').val(kode);
		kode_faskes = $('#kodeFaskes').val();
		nama_faskes = $('#nameFaskes').val();
		$('#tglrencanaRujuk').val($('#txttglrencanarujukan').val());
		$('#txtnmppkdirujuk').val(nama_faskes);
		$('#txtkdppkdirujuk').val(kode_faskes);
		$('#utama').show();
		$('#kedua').hide();
		$('#fasilitas').hide();
	});
	$('#btnCariJadwal').on('click',function(e) {
		$('#fasilitas').hide();
		faskes = $('#dataPpk').val();
		arrfaskes = faskes.split(',');
		kode = arrfaskes[0];
		nama = arrfaskes[1];
		$('#nameFaskes').val(nama);
		$('#kodeFaskes').val(kode);
		tgl = $('#txttglrencanarujukan').val();
		if(kode == ''){
			alert('silahkan pilih faskes');
			event.preventDefault();
		}else if(tgl == ''){
			alert('Tgl rencana kunjungan kosong');
			event.preventDefault();
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowFasilitas}',
				data: 'id='+kode+'&tgl='+tgl,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#fasilitas').show();
					$('#fasilitas').animate({ scrollTop: 0 }, 200);
					$('#fasilitas').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
				
			});
		}
	});
", View::POS_READY);
?>