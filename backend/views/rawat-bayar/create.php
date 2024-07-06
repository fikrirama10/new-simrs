<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RawatBayar */

$this->title = 'Create Rawat Bayar';
$this->params['breadcrumbs'][] = ['label' => 'Rawat Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-bayar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
