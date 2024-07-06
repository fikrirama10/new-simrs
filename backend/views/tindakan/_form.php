<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\JenisPenerimaan;
use common\models\JenispenerimaanDetail;
use common\models\TindakanTarifKategori;
use common\models\TindakanKategori;
use common\models\RawatBayar;
use common\models\Poli;
/* @var $this yii\web\View */
/* @var $model common\models\Tindakan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tindakan-form box box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_tindakan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idtarif')->dropDownList(ArrayHelper::map(TindakanTarifKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Kategori Tindakan -'])->label('Kategori Tindakan')?>
    <?= $form->field($model, 'idjenistindakan')->dropDownList(ArrayHelper::map(TindakanKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Jenis Tindakan -'])->label('Jenis Tindakan')?>
    <?= $form->field($model, 'idpenerimaan')->dropDownList(ArrayHelper::map(JenisPenerimaan::find()->all(), 'id', 'jenispenerimaan'),['prompt'=>'- Jenis Penerimaan -'])->label('Jenis Penerimaan')?>
    <?= $form->field($model, 'idjenispenerimaan')->dropDownList(ArrayHelper::map(JenispenerimaanDetail::find()->all(), 'id', 'namapenerimaan'),['prompt'=>'- Jenis Penerimaan Detail-'])->label('Jenis Penerimaan Detail')?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
