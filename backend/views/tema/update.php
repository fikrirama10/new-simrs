<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsTema */

$this->title = 'Update Setting Simrs Tema: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setting Simrs Temas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-simrs-tema-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
