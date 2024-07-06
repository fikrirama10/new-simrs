<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelJenis */

$this->title = 'Create Personel Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Personel Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-jenis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
