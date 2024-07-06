<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatSuplier */

$this->title = 'Create Obat Suplier';
$this->params['breadcrumbs'][] = ['label' => 'Obat Supliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-suplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
