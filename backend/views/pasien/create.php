<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pasien */

$this->title = 'Create Pasien';
$this->params['breadcrumbs'][] = ['label' => 'Pasiens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pasien-create">

    <?= $this->render('_form2', [
        'model' => $model,
        'modelalamat' => $modelalamat,
        'modelstatus' => $modelstatus,
    ]) ?>

</div>
