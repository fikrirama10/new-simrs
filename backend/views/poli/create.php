<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Poli */

$this->title = 'Create Poli';
$this->params['breadcrumbs'][] = ['label' => 'Polis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poli-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
