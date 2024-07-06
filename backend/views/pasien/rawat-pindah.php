<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatJenis;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\RawatBayar;
use common\models\Poli;
use yii\bootstrap\Modal;
use common\models\Rawat;
?>
<div class='row'>
	<div class="col-md-4">
		<div class="box">
			<div class="box-header"><h4> Data Pasien</h4></div>
			<div class="box-body">
				<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							[                                             
								'label' => 'Nama Pasien',
								'value' => Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan).'. '. $pasien->nama_pasien.' ('.$pasien->jenis_kelamin.')',
								'captionOptions' => ['tooltip' => 'Tooltip'], 
							],
							'tgllahir',
							'nohp',
							[                                                  // the owner name of the model
								'label' => 'Usia Pasien',
								'value' => $pasien->usia_tahun.'thn, ',            
								
								'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
							],
						],
						
				]) ?>
			</div>
		</div>
		<div class="box">
			<div class="box-header"><h4> Data Rawat</h4></div>
			<div class="box-body">
				<?= DetailView::widget([
						'model' => $rawat,
						'attributes' => [
							'idkunjungan',
							'idrawat',
							'ruangan.nama_ruangan',
							'kelas.kelas',
							'dokter.nama_dokter',
							'jenisrawat.jenis',
							'tglmasuk',
						],
						
					]) ?>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box box-body">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($pindah, 'idtujuan')->hiddenInput(['maxlength' => true,])->label(false) ?>
		<?= $form->field($pindah, 'idkelastujuan')->hiddenInput(['maxlength' => true,])->label(false) ?>
		<?= $form->field($pindah, 'idkelastujuan')->hiddenInput(['maxlength' => true,])->label(false) ?>
		<label>Tujuan Ruangan</label>
		<input type='text' class='form-control' placeholder='<?= $pindah->ruangantujuan->nama_ruangan ?>'  id="rawatpermintaanpindah-distole">
		<br>
		<hr>
		<?= $form->field($pindah, 'idkelastujuan')->dropDownList(ArrayHelper::map(RuanganKelas::find()->where(['ket'=>1])->all(), 'id', 'kelas'),['prompt'=>'- Kelas Rawat -','onchange'=>'$.get("'.Url::toRoute('pasien/list-ruangan/').'",{ id: $(this).val() }).done(function( data ) 
		{  $( "select#rawatpermintaanpindah-idtujuan" ).html( data );});		
		'])->label('Kelas Rawat')?>
		<?= $form->field($pindah, 'idtujuan')->dropDownList(ArrayHelper::map(Ruangan::find()->where(['id'=>0])->all(), 'id', 'nama_ruangan'),['prompt'=>'- Nama Ruangan -','required'=>true])->label('Nama Ruangan')?>
		
		<div class="input-group">
			<input type="text" readonly id="bed" name="bed" class="form-control">
			<a id="show-ruangan"  class="input-group-addon btn btn-success btn-sm" ><span class="fa fa-search" style="width: 20px;"></span>Cek Tempat Tidur</a>								
		</div>
		<?= $form->field($pindah, 'idbedtujuan')->textInput(['maxlength' => true,])->label(false) ?>
		<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>	</div>
		
		<div id='ruangan-ajax'></div>
		<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
		<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
<?php
$urlShowRuangan = Url::to(['pasien/show-ruangan-pindah']);
$this->registerJs("
	$('#show-ruangan').on('click',function(){
			$('#ruangan-ajax').hide();
			ruangan = $('#rawatpermintaanpindah-idtujuan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowRuangan}',
				data: 'id='+ruangan,
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