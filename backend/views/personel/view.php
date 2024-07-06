<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Personel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Personels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="personel-view">

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
            'kode_personel',
            'nik',
            'no_bpjs',
            'nrp_nip',
            'nama_lengkap',
            'alamat:ntext',
            'idjabatan',
            'idpangkat',
            'foto',
            'no_tlp',
            'status',
            'idjenis',
            'mulai_bergabung',
            'akhir_bergabung',
        ],
    ]) ?>

</div>
