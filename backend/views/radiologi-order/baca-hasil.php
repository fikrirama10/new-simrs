<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\RawatBayar;
use common\models\Poli;
use common\models\RadiologiHasilfoto;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use slavkovrn\prettyphoto\PrettyPhotoWidget;
$hasilFoto = RadiologiHasilfoto::find()->where(['idhasil'=>$model->id])->all();
$url = Yii::$app->params['baseUrl'].'dashboard/rest/list-foto?id='.$model->id;
$content = file_get_contents($url);
$json = json_decode($content, true);
?>
<div class="row">
	<div class="col-md-6">
		<div class="box">
			<div class="box-header with-border"></div>
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
					<?php if($model->status == 1){ ?>
						<?= $form->field($model, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Bayar -','required'=>true])->label('Jenis Bayar')?>
					<?php } ?>
					<?= $form->field($model, 'klinis')->textInput(['maxlength' => true]) ?>
					<a class='btn btn-warning' data-toggle="modal" data-target="#mdTemplate">Cari Template</a><hr>
					<?= $form->field($model, 'hasil')->textArea(['rows' => 6]) ?>
					<?= $form->field($model, 'kesan')->textArea(['rows' => 6]) ?>
					
					<?= $form->field($model, 'keterangan')->textArea(['rows' => 2]) ?>
					<?= $form->field($model, 'template')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
					<div class="form-group">
						<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
						<a href='<?= Url::to(['/radiologi-order/view?id='.$model->idrawat])?>' class='btn btn-primary'>Selesai</a>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box">
			<div class="box-body">
				<?php if($hasilFoto){ ?>
				<?= PrettyPhotoWidget::widget([
					'id'     =>'prettyPhoto',   // id of plugin should be unique at page
					'class'  =>'galary img-thumbnail',        // class of plugin to define a style
					'width' => '100%',           // width of image visible in widget (omit - initial width)
					'height' => '600px',        // height of image visible in widget (omit - initial height)
					'images' => $json,
				]) ?>
				<?php }else{ ?>
					<h3>Belum ada Foto di upload</h3>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php

Modal::begin([
	'id' => 'mdTemplate',
	'header' => '<h3>Pilih Template</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
	],
]);

echo '<div class="modalContent">'. $this->render('_dataTemplate', ['template'=>$template, ]).'</div>';
 
Modal::end();


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
