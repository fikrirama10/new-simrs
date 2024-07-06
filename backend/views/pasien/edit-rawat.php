<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RawatBayar;
use yii\helpers\ArrayHelper;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pasiens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="pasien-view">
	<div class='box box-primary'>
		<div class='box-header with-border'>
			<h3>Edit Berobat</h3>
		</div>
		<div class='box-body'>
	
			<div class='row'>
				<div class='col-md-3'>
					<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
						],
					]) ?>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'idrawat',
							'idkunjungan',
							'poli.poli',
							'tglmasuk',
							'bayar.bayar',
						],
					]) ?>
					
					
				</div>
				<div class='col-md-9'>
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($model, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label('Penanggung',['class'=>'label-class'])->label()?>
					<?= $form->field($model, 'no_sep')->textInput(['maxlength' => true])?>
					<?= $form->field($model, 'anggota')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
					<?= $form->field($model, 'keterangan')->textArea()?>
					<div class="form-group">
						<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
						<a href='<?= Url::to(['pasien/'.$pasien->id])?>' class='btn btn-warning'>Kembali</a>
					</div>
				<?php ActiveForm::end(); ?>
					<br>
					
				</div>
			</div>
		</div>
	</div>

	
</div>

<?php
$this->registerJs("

	$('#confirm').on('click', function(event){
		age = confirm('Konfirmasi Untuk Membatalkan Kunjungan berobat');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	

", View::POS_READY);
?>

