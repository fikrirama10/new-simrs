<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\TindakanTarif;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\Tindakan */
$tindakan = TindakanTarif::find()->where(['idtindakan'=>$model->id])->all();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tindakans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="tindakan-view box box-body">
    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nama_tindakan',
            'kode_tindakan',
            'tindakan.kategori',
            'penerimaan.jenispenerimaan',
            'detail.namapenerimaan',
			
        ],
    ]) ?>
	<hr>
	<p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a data-toggle="modal" data-target="#tarifModal" class='btn btn-success'>Tambah Tarif</a>
        
    </p>
</div>
<div class="box">
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th>Jenis Bayar</th>
				<th>Tarif</th>
				<th>Edit</th>
			</tr>
			<?php if($tindakan){
					foreach($tindakan as $tn):
			?>
			<tr>
				<td><?= $tn->bayar->bayar ?></td>
				<td><?= $tn->tarif ?></td>
				<td><a data-toggle="modal" data-target="#mdTarif<?=$tn->id?>"  class='btn btn-warning'><i class='fa fa-pencil'></i></a></td>
			</tr>
			<?php endforeach; }?>
		</table>
	</div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="tarifModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Tambah Tarif</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<?= $this->render('_formTarif', [
					'model' => $model,
					'tarif' => $tarif,
				]) ?>
		  </div>
	  </div>
	</div>
</div>
<?php 
	foreach($tindakan as $tn):
		Modal::begin([
			'id' => 'mdTarif'.$tn->id,
			'header' => '<h3>Edit Tarif</h3>',
			'size'=>'modal-lg',
			'options'=>[
				'data-url'=>'transaksi',
				'tabindex' => ''
			],
		]);

		echo '<div class="modalContent">'. $this->render('_formEdit', ['model'=>$model,'tn'=>$tn]).'</div>';
		 
		Modal::end();
	endforeach;
?>
