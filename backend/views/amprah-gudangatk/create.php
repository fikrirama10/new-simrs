<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangatk */

$this->title = 'Create Amprah Gudangatk';
$this->params['breadcrumbs'][] = ['label' => 'Amprah Gudangatks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amprah-gudangatk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
