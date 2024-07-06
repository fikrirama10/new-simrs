<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Rawat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rawats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rawat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idrawat',
            'idkunjungan',
            'idjenisrawat',
            'no_rm',
            'idpoli',
            'iddokter',
            'idruangan',
            'idkelas',
            'idbayar',
            'no_sep',
            'no_rujukan',
            'no_suratkontrol',
            'tglmasuk',
            'tglpulang',
            'los',
            'status',
            'no_antrian',
            'cara_datang',
            'cara_keluar',
            'kunjungan',
        ],
    ]) ?>

</div>
