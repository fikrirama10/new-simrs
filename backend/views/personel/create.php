<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Personel */

$this->title = 'Create Personel';
$this->params['breadcrumbs'][] = ['label' => 'Personels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
