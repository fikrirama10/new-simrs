<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatDroping */

$this->title = 'Create Obat Droping';
$this->params['breadcrumbs'][] = ['label' => 'Obat Dropings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-droping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
