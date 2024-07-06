<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelProfesi */

$this->title = 'Create Personel Profesi';
$this->params['breadcrumbs'][] = ['label' => 'Personel Profesis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-profesi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
