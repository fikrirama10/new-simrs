<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */

$this->title = 'Create Amprah Gudangobat';
$this->params['breadcrumbs'][] = ['label' => 'Amprah Gudangobats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amprah-gudangobat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
