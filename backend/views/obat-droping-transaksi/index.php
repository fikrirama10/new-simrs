<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ObatDropingTransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obat Droping Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-droping-transaksi-index box box-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Obat Droping Transaksi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idtrx',
            'idjenis',
            'ketrangan:ntext',
            'tgl',
            //'iduser',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
