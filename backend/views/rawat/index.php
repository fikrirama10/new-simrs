<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RawatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rawats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rawat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idrawat',
            'idkunjungan',
            'idjenisrawat',
            'no_rm',
            //'idpoli',
            //'iddokter',
            //'idruangan',
            //'idkelas',
            //'idbayar',
            //'no_sep',
            //'no_rujukan',
            //'no_suratkontrol',
            //'tglmasuk',
            //'tglpulang',
            //'los',
            //'status',
            //'no_antrian',
            //'cara_datang',
            //'cara_keluar',
            //'kunjungan',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
