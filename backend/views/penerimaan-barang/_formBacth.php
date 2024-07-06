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
?>
<?php $form = ActiveForm::begin(); ?>

			<?= $form->field($bacth, 'idobat')->hiddenInput(['maxlength' => true,'value'=>$id])->label(false) ?>
			<div class="row">
				<div class="col-md-4">
					<?= $form->field($bacth, 'no_bacth')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				<div class="col-md-8">
					<?= $form->field($bacth, 'merk')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				
				<div class="col-md-4">
					<?= $form->field($bacth, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Obat -','required'=>true])->label('Jenis Obat')?>
				</div>
				<div class="col-md-4">
					<?= $form->field($bacth, 'idsuplier')->dropDownList(ArrayHelper::map(ObatSuplier::find()->all(), 'id', 'suplier'),['prompt'=>'- Suplier -','required'=>true])->label('Suplier')?>
				</div>
				<div class="col-md-4">
					<?= $form->field($bacth, 'kat_obat')->dropDownList(ArrayHelper::map(ObatKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Kategori Obat -','required'=>true])->label('Kategori Obat')?>
				</div>
				<div class="col-md-4">
					<?= $form->field($bacth, 'harga_jual')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($bacth, 'harga_beli')->textInput(['maxlength' => true,'required'=>true]) ?>
				</div>
				<div class="col-md-6">
					<?=	$form->field($bacth, 'tgl_produksi')->widget(DatePicker::classname(),[
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd',
						'required'=>true

						]
						])->label('Tgl Produksi')?>
				</div>
				<div class="col-md-6">
					<?=	$form->field($bacth, 'tgl_kadaluarsa')->widget(DatePicker::classname(),[
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
		