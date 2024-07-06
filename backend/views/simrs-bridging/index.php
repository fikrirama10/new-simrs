<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\SettingSimrsBridging;
$bridging = SettingSimrsBridging::find()->count();
/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingSimrsBridgingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setting Simrs Bridgings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-simrs-bridging-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php if($bridging < 1){ ?>
    <p>
        <?= Html::a('Create Setting Simrs Bridging', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php } ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cons_id',
            'secret_key',
            'type',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
