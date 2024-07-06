<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ObatFarmasi */

$this->title = 'Buat Resep Baru';
$this->params['breadcrumbs'][] = ['label' => 'Obat Farmasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-6">
		<div class='box box-body'>
			 <h1><?= Html::encode($this->title) ?></h1>

			<?= $this->render('_form', [
				'model' => $model,
			]) ?>
		</div>
	</div>
</div>
   

</div>
