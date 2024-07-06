<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\LaboratoriumForm;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\LaboratoriumPemeriksaan */
$labform = LaboratoriumForm::find()->where(['idpemeriksaan'=>$model->id])->orderBy(['urutan'=>SORT_ASC])->all();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Laboratorium Pemeriksaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="laboratorium-pemeriksaan-view box box-body">

    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'layanan.nama_layanan',
            'nama_pemeriksaan',
            'kode_lab',
            'tarif.tarif'
        ],
    ]) ?>
	<hr>
	<p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<a data-toggle="modal" data-target="#tarifModal" class='btn btn-success'>Tambah Item</a>
        
       
    </p>

</div>
<div class="box">
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th rowspan=2>Item</th>
				<th rowspan =2>Satuan</th>
				<th colspan=2>Nilai Normal</th>
				<th rowspan=2>Urutan</th>
				<th rowspan=2>Edit</th>
			</tr>
			<tr>
				<th>Laki - Laki </th>
				<th>Perempuan</th>
			</tr>
			<?php if($labform){
					foreach($labform as $lf):
			?>
			<tr>
				<td><?= $lf->form ?></td>
				<td><?= $lf->satuan ?></td>
				<td><?= $lf->nilai_normallaki ?></td>
				<td><?= $lf->nilai_normalp ?></td>
				<td><?= $lf->urutan ?></td>
				<td>
					<a data-toggle="modal" data-target="#mdLab<?=$lf->id?>"  class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i></a>
					<a href='<?= Url::to(['laboratorium-pemeriksaan/hapus-item?id='.$lf->id])?>' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
				</td>
				
			</tr>
			<?php endforeach; }?>
		</table>
	</div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="tarifModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Tambah Item</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>

		  <div class="modal-body">
				<?= $this->render('_formLab', [
					'model' => $model,
					'item' => $item,
				]) ?>
		  </div>
	  </div>
	</div>
</div>
<?php 
	foreach($labform as $lf):
		Modal::begin([
			'id' => 'mdLab'.$lf->id,
			'header' => '<h3>Edit Lab</h3>',
			'size'=>'modal-lg',
			'options'=>[
				'data-url'=>'transaksi',
				'tabindex' => ''
			],
		]);

		echo '<div class="modalContent">'. $this->render('_formEdit', ['model'=>$model,'lf'=>$lf]).'</div>';
		 
		Modal::end();
	endforeach;
?>