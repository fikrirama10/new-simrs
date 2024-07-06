<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RawatResepSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rawat Reseps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-resep-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rawat Resep', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'kode_resep',
            'idrawat',
            'no_rm',
            'iddokter',
            //'tgl_resep',
            //'jam_resep',
            //'status',
            //'idjenisrawat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
