<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Operasi */

$this->title = 'Tulis Laporan Operasi: ' . $model->kode_ok;
$this->params['breadcrumbs'][] = ['label' => 'Operasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode_ok, 'url' => ['view', 'id' => $model->kode_ok]];
$this->params['breadcrumbs'][] = 'Update';
?>
<br>
<div class="operasi-update">
	
	<div class='row'>
		<div class='col-md-3'></div>
		<div class='col-md-9'>
			<div class='box'>
				<div class='box-header'></div>
				<div class='box-body'>
					<?= $this->render('_form', [
						'model' => $model,
					]) ?>
				</div>
			</div>
		</div>
	</div>
    

</div>
