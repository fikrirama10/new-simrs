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
use yii\widgets\ActiveForm;
?>
<div class="row">
	<div class="col-sm-8">
		<div class="box">
			<div class="box-header with-border"><h4>Buat SPRI Manual</h4></div>
			<div class="box-body">
				<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">No RM</label>
					<div class="col-sm-3">
						<input type='text' name="RawatSpri[no_rm]" id='no_rm' class='form-control'>
					</div>
					<span class="col-sm-2 input-group-btn">
						<button type="button" id="show-rm" class="btn btn-info btn-sm btn-flat">Cek RM</button>
					</span>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div class="col-sm-8">
						
					</div>
				</div>
				<div id='ruangan-ajax'></div>
				
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<?php
$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowPasien = Url::to(['admisi/show-pasien']);
$this->registerJs("
	$('#admisi-spri').hide();
	$('#sep_pasien').hide();
	$('#show-rm').on('click',function(){
			$('#ruangan-ajax').hide();
			rm = $('#no_rm').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowPasien}',
				data: 'id='+rm,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {				
					$('#ruangan-ajax').show();
					$('#ruangan-ajax').animate({ scrollTop: 0 }, 200);
					$('#ruangan-ajax').html(data);
					
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