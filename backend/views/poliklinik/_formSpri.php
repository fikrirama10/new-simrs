<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use yii\bootstrap\Modal;
use common\models\Poli;
use common\models\Dokter;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
use kartik\date\DatePicker;

?>
<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($spri, 'idrawat')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($spri, 'idjenisrawat')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->idjenisrawat])->label(false) ?>
	<?= $form->field($spri, 'no_rm')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->no_rm])->label(false) ?>
	<?=	$form->field($spri, 'tgl_rawat')->widget(DatePicker::classname(),[
		'type' => DatePicker::TYPE_COMPONENT_APPEND,
		'pluginOptions' => [
		'autoclose'=>true,
		'format' => 'yyyy-mm-dd',
		'required'=>true
		]
	])->label('Tgl Rawat Inap')?>
	<?= $form->field($spri, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->where(['spesialis'=>1])->all(), 'id', 'poli'),['prompt'=>'- Nama Poli -','required'=>true])->label('Spesialis')?>
	<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cek dokter</a>
	<div id='pasien-ajax'></div>
	<hr>
	<label>DPJP</label>
	<input type='text' required class='form-control' id='nama_dokter' readonly >
	<?= $form->field($spri, 'iddokter')->hiddeninput(['maxlength' => true,'readonly'=>true,])->label(false) ?>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm-spri']) ?>
</div>
<?php ActiveForm::end(); ?>