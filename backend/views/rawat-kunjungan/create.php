<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RawatKunjungan */

$this->title = 'Create Rawat Kunjungan';
$this->params['breadcrumbs'][] = ['label' => 'Rawat Kunjungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-kunjungan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
