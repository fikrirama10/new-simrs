<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
$this->title = 'SEP';
$this->params['breadcrumbs'][] = ['label' => 'Pasien', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

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
<br>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien </h4></div>
			<div class='box-body'>
				<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
							'tgllahir',
							'tempat_lahir',
							'nohp',
							'usia_tahun',
							'kepesertaan_bpjs',
							'pekerjaan.pekerjaan',
						],
					]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<?php if($cek < 1){ ?>
			<?= $this->render('_seprajalbaru',[
				'rujukan'=>$rujukan,
				'cek'=>$cek,
				'rawat'=>$rawat,
				'pasien'=>$pasien,
				'jaminan'=>$jaminan,
			]) ?>
		<?php }else if($rawat->poli->kode != $rujukan['rujukan']['poliRujukan']['kode']){ ?>
		<?= $this->render('_seprajalrujuk',[
				'rujukan'=>$rujukan,
				'cek'=>$cek,
				'rawat'=>$rawat,
				'pasien'=>$pasien,
				'jaminan'=>$jaminan,
			]) ?>
		<?php }else{ ?>
		<?= $this->render('_seprajalkontrol',[
			'rujukan'=>$rujukan,
			'cek'=>$cek,
			'rawat'=>$rawat,
			'pasien'=>$pasien,
			'jaminan'=>$jaminan,
		]) ?>
		<?php } ?>
		
	</div>
</div>

<?php


Modal::begin([
	'id' => 'mdPlus',
	'header' => '<h3>Buat Surat rencana kontrol</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_kontrol',['pasien'=>$pasien,'rawat'=>$rawat]).'</div>';
 
Modal::end();


Modal::begin([
	'id' => 'mdPasien',
	'header' => '<h5>Data Penjaminan an. '.$pasien->nama_pasien.'</h5>',
	'footer' => '  <button type="button" id="btnKasusKLLBaru" class="btn btn-primary pull-left"> Kasus KLL Baru</button> <button type="button" id="btnTutupJaminan" class="btn btn-danger pull-right"> Batal</button>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_form-kecelakaan',['nobpjs'=>$pasien->no_bpjs,'tgl'=>date('Y-m-d',strtotime($rawat->tglmasuk))]).'</div>';
 
Modal::end();

$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowRuangan = Url::to(['admisi/show-ruangan']);
$urlKab = Url::to(['admisi/get-kab']);
$urlKec = Url::to(['admisi/get-kec']);
$this->registerJs("
	
	$('#assesmentPel').hide();
	$('#flagProcedure').hide();
	$('#confirm22').hide();
	$('#suplesi').hide();
	$('#btnTutupJaminan').on('click', function(event){
		 $('#mdPasien').modal('hide');
		 $('#kecelakaan').val('');
		 $('#jaminan_value').val(0);
	});
	$('#btnKasusKLLBaru').on('click', function(event){
		 $('#mdPasien').modal('hide');
		 $('#suplesi').show();
		 $('#suplesi_bpjs').val(0);
	});
	$('#tujuanKunj').on('change', function(event){
		tujuan = $('#tujuanKunj').val();
		if(tujuan == 1){
			$('#flagProcedure').show();
			$('#assesmentPel').hide();
		}else if(tujuan == 2){
			$('#flagProcedure').hide();
			$('#assesmentPel').show();
		}else{
			$('#assesmentPel').hide();
			$('#flagProcedure').hide();
		}
	});
	$('#provinsi').on('change', function(event){
		prov = $('#provinsi').val();
		$('#provinsi_value').val(prov);
	});
	$('#idkabupaten').on('change', function(event){
		prov = $('#idkabupaten').val();
		$('#idkab_value').val(prov);
	});
	$('#idkecamatan').on('change', function(event){
		prov = $('#idkecamatan').val();
		$('#idkec_value').val(prov);
	});
	$('#tglkejadian').on('change', function(event){
		prov = $('#tglkejadian').val();
		$('#tglkejadian_value').val(prov);
	});

	$('#kecelakaan').on('change', function(event){
		kecelakaan = $('#kecelakaan').val();
		if(kecelakaan == 1 || kecelakaan == 2){
			 $('#mdPasien').modal('show');
			 $('#suplesi').hide();
			 $('#jaminan_value').val(kecelakaan);
		}else if(kecelakaan == 3 ){
			$('#suplesi').show();
			$('#jaminan_value').val(kecelakaan);
		}else{
			$('#suplesi').hide();
		}
	});

	
	$('#provinsi').on('change',function(){
			provinsi = $('#provinsi').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlKab}',
				data: 'kode='+provinsi,
				
				success: function (data) {
					$('#idkabupaten').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#idkabupaten').on('change',function(){
			idkabupaten = $('#idkabupaten').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKec}',
				data: 'kode='+idkabupaten,
				
				success: function (data) {
					$('#idkecamatan').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	
	$('#confirm2').on('click', function(event){
		diagnosa = $('#rawat-icdx').val();
		if(diagnosa == ''){
			alert('Kode Diagnosa kosong');
			event.preventDefault();
		}else{			
			age = confirm('Yakin Untuk menyimpan data');
			if(age == true){
				 return true;
			} else {
				return true;
			}				
		}
		
	});
", View::POS_READY);
?>