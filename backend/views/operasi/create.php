<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Operasi */

$this->title = 'Create Operasi';
$this->params['breadcrumbs'][] = ['label' => 'Operasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operasi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
