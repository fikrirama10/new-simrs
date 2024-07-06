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
<?php if($hari != 4 && $hari != 7 && $hari != 1){ ?>
<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
<br>
Pendaftaran Vaksin RSAU LANUD SULAIMAN
<hr>
	<div class="alert alert-warning" role="alert">
		<h4 class="alert-heading">Perhatian !!</h4>
		
		Pendaftaran Vaksin Belum di Buka Kegiatan Vaksin Dilaksanakan di Hari Rabu , Kamis & Jumat Link Pendaftaran  di buka dari Hari SABTU - SENIN	
	
	</div>
	<hr>
</div>

<?php }else{ ?>
<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
<br>
<h4>Pendaftaran Vaksin RSAU LANUD SULAIMAN</h4>
<hr>
	<div class="alert alert-success" role="alert">
		<h4 class="alert-heading">Perhatian !!</h4>
		
		Kegiatan Vaksin Dilaksanakan di Hari Rabu , Kamis & Jumat .
	    
	</div>
	<div style='background:#8500ff; color:#fff;' class="alert alert-primary" role="alert">
        <?php if($hari == 1){echo'Pendaftaran untuk vaksinasi hari Jumat';}else if($hari == 6){echo'Pendaftaran untuk vaksinasi hari Rabu';}else if($hari == 7){echo'Pendaftaran untuk vaksinasi hari Kamis';} ?>
    </div>
	<div style='background:#ff7600; color:#fff;' class="alert alert-primary" role="alert">
         Kuota Vaksin hanya untuk 100 orang per hari
    </div>

	<hr>
		<?php $form = ActiveForm::begin(); ?>
		<div class='row'>
			<div class='col-md-5'>
			<label>Pilih Tgl Vaksin</label>
			<?=	$form->field($model, 'tglvaksin')->widget(DatePicker::classname(),[
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
							//'autoclose'=>true,
							'format' => 'yyyy-mm-dd',
							'required'=>true,
							'todayHighlight' => true,
							 'autoclose'=>true,
							'startDate' => $tgl,
						]
					])->label(false);?>
			<br>
			</div>
			<div class='col-md-2'>
			<label style='color:#fff;'>`</label>
			<br>
			<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cari</a></div>
			
		</div>
		<div class='row'>
			<div class='col-md-8'>
				<div id='pasien-ajax'></div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
</div>

<?php } ?>
<?php
$urlShowAll = Url::to(['site/show-vaksin']);
$this->registerJs("
	
	$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			tgl = $('#vaksin-tglvaksin').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'tgl='+tgl,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').html(data);
					 $('#vaksin-tglvaksin').attr('disabled', true);
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