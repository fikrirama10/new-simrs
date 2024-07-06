<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PersonelPangkatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personel Pangkats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personel-pangkat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Personel Pangkat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pangkat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
