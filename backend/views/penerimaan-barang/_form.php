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
use common\models\ObatSuplier;
$suplier = ObatSuplier::find()->all();
$bayar = RawatBayar::find()->all();
?>

<div class="penerimaan-barang-form">
	<div class="row">
		<div class="col-md-6">
			<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
			<div class="box">
				<div class="box-header with-border"><h3>Penerimaan Barang</h3></div>
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">No Faktur</label>
						<div class="col-sm-6">
							<input type='text' required name="PenerimaanBarang[no_faktur]" id='up' class='form-control'>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Suplier</label>
						<div class="col-sm-6">
							<select required name="PenerimaanBarang[idsuplier]" id='up' class='form-control'>
								<option>--Pilih Suplier--</option>
								<?php foreach($suplier as $s): ?>
								<option value='<?= $s->id?>'><?= $s->suplier ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Tgl Faktur</label>
						<div class="col-sm-6">
							<input type='date' name="PenerimaanBarang[tgl_faktur]" id='up' class='form-control'>
						</div>
					</div>
					
				</div>
				<div class="box-footer with-border">
						<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
		<div class="col-md-6"></div>
	</div>
</div>
<?php
$urlShowUp = Url::to(['penerimaan-barang/show-up']);
$this->registerJs("
	$('#show-up').on('click',function(){
			$('#up-ajax').hide();
			up = $('#up').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowUp}',
				data: 'id='+up,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {				
					$('#up-ajax').show();
					$('#up-ajax').animate({ scrollTop: 0 }, 200);
					$('#up-ajax').html(data);
					
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