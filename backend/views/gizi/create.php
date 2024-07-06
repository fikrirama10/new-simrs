<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Gizi */

$this->title = 'Create Gizi';
$this->params['breadcrumbs'][] = ['label' => 'Gizis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gizi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
