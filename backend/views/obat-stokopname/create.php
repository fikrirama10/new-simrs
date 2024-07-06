<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatStokOpname */

$this->title = 'Create Obat Stok Opname';
$this->params['breadcrumbs'][] = ['label' => 'Obat Stok Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-stok-opname-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
