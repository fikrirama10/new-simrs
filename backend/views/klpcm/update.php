<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Klpcm */

$this->title = 'Update Klpcm: ' . $klpcm->id;
$this->params['breadcrumbs'][] = ['label' => 'Klpcms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $klpcm->id, 'url' => ['view', 'id' => $klpcm->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="klpcm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formUpdate', [
        'klpcm' => $klpcm,
    ]) ?>

</div>
