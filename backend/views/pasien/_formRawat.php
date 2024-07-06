<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;

?>

<form class='form-horizontal'>
	<div class="form-group">
		<label class="col-sm-2 control-label">No Bpjs</label>
		<div class="col-sm-3">
			<input type='text' readonly value='<?= $model->no_bpjs?>' id='no_rm<?= $lr->id?>' class='form-control'>
			<input type='hidden' readonly value='<?= $lr->id?>' id='idrawat<?= $lr->id?>' class='form-control'>
			<input type='hidden' readonly value='<?= $lr->idpoli?>' id='poli<?= $lr->id?>' class='form-control'>
		</div>
		<div class="col-sm-3">
			<select id='faskes<?= $lr->id?>' class='form-control'>
				<option value='1'> Faskes 1 </option>
				<option value='2'> Faskes 2 </option>
			</select>
		</div>
		<span class="col-sm-2 input-group-btn">
			<button type="button" id="show-rm<?= $lr->id ?>" class="btn btn-info btn-sm btn-flat">Cari</button>
		</span>
	</div>
	<div id='rujukan-ajax<?= $lr->id ?>'></div>
</form>
<?php
$urlShowPasien = Url::to(['pasien/show-listrujukan']);
$this->registerJs("
	$('#show-rm{$lr->id}').on('click',function(){
			$('#rujukan-ajax{$lr->id}').hide();
			rm = $('#no_rm{$lr->id}').val();
			idrawat = $('#idrawat{$lr->id}').val();
			faskes = $('#faskes{$lr->id}').val();
			poli = $('#poli{$lr->id}').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowPasien}',
				data: 'id='+rm+'&idrawat='+idrawat+'&faskes='+faskes+'&poli='+poli,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {				
					$('#rujukan-ajax{$lr->id}').show();
					$('#rujukan-ajax{$lr->id}').animate({ scrollTop: 0 }, 200);
					$('#rujukan-ajax{$lr->id}').html(data);
					
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