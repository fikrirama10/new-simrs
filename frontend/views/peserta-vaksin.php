<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peserta Vaksin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-index box box-body">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama',
            'usia',
            'tgldaftar',
            'tglvaksin',
            'vaksin',

        ],
    ]); ?>


</div>
