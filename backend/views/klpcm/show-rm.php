<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\checkbox\CheckboxX;
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
<table class='table table-stripped' style='font-size:10px;'>
	<tr>
		<th width=200>Nama Pasien</th>
		<th>:</th>
		<td width=250><?= $pasien->nama_pasien ?></td>
		<th width=200>Usia Pasien</th>
		<th width=10>:</th>
		<td width=100><?= $pasien->usia_tahun ?>th</td>
	</tr>
	<tr>
		<th width=200>No RM</th>
		<th width=10>:</th>
		<td><?= $pasien->no_rm ?></td>
		<th width=200>Tgl.Lahir</th>
		<th  width=10>:</th>
		<td><?= $pasien->tgllahir ?></td>
	</tr>
</table>
<div id='table_rawat'>
<table class='table table-border' style='font-size:10px;'>
	<tr>
		<th>Id Rawat</th>
		<th>Jenis Rawat</th>
		<th>Poli</th>
		<th>Dokter</th>
		<th>Tgl</th>
		<th>Icd 10</th>
	</tr>
	<?php foreach($rawat as $r): ?>
	<tr>
		<td><a class='btn btn-default btn-xs' id='btn<?= $r->id?>'><?= $r->idrawat ?></a><input type='hidden' value='<?= $r->id?>' id='input<?= $r->id?>'></td>
		<td><?= $r->jenisrawat->jenis ?></td>
		<td><?= $r->poli->poli ?></td>
		<td><?= $r->dokter->nama_dokter ?></td>
		<td><?= $r->tglmasuk ?></td>
		<td><?= $r->icdx ?></td>
	</tr>
	<?php 			
					$urlGet = Url::to(['klpcm/get-rawat']);
					$this->registerJs("
					
					$('#btn{$r->id}').on('click',function(){
						id = $('#input{$r->id}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data != null){
									$('#icdx').show();
									$('#data-dokter').hide();
									$('#table_rawat').hide();
									$('#admisi-ranap').show();
									var res = JSON.parse(JSON.stringify(data));
									
									$('#idrawat').val(res.idrawat);
									$('#diagnosa').val(res.icdx);
									$('#klpcm-tgl_kunjungan').val(res.tglmasuk);
									$('#klpcm-iddokter').val(res.iddokter);
									$('#klpcm-idrawat').val(res.id);
									$('#klpcm-no_rm').val(res.no_rm);
									$('#klpcm-idjenisrawat').val(res.idjenisrawat);
									
									
									
									//$('#transaksidetail-harga-disp').val(format_money(parseInt(harga),''));
									// console.log(kode +' '+ idstok);
								}else{
									alert('data tidak ditemukan');
								}
							},
							error: function (exception) {
								alert(exception);
							}
						});	
					}) ;


					", View::POS_READY);

					?>
	<?php endforeach; ?>
</table>
</div>
<hr>
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
<div class="form-group">
	<label class="col-sm-4 control-label">Id Rawat</label>
	<div class="col-sm-4">
		<input type='text' readonly  id='idrawat' class='form-control'>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Diagnosa</label>
	<div class="col-sm-4">
		<input type='text' readonly id='diagnosa' class='form-control'>
	</div>
</div>
     
