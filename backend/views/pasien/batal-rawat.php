<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;

use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;


$this->title = $rawat->id;
$this->params['breadcrumbs'][] = ['label' => 'Pasiens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="pasien-view">
	<div class='box box-primary'>
		<div class='box-header with-border'>
			<h3>Batal Berobat</h3>
		</div>
		<div class='box-body'>
	
			<div class='row'>
				<div class='col-md-3'>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
						],
					]) ?>
					<?= DetailView::widget([
						'model' => $rawat,
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
					<?= $form->field($rawat, 'keterangan')->textarea(['maxlength' => true,'rows'=>6 ,'required'=>true])->label('Alasan Batal Berobat') ?>
					<div class="form-group">
						<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
						<a href='<?= Url::to(['pasien/'.$model->id])?>' class='btn btn-warning'>Kembali</a>
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

