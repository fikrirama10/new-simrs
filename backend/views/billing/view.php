<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use yii\helpers\ArrayHelper;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\TransaksiDetail;
use common\models\Rawat;
use common\models\RawatBayar;
use yii\bootstrap\Modal;
use common\models\KategoriPenyakit;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$total = 0;
$urlTindakan = "http://localhost/simrs2021/dashboard/rest/tarif?idjenis=";
$formatJs = <<< 'JS'
var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.tindakan;   
    return marckup ;
};
var formatRepoTindakan = function (repo) {
    return repo.tindakan || repo.text;
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
?>

<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
			<div class='box-body'>
				<?= DetailView::widget([
					'model' => $pasien,
					'attributes' => [
						'no_rm',
						'nama_pasien',
						'nohp',
					],
				]) ?>
			</div>
			<div class='box-footer'>
				<?php if($model->status == 1){ ?>
				<a  data-toggle="modal" data-target="#mdBayar" class='btn btn-sm bg-navy'>Bayar</a>
				<?php }else{ ?>
				<a class='btn btn-warning'>Print</a>
				<?php } ?>
			</div>			
		</div>
	</div>
	<?php if($model->status == 1){ ?>
	<div class='col-md-8'>
		<div class='box box-danger'>
			<div class='box-header'><h3>Tambah Tindakan</h3></div>
			<div class='box-body'>
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($trx, 'idkunjungan')->hiddeninput(['maxlength' => true,'required'=>true,'value'=>$model->idkunjungan])->label(false) ?>
					<?= $form->field($trx, 'idtransaksi')->hiddeninput(['maxlength' => true,'required'=>true,'value'=>$model->id])->label(false) ?>
					<?= $form->field($trx, 'idpelayanan')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Tindakan .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $urlTindakan,
						'dataType' => 'json',
						'delay' => 150,
						'data' => new JsExpression('function(params) { return {q:params.term};}'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true
						],
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
						'templateResult' => new JsExpression('formatTindakan'),
						'templateSelection' => new JsExpression('formatRepoTindakan'),
						],
					]);?>
					<div class='row'>
						<div class='col-md-6'>
							<?= $form->field($trx, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Bayar -','required'=>true])?>
						</div>
						<div class='col-md-6'>
							<?= $form->field($trx, 'tarif')->textinput(['readonly' => true,'required'=>true]) ?>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-3'>
							<?= $form->field($trx, 'jumlah')->textinput(['maxlength' => true,'required'=>true]) ?>
						</div>
						<div class='col-md-6'>
						
					<?=	$form->field($trx, 'tgl')->widget(DatePicker::classname(),[
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd',
						'required'=>true

						]
					])?>
						</div>
					</div>
					
						<?= $form->field($trx, 'idtindakan')->hiddeninput(['maxlength' => true,'required'=>true])->label(false) ?>
					
					<?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm','id'=>'confirm-spri']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<div class='row'>
	<div class='col-md-12'>
		<div class="box box-warning">
            <div class="box-header with-border">
				<h3 class="box-title">TAGIHAN TINDAKAN</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
				<!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="">
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Tindakan / Obat</th>
									<th>Penanggung</th>
									<th>Tarif Rp</th>
									<th>Jumlah </th>
									<th>Subtotal Rp</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($json['rekapTagihan'] as $rekap): ?>
								<tr>
									<td><?= $rekap['nama']?></td>
									<td>
										<?php if($rekap['bayar'] == 2){ ?>
											<span class="badge badge-xs bg-green">BPJS</span>
										<?php }else{ ?>
											<span class="badge-xs badge bg-gray">UMUM</span>
										<?php } ?>
									</td>
									<td>Rp <?= Yii::$app->algo->IndoCurr($rekap['harga'])?></td>
									<td><?= $rekap['jumlah']?></td>
									<td>Rp <?= Yii::$app->algo->IndoCurr($rekap['total'])?></td>
								</tr>
								<?php endforeach ?>
							</tbody>
							<tfoot>
								<tr>
									<th rowspan=3></th>
									<th align=right colspan=3>TOTAL DITANGGUNG</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['total'] - $json['rekapTagihanUmum'])?></th>
								</tr>
								<tr>
									<th align=right colspan=3>TOTAL BAYAR</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['rekapTagihanUmum'])?></th>
								</tr>
								<tr>
									<th align=right colspan=3>TOTAL TAGIHAN</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['total'])?></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.col -->
				</div>
            </div>
            <!-- /.box-body -->
        </div>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">RINCIAN RESEP</h3>

				<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
				</button>
				</div>
				<!-- /.box-tools -->
			</div>
			<div class="box-body">
				<table class="table table-bordered">
							<thead>
								<tr>
									<th>Kode Resep</th>
									<th>Tgl Resep</th>
									<th>Total Harga</th>
									<th>Total Bayar</th>
									<th>#</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($resep as $r): ?>
								<tr>
									<td><?= $r->kode_resep?></td>
									<td><?= $r->tgl?></td>
									<td><?= $r->total_harga?></td>
									<td><?= $r->total_bayar?></td>
									<td><a data-toggle="modal" data-target="#mdObat<?= $r->id?>" class='btn btn-primary btn-xs'>Lihat</a></td>
								</tr>
								<?php endforeach ?>
							</tbody>
							
						</table>
			</div>
		</div>
		<div class="box box-default collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title">RINCIAN </h3>

				<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
				</button>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body" style="display: none;">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Tindakan / Obat</th>
							<th>Penanggung</th>
							<th>Tarif Rp</th>
							<th>Jumlah </th>
							<th>Subtotal Rp</th>
							<th>#</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($trxdetail as $trx): 
						$detail = TransaksiDetail::find()->where(['idtransaksi'=>$model->id])->andwhere(['DATE_FORMAT(tgl,"%Y-%m-%d")'=>date('Y-m-d',strtotime($trx->tgl))])->all();						
						?>
						<tr>
						  <th colspan=4><?= date('Y-m-d',strtotime($trx->tgl)) ?></th>
						</tr>
						<?php foreach($detail as $d): 
						$total += $d->total;
						?>
						<tr>
						  <td><?= $d->nama_tindakan ?></td>
						  <td>
							<?php if($d->idbayar == 2){ ?>
								<span class="badge badge-xs bg-green">BPJS</span>
							<?php }else{ ?>
								<span class="badge-xs badge bg-gray">UMUM</span>
							<?php } ?>
						  </td>
						  <td>Rp. <?= Yii::$app->algo->IndoCurr($d->tarif)?></td>
						  <td><?= $d->jumlah ?></td>
						  <td>Rp. <?= Yii::$app->algo->IndoCurr($d->total)?></td>
						  <td>
							<?php if($d->idrawat == null){ ?>
								<a class='btn btn-danger btn-xs' href='<?= Url::to(['hapus-trx?id='.$d->id])?>'>Hapus</a>
							<?php }?>
						  </td>
						</tr>
						<?php endforeach; ?>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
								<tr>
									<th rowspan=3></th>
									<th align=right colspan=3>TOTAL DITANGGUNG</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['total'] - $json['rekapTagihanUmum'])?></th>
								</tr>
								<tr>
									<th align=right colspan=3>TOTAL BAYAR</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['rekapTagihanUmum'])?></th>
								</tr>
								<tr>
									<th align=right colspan=3>TOTAL TAGIHAN</th>
									<th>Rp <?= Yii::$app->algo->IndoCurr($json['total'])?></th>
								</tr>
							</tfoot>
				</table>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div>
<?php
$urlGet = Url::to(['billing/get-tarif']);
$this->registerJs("


	$('#transaksidetail-idbayar').on('change',function(){
		id = $('#transaksidetail-idpelayanan').val();
		bayar = $('#transaksidetail-idbayar').val();
		$.ajax({
			type: 'POST',
			url: '{$urlGet}',
			data: {id: id,bayar: bayar},
			dataType: 'json',
			success: function (data) {
				if(data !== null){
					var res = JSON.parse(JSON.stringify(data));
					$('#transaksidetail-tarif').val(res.tarif);	
					$('#transaksidetail-idtindakan').val(res.id);	
					
				}else{
					document.getElementById('transaksidetail-tarif').value= '' ;
					document.getElementById('transaksidetail-idtindakan').value= '' ;
					alert('data tidak ditemukan');
				}
			},
			error: function (exception) {
				alert(exception);
			}
		});	
	});
	

", View::POS_READY);

Modal::begin([
	'id' => 'mdBayar',
	'header' => '<h3>Pembayaran</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formBayar', ['model'=>$model,'json'=>$json]).'</div>';
 
Modal::end();

foreach($resep as $r):
	Modal::begin([
		'id' => 'mdObat'.$r->id,
		'header' => '<h3>Resep Obat</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formObat', ['r'=>$r]).'</div>';
	 
	Modal::end();
endforeach;

?>