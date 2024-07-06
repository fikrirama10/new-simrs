<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
?>
<hr>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No.SEP</th>
		<th>Tgl.SEP</th>
		<th>Poli</th>
		<th>No.Rujukan</th>
		<th>PPK.Pelayanan</th>
		<th></th>
	</tr>
	<?php $no=1; foreach($monitoring['response']['histori'] as $histori){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $histori['noSep'] ?></td>
		<td><?= $histori['tglSep'] ?></td>
		<td><?= $histori['poli'] ?></td>
		<td><?= $histori['noRujukan'] ?></td>
		<td><?= $histori['ppkPelayanan'] ?></td>
		<td><a class='btn btn-success btn-xs' id='pilihsp<?= $histori['noSep']?>'><span class='fa fa-check'></span></a></td>
	</tr>
				<?php
				$tgl = date('Y-m-d',strtotime($rawat->tglmasuk));
				$urlPoli = Url::to(['pasien/show-poli']);
				$this->registerJs("
					$('#pilihsp{$histori['noSep']}').on('click',function(e) {					
						$('#sepKontrol').val('{$histori['noSep']}')
						sep = '{$histori['noSep']}';
						tgl = '{$tgl}';
						idrawat = '{$rawat->id}';
						$.ajax({
							type: 'GET',
							url: '{$urlPoli}',
							data: 'sep='+sep+'&tgl='+tgl+'&idrawat='+idrawat,
							
							success: function (data) {
								$('#dataPoli').html(data);
								
								console.log(data);
								
							},
							
						});
					});
				", View::POS_READY);
				?>
	<?php } ?>
</table>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">No.SEP</label>
			<div class="col-sm-8">
				<input type='text' class='form-control' readonly name='RawatKontrol[no_sep]' id='sepKontrol'>
			</div>
		</div>
		<div id='dataPoli'></div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Dokter</label>
			<div class="col-sm-8">
				<input type='text' class='form-control' readonly name='nama_dokter' id='nama_dokter'>
				<input type='hidden' class='form-control' name='RawatKontrol[kode_dokter]' id='kode_dokter'>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Tgl.Kontrol</label>
			<div class="col-sm-8">
				<input type='text' class='form-control' name='RawatKontrol[tgl_kontrol]' readonly id='tgl_kontrol' value='<?= date('Y-m-d',strtotime($rawat->tglmasuk))?>'>
			</div>
		</div>
		<button type="submit" id='' class="btn btn-info pull-right ">Simpan</button>
