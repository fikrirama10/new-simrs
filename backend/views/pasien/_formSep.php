<?php 
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use kartik\select2\Select2;
	use kartik\date\DatePicker;
	use yii\web\JsExpression;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use common\models\JenisDiagnosa;
	use common\models\Dokter;
	use common\models\RawatBayar;
	use yii\web\View;
	use common\models\Poli;
	use common\models\Kamar;
	use common\models\DokterJadwal;
	use common\models\DokterKuota;
	use yii\bootstrap\Modal;
	use kartik\checkbox\CheckboxX;
?>
<table class='table'>
	<tr>
		<th>No BPJS</th>
		<td>
			<div class="input-group">
				<input type="text" readonly id="pasien-bpjs"  class="form-control" value='<?= $rawat->pasien->no_bpjs?>'>
				<a id="show-rujukan" class="input-group-addon btn btn-success btn-sm" ><span class="fa fa-search"></span> cek</a>								
			</div>
		</td>
		<td width=400></td>
	</tr>
	<tr>
		<div id='show-bpjs'></div>
	</tr>
</table>
<?php
$urlShowRujukan = Url::to(['pasien/show-rujukansep']);
$this->registerJs("

	$('#show-rujukan').on('click',function(){
			$('#show-bpjs').hide();
			pasien-bpjs = $('#rawat-idruangan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowRujukan}',
				data: 'id='+ruangan,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#show-bpjs').show();
					$('#show-bpjs').animate({ scrollTop: 0 }, 200);
					$('#show-bpjs').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>