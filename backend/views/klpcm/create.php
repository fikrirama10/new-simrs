<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Klpcm */

$this->title = 'Create Klpcm';
$this->params['breadcrumbs'][] = ['label' => 'Klpcms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="klpcm-create">

    <?= $this->render('_form', [
        'klpcm' => $klpcm,
    ]) ?>

</div>
