<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsTema */

$this->title = 'Create Setting Simrs Tema';
$this->params['breadcrumbs'][] = ['label' => 'Setting Simrs Temas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-simrs-tema-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
