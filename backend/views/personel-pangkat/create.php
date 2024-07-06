<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelPangkat */

$this->title = 'Create Personel Pangkat';
$this->params['breadcrumbs'][] = ['label' => 'Personel Pangkats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-pangkat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
