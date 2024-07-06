<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsBridging */

$this->title = 'Update Setting Simrs Bridging: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setting Simrs Bridgings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-simrs-bridging-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
