<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PermintaanObat */

$this->title = 'Create Permintaan Obat';
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Obats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permintaan-obat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
