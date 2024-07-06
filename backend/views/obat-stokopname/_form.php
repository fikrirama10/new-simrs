<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\ObatStokopnamePeriode;
/* @var $this yii\web\View */
/* @var $model common\models\ObatStokOpname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-stok-opname-form box box-body">
	<div class='col-md-6 '>
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'idperiode')->dropDownList(ArrayHelper::map(ObatStokopnamePeriode::find()->all(), 'id', 'periode'),['prompt'=>'- Periode -'])->label('Periode')?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>

