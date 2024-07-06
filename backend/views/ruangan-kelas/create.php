<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganKelas */

$this->title = 'Create Kelas';
$this->params['breadcrumbs'][] = ['label' => 'Ruangan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruangan-kelas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
