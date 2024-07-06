<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganBed */

$this->title = 'Create Ruangan Bed';
$this->params['breadcrumbs'][] = ['label' => 'Ruangan Beds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruangan-bed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
