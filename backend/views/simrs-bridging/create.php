<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsBridging */

$this->title = 'Create Setting Simrs Bridging';
$this->params['breadcrumbs'][] = ['label' => 'Setting Simrs Bridgings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-simrs-bridging-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
