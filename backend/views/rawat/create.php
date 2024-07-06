<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Rawat */

$this->title = 'Create Rawat';
$this->params['breadcrumbs'][] = ['label' => 'Rawats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
