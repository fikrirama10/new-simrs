<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\checkbox\CheckboxX;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Ruangan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ruangans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="ruangan-view">
	<div class='row'>
		<div class='col-md-4'>
			<div class='box'>
				<div class='box-header with-border'><h3><?= $model->nama_ruangan ?></h3></div>
				<div class='box-body'>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'nama_ruangan',
							'kapasitas',
							'gender0.gender',
							'idkelas0.kelas',
							'idjenis0.ruangan_jenis',
							'keterangan',
						],
					]) ?>
				</div>
				<div class='box-footer with-border'>
					<a class='btn btn-warning' href='<?= Url::to(['/ruangan'])?>'>Kembali</a> 
					<a class='btn btn-success' href='<?= Url::to(['/ruangan/update-aplicares?id='.$model->id])?>'>Update aplicares</a>
				</div>
			</div>
		</div>
		<div class='col-md-8'>
			<div class='box'>
				<div class='box-header with-border'><h3>Bed</h3></div>
				<div class='box-body'>
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-bed">+ BED </button><hr>
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Kode Bed</th>
							<th>Status</th>
							<th>Terisi</th>
							<th>#</th>
						</tr>
						<?php $no=1; foreach($bed as $b){ ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $b->kodebed ?></td>
							<td>
								<?php if($b->status == 1){echo'Aktif';}else{echo'Non Aktif';} ?>
							</td>
							<td><?= $b->terisi ?></td>
							<td><a href='<?= Url::to(['ruangan-bed/update?id='.$b->id]) ?>' class='btn btn-primary btn-xs'>Edit</a></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
    
    

</div>
<div class="modal fade" id="modal-bed">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Tambah Bed</h4>
		</div>
		<div class="modal-body">
			<div class = "container-fluid">
				<div class='row'>
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($tambah_bed, 'idruangan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
				<?= $form->field($tambah_bed, 'idjenis')->hiddenInput(['maxlength' => true,'value'=>$model->idjenis])->label(false) ?>
				<input type='text' value='<?= $model->nama_ruangan ?>' readonly class='form-control'>
				<br>
					<?= $form->field($tambah_bed, 'bayi')->widget(CheckboxX::classname(), [
					'initInputType' => CheckboxX::INPUT_CHECKBOX,
					'autoLabel' => false
				])->label(false); ?>
				<?= $form->field($tambah_bed, 'status')->widget(CheckboxX::classname(), [
					'initInputType' => CheckboxX::INPUT_CHECKBOX,
					'autoLabel' => false
				])->label(false); ?>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			<?php ActiveForm::end(); ?>
		</div>
		</div>
	<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>