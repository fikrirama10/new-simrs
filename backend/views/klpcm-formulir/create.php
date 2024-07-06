<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KlpcmFormulir */

$this->title = 'Create Klpcm Formulir';
$this->params['breadcrumbs'][] = ['label' => 'Klpcm Formulirs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="klpcm-formulir-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
