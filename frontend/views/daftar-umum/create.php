<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DaftarUmum */

$this->title = 'Create Daftar Umum';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Umums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daftar-umum-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
