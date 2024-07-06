<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-amprah-form">

    <?php $form = ActiveForm::begin(); ?>

	<?=	$form->field($model, 'tgl_permintaan')->widget(DatePicker::classname(),['type' => DatePicker::TYPE_COMPONENT_APPEND,'pluginOptions' => ['autoclose'=>true,'format' => 'yyyy-mm-dd']])?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
