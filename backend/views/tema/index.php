<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingSimrsTemaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setting Simrs Temas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-simrs-tema-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Setting Simrs Tema', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tema',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
