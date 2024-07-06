<?php
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class='box box-body'>
	<div class='row'>
		<div class='col-md-4'>
			<label>Tgl Awal</label>
			<input type='date' id='awal' class='form-control'>
		</div>
		<div class='col-md-4'>
			<label>Tgl Akhir</label>
			<input type='date' id='akhir' class='form-control'>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-12'>
			<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
			<div id='kunjunganAjax'></div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>

<?php 
$urlKunjungan = Url::to(['pasien/show-kunjungan']);
$this->registerJs("
	$('#akhir').on('change',function(e) {
		awal = $('#awal').val();
		akhir = $('#akhir').val();
		nokartu = '{$pasien->no_bpjs}';
		idrawat = '{$rawat->id}';
		$.ajax({
			type: 'GET',
			url: '{$urlKunjungan}',
			data: 'awal='+awal+'&akhir='+akhir+'&nokartu='+nokartu+'&idrawat='+idrawat,
			
			success: function (data) {
				$('#kunjunganAjax').html(data);
				
				console.log(data);
				
			},
			
		});
	});
", View::POS_READY);

?>