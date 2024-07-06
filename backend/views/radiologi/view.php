<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RadiologiTindakan */

$this->title = $model->nama_tindakan;
$this->params['breadcrumbs'][] = ['label' => 'Radiologi Tindakans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="radiologi-tindakan-view box box-body">
<h1><?= Html::encode($this->title) ?></h1>
<hr>
	 <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	 <br>.
     <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pelayanan.nama_pelayanan',
            'rad.radiologi',
            'kode_tindakan',
            'nama_tindakan',
            'keterangan:ntext',
        ],
    ]) ?>

</div>
