<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;

?>
<?php $form = ActiveForm::begin(['action' => ['/laboratorium-pemeriksaan/edit-lab?id='.$lf->id],]); ?>
    <?= $form->field($lf, 'status')->hiddenInput(['maxlength' => true,'value'=>1])->label(false) ?>
    <?= $form->field($lf, 'idpelayanan')->hiddenInput(['maxlength' => true,'value'=>$model->idlab])->label(false) ?>
    <?= $form->field($lf, 'idpemeriksaan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($lf, 'form')->textInput(['maxlength' => true])->label('Nama Item') ?>
    <?= $form->field($lf, 'satuan')->textInput(['maxlength' => true])?>
    <?= $form->field($lf, 'nilai_normallaki')->textInput(['maxlength' => true])->label('Nilai Normal Laki laki')?>
    <?= $form->field($lf, 'nilai_normalp')->textInput(['maxlength' => true])->label('Nilai Normal Perempuan')?>
    <?= $form->field($lf, 'urutan')->textInput(['maxlength' => true])->label()?>
	<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>