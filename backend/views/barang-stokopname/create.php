<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BarangStokopname */

$this->title = 'Create Barang Stokopname';
$this->params['breadcrumbs'][] = ['label' => 'Barang Stokopnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-stokopname-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
