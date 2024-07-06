<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View ;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\TindakanKategori;
use common\models\RawatJenis;
use common\models\TarifKattindakan;
use common\models\Poli;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\RawatBayar;
use kartik\checkbox\CheckboxX;
$rawat_bayar = RawatBayar::find()->all();
/* @var $this yii\web\View */
/* @var $model common\models\Tarif */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-form">
	<div class='box box-body'>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_tarif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idkategori')->dropDownList(ArrayHelper::map(TindakanKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Jenis Tarif -'])->label('Jenis Tarif')?>
    <?= $form->field($model, 'idjenisrawat')->dropDownList(ArrayHelper::map(RawatJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Perawatan -'])->label('Jenis Perawatan')?>
    <?= $form->field($model, 'kat_tindakan')->dropDownList(ArrayHelper::map(TarifKattindakan::find()->all(), 'id', 'tindakan'),['prompt'=>'- Kategori Tarif -'])->label('Tarif Kategori')?>
    <?= $form->field($model, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->all(), 'id', 'poli'),['prompt'=>'- Poliklinik -'])->label('PoliKlinik')?>
    <?= $form->field($model, 'idruangan')->dropDownList(ArrayHelper::map(Ruangan::find()->all(), 'id', 'nama_ruangan'),['prompt'=>'- Ruangan Rawat -'])->label('Ruangan Rawat')?>
    <?= $form->field($model, 'idkelas')->dropDownList(ArrayHelper::map(RuanganKelas::find()->all(), 'id', 'kelas'),['prompt'=>'- Kelas Rawat -'])->label('Kelas Rawat')?>
	<?= $form->field($model, 'paket')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>
	
	</div>
	<div class='box box-body'>
		<table class='table table-bordered'>
			<tr>
				<td><?= $form->field($model, 'medis')->textInput(['value'=>0]) ?></td>				
				<td><?= $form->field($model, 'paramedis')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'petugas')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'apoteker')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'sewaalat')->textInput(['value'=>0]) ?></td>
			</tr>
			<tr>
				<td><?= $form->field($model, 'gizi')->textInput(['value'=>0]) ?></td>				
				<td><?= $form->field($model, 'bph')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'sewakamar')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'makanpasien')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'nova_t')->textInput(['value'=>0]) ?></td>
			</tr>
			<tr>
				<td><?= $form->field($model, 'laundry')->textInput(['value'=>0]) ?></td>		
				<td><?= $form->field($model, 'cs')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'opsrs')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'perekam_medis')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'radiografer')->textInput(['value'=>0]) ?></td>
			</tr>
			<tr>
				<td><?= $form->field($model, 'radiolog')->textInput(['value'=>0]) ?></td>		
				<td><?= $form->field($model, 'assisten')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'operator')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'ass_tim')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'dokter_anastesi')->textInput(['value'=>0]) ?></td>
			</tr>
			<tr>
				<td><?= $form->field($model, 'sewa_ok')->textInput(['value'=>0]) ?></td>		
				<td><?= $form->field($model, 'asisten_anastesi')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'cssd')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'bbm')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'ranmor')->textInput(['value'=>0]) ?></td>
			</tr>
			<tr>
				<td><?= $form->field($model, 'supir')->textInput(['value'=>0]) ?></td>		
				<td><?= $form->field($model, 'dokter')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'rs')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'harbang')->textInput(['value'=>0]) ?></td>
				<td><?= $form->field($model, 'atlm')->textInput(['value'=>0]) ?></td>
			</tr>
		</table>
	</div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
<?php
$urlShowAll = Url::to(['tarif/show-inputan']);
$this->registerJs("

	$('#tarif-idkategori').on('change',function(){
			id = $('#tarif-idkategori').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#tarif').show();
					$('#tarif').animate({ scrollTop: 0 }, 200);
					$('#tarif').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
	});  
	

", View::POS_READY);


?>