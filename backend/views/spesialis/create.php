<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DokterSpesialis */

$this->title = 'Create Dokter Spesialis';
$this->params['breadcrumbs'][] = ['label' => 'Dokter Spesialis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dokter-spesialis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
