<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Tarif */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tarifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="tarif-view box box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<div class='row'>
		<div class='col-md-6'>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'kode_tarif',
					'nama_tarif',
					'idkategori',
					'idjenisrawat',
					'kat_tindakan',
					'idpoli',
					'idkelas',
					
				],
			]) ?>
		</div>
		<div class='col-md-6'>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'medis',
            'paramedis',
            'petugas',
            'apoteker',
            'gizi',
            'bph',
            'sewakamar',
            'sewaalat',
            'makanpasien',
            'laundry',
            'cs',
            'opsrs',
            'nova_t',
            'perekam_medis',
            'tarif',
					
				],
			]) ?>
		</div>
	</div>
    

</div>
'