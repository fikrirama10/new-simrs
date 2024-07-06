<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelJabatan */

$this->title = 'Create Personel Jabatan';
$this->params['breadcrumbs'][] = ['label' => 'Personel Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-jabatan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
