<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\RawatBayar;
use mdm\widgets\TabularInput;
use common\models\LabHasil;
use common\models\Rawat;
use yii\widgets\DetailView;
$rawat = Rawat::find()->where(['id'=>$model->hasil->idrawat])->one();
?>
<div class="box">
	<div class="box-header"><h3>Pemeriksaan <?= $model->pemeriksaan->nama_pemeriksaan?></h3></div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-4">
				<?= DetailView::widget([
					'model' => $rawat,
					'attributes' => [
						'idkunjungan',
						'poli.poli',
						'dokter.nama_dokter',
						'pasien.nama_pasien',
						'no_rm',
						'pasien.usia_tahun',
						'pasien.jenis_kelamin',
					],
						
				]) ?>
			</div>
			<div class="col-md-8">
				<?php if($model->status == 2){ 
					
					$no=1;
				?>
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Item</th>
							<th>Hasil</th>
							<th>Satuan</th>
							<th>Nilai Rujukan</th>
							<th>Edit</th>
						</tr>
						<?php foreach($labHasil as $lh):?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $lh->item ?></td>
							<td><?= $lh->hasil ?></td>
							<td><?= $lh->satuan ?></td>
							<td><?= $lh->nilai_rujukan ?></td>
							<td><a class='btn btn-xs btn-warning' data-toggle="modal" data-target="#hasilModal<?= $lh->id?>"><i class='fa fa-pencil'></i></a></td>
						</tr>
						<?php endforeach;?>
						<tr>
							<td colspan=6><a href='<?= Url::to(['laboratorium/view?id='.$model->hasil->idrawat])?>' class='btn btn-success'>Selesai</a></td>
						</tr>
					</table>
				
				<?php }else{ ?>
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($model, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Bayar -','required'=>true])->label('Jenis Bayar')?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th colspan=4>Input Hasil Laboratorium : <?= $model->hasil->labid?></th>
						</tr>
						</thead>
						<thead>
							<tr>
							<th class="col-lg-3">Nama</th>
							<th class="col-lg-3">Nilai</th>
							<th class="col-lg-3">Satuan</th>
							<th class="col-lg-3">Nilai Normal</th>
							</tr>
						</thead>
						<?=
						  TabularInput::widget([
							'id' => 'detail-grid',
							'model' => \common\models\LabHasil::className(),  // <---- ini
							'allModels' => $aHasil,  // <---- dan ini
							'options' => ['tag' => 'tbody'],
							'itemOptions' => ['tag' => 'tr'],
							'itemView' => '_itemHasil',
						])
						?>
						<tr>
							<td colspan=4><?= Html::submitButton('Create',['class' => 'btn btn-success' ,'id'=>'confirm']) ?></td>
						</tr>
					</table>
					
				<?php ActiveForm::end(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php foreach($labHasil as $lh):?>
	<div class="modal fade bd-example-modal-lg" tabindex="-1" id="hasilModal<?= $lh->id?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><?= $lh->item?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
					<?php $form = ActiveForm::begin(['action' => ['/laboratorium/input-hasil?iditem='.$lh->id.'&id='.$model->id],]); ?>
					<?= $form->field($lh, 'hasil')->textInput(['maxlength' => true]) ?>
					<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
					<?php ActiveForm::end(); ?>
			  </div>
		  </div>
		</div>
	</div>
<?php endforeach;?>
<?php
$this->registerJs("

	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data??');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	      
", View::POS_READY);
?>