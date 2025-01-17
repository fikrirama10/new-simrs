<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\ObatSuplier;
use common\models\ObatKategori;
use yii\helpers\ArrayHelper;
use common\models\RawatBayar;
use kartik\date\DatePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\ObatDroping */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Dropings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<hr>
<div class="obat-droping-view">
<div class="box">
	<div class="box-header with-border">Data Obat</div>
	<div class="box-body">
		 <?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'nama_obat',
				'jenis.jenis',
				'satuan.satuan',
			],
		]) ?>
	</div>
	<div class="box-footer"></div>
</div>
<div class="box">
	<div class="box-header with-border"></div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<a data-toggle="modal" data-target="#batchModal" class="btn btn-app">
					<span class="badge bg-yellow">+</span>
					<i class="fa fa-barcode"></i> Tambah Batch 
				</a>
				<a href=<?= Url::to(['/obat-droping/update?id='.$model->id])?>' class="btn btn-app">
					<span class="badge bg-purple"><i class="fa fa-edit"></i></span>
					<i class="fa fa-edit"></i> Edit
				</a>
				<a href='<?= Url::to(['/obat-suplier/create?id='.$model->id])?>' class="btn btn-app">
					<span class="badge bg-red"><i class="fa fa-inbox"></i></span>
					<i class="fa fa-truck"></i> Tambah Suplier
				</a>
				<a href='<?= Url::to(['/obat/kartu-stok?id='.$model->id.'&asal='. Yii::$app->user->identity->userdetail->idgudang])?>' class="btn btn-app">
					<span class="badge bg-green"><i class="fa fa-cube"></i></span>
					<i class="fa fa-cube"></i> Kartu Stok
				</a>
				<a  href='<?= Url::to(['/obat/mutasi-stok?id='.$model->id.'&asal='. Yii::$app->user->identity->userdetail->idgudang])?>' class="btn btn-app">
					<span class="badge bg-info"><i class="fa fa-exchange"></i></span>
					<i class="fa fa-exchange"></i> Mutasi Stok
				</a>
			</div>
			<div class="col-md-6"></div>
		</div>
	</div>
	<div class="box-footer"></div>
</div>
</div>
<div class="box">
	<div class="box-header with-border"><h3>Data Batch Obat</h3></div>
	<div class="box-body">
		<?= $this->render('_dataBacth', [
					'model' => $model,
					'dataProvider' => $dataProvider,
					'searchModel' => $searchModel,
				]) ?>
	</div>
	<div class="box-footer"></div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="batchModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Tambah Batch Obat</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($obat, 'idsatuan')->hiddenInput(['maxlength' => true,'value'=>$model->idsatuan])->label(false) ?>
			<?= $form->field($obat, 'idobat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
			<div class="row">
				<div class="col-md-4">
					<?= $form->field($obat, 'no_batch')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				<div class="col-md-8">
					<?= $form->field($obat, 'merk')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($obat, 'stok')->textInput(['maxlength' => true,'required'=>true]) ?>
					
				</div>
				
				<div class="col-md-6">
					<?=	$form->field($obat, 'produksi')->widget(DatePicker::classname(),[
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd',
						'required'=>true

						]
						])->label('Tgl Produksi')?>
				</div>
				<div class="col-md-6">
					<?=	$form->field($obat, 'ed')->widget(DatePicker::classname(),[
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd',
						'required'=>true
						]
						])->label('Tgl Kadaluwarsa')?>
				</div>
			</div>
			<div class="form-group">
			<?= Html::submitButton('Save', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		  </div>
	  </div>
	</div>
</div>