<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RawatKunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rawat Kunjungans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-kunjungan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rawat Kunjungan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idkunjungan',
            'no_rm',
            'tgl_kunjungan',
            'jam_kunjungan',
            //'iduser',
            //'usia_kunjungan',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
