<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ruangan */

$this->title = 'Create Ruangan';
$this->params['breadcrumbs'][] = ['label' => 'Ruangans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruangan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
