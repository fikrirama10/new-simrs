<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LaboratoriumLayananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Layanan Laboratorium';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorium-layanan-index box box-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Layanan Laboratorium', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama_layanan',
            'keterangan:ntext',
            'urutan',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
