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
use common\models\Poli;
use common\models\Rawat;
use kartik\date\DatePicker;
?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($pelayanan, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false)?>
<?=	$form->field($pelayanan, 'tglmasuk')->widget(DatePicker::classname(),[
	'type' => DatePicker::TYPE_COMPONENT_APPEND,
	'pluginOptions' => [
	'autoclose'=>true,
	'format' => 'yyyy-mm-dd',
	'todayHighlight' => true,
	'required'=>true,
	]
])->label('Tgl Kunjungan')?>
<?= $form->field($pelayanan, 'idjenisrawat')->dropDownList(ArrayHelper::map(RawatJenis::find()->where(['ket'=>1])->all(), 'id', 'jenis'),['prompt'=>'- Jenis Rawat -','onchange'=>'$.get("'.Url::toRoute('pasien/listpoli/').'",{ id: $(this).val() }).done(function( data ) 
	{
	  $( "select#rawat-idpoli" ).html( data );
	});		
'])->label('Jenis Rawat')?>
<?= $form->field($pelayanan, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->where(['id'=>0])->all(), 'id', 'poli'),['prompt'=>'- Nama Poli -','required'=>true])->label('Nama Poli')?>
<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cek dokter</a>
<div id='loading' style='display:none;'>
	<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
</div>
<div id='pasien-ajax'></div>
<?php ActiveForm::end(); ?>