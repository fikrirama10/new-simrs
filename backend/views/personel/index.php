<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PersonelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personels';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Personel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'kode_personel',
            'nik',
            'no_bpjs',
            'nrp_nip',
            //'nama_lengkap',
            //'alamat:ntext',
            //'idjabatan',
            //'idpangkat',
            //'foto',
            //'no_tlp',
            //'status',
            //'idjenis',
            //'mulai_bergabung',
            //'akhir_bergabung',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
