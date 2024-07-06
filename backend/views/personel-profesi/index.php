<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PersonelProfesiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personel Profesis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-profesi-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Personel Profesi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'profesi',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
