<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UsulPesan */

$this->title = 'Create Usul Pesan';
$this->params['breadcrumbs'][] = ['label' => 'Usul Pesans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usul-pesan-create">
<br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
