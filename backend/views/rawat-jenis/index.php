<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RawatJenisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rawat Jenis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rawat-jenis-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rawat Jenis', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'jenis',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
