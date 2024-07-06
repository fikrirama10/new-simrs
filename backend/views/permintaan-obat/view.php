<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\PermintaanObat;
use common\models\ObatBacth;
use common\models\PermintaanObatRequest;
use common\models\Obat;
use common\models\RawatBayar;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\View;
use kartik\grid\GridView;
use yii\web\JsExpression;
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
/* @var $this yii\web\View */
/* @var $model common\models\PermintaanObat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Obats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class='row'>
	<div class='col-md-4'>
		<div class="permintaan-obat-view box box-body">
			<h4>Permintaan Obat</h4>
			
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'kode_permintaan',
					'tgl_permintaan',
					'user.userdetail.nama',
					'unit.unit',
					'keterangan:ntext',
				],
			]) ?>

		</div>
	<div class='box box-footer'>
		<a href='<?= Url::to(['permintaan-obat/form-permintaan?id='.$model->id])?>' target='_blank' class='btn btn-warning'>Print</a>
	</div>
	<div class='box box-body'>
	<h3>Permintaan Obat</h3>
	
</div>
	</div>
	<div class='col-md-8'>
		<div class=' box box-body'>
			<h3>Persetujuaan Obat</h3>
			<?php if($model->status == 1){ ?>
			<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($detail_permintaan, 'idobat')->hiddenInput(['required'=>true])->label(false) ?>
				<?= $form->field($detail_permintaan, 'idbacth')->hiddenInput(['required'=>true])->label(false) ?>
				<?= $form->field($detail_permintaan, 'idpermintaan')->hiddenInput(['required'=>true,'value'=>$model->id])->label(false) ?>
				<label>Merk Obat</label>
				<input type='text' readonly id='merk-obat' class='form-control'><hr>
				<?= $form->field($detail_permintaan, 'jumlah_setuju')->textInput(['required'=>true]) ?>
				<?= $form->field($detail_permintaan, 'keterangan')->textarea(['required'=>true]) ?>
				<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm4']) ?>
			<?php ActiveForm::end(); ?>
			<?php } ?>
			<hr>
			<table class='table table-bordered table-xs'>
				<tr>
					<th>No</th>
					<th>No Bacth</th>
					<th>Obat / Alkes</th>
					<th>ED</th>
					<th>Jumlah Diberikan</th>
					<th>Keterangan</th>
					<th>Stok Gudang</th>

				</tr>
				<?php $no=1; foreach($detail_permintaan_list as $dpl){ 
				$cek_bacth = ObatBacth::findOne($dpl->idbacth);
				?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $dpl->bacth->no_bacth ?> </td>
					<?php if($cek_bacth){ ?>
					<td><?= $dpl->obat->nama_obat ?> (<?= $dpl->bacth->merk?>)</td>
					<td><?= $dpl->bacth->tgl_kadaluarsa ?> </td>
					<?php } ?>
					<td><?= $dpl->jumlah_setuju ?> </td>
					<td><?= $dpl->keterangan ?> </td>
					<td>	<a  data-toggle='modal' data-target='#mdPermintaand<?= $dpl->id ?>' class='btn btn-xs btn-primary'>Lihat Stok</a></td>
				</tr>
				<?php } ?>
			</table>
				<?php if($model->status == 13){ ?>
					<?php if(Yii::$app->user->identity->idpriv == 5){ ?>
					<br>
					<a id='confirm' href='<?= Url::to(['permintaan-obat/selesai-persetujuan?id='.$model->id])?>' class='btn btn-primary'>Berikan</a>
					<?php } ?>
				<?php } ?>
		</div>
	</div>
</div>


<?php 
foreach($permintaan_list as $pl):
	Modal::begin([
		'id' => 'mdPermintaan'.$pl->id,
		'header' => '<h3>Permintaan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formPermintaan', ['model'=>$model,'pl'=>$pl
	,'detail_permintaan'=>$detail_permintaan]).'</div>';
	 
	Modal::end();
endforeach;
foreach($detail_permintaan_list as $dpl):
	Modal::begin([
		'id' => 'mdPermintaand'.$dpl->id,
		'header' => '<h3>Permintaan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formPermintaan', ['model'=>$model,'pl'=>$pl
	,'detail_permintaan'=>$detail_permintaan]).'</div>';
	 
	Modal::end();
endforeach;
$this->registerJs("
	
	$('#confirm4').hide();
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka stok akan langsung berpindah , pastikan semua jumlah telah sesuai.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	$('#confirm-ajukan').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);

?>