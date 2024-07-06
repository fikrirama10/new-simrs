<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrs */

$this->title = 'Create Setting Simrs';
$this->params['breadcrumbs'][] = ['label' => 'Setting Simrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-simrs-create  box box-body">
	<div class='row'>
		<div class='col-md-6'>
			<h1><?= Html::encode($this->title) ?></h1>

			<?= $this->render('_form', [
				'model' => $model,
			]) ?>
		</div>
	</div>
    

</div>
