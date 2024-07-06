<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\checkbox\CheckboxX;
use common\models\DokterKuota;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
	<div class="form-group" id='list_dokter'>
		<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
		<div class="col-md-8 col-sm-8 col-xs-12" style='margin-left:-15px;'>
			<table class='table table-bordered'>
				<tr>
					<th>Nama Dokter</th>
					<th>Hari</th>
					<th>Jadwal</th>
					<th>Kuota</th>
					<th>Sisa</th>
				</tr>
				<?php if($dokter){ ?>
					<?php foreach($dokter as $dr){ 
					$dokter_kuotac = DokterKuota::find()->where(['iddokter'=>$dr->iddokter])->andwhere(['tgl'=>$kunjungan])->andWhere(['idhari'=>date('N',strtotime($kunjungan))])->count();
					$dokter_kuota = DokterKuota::find()->where(['iddokter'=>$dr->iddokter])->andwhere(['tgl'=>$kunjungan])->andWhere(['idhari'=>date('N',strtotime($kunjungan))])->one();
					?>
					<tr>
						<td>
							<a id='kode-dokter<?= $dr->iddokter?>' class='btn btn-default btn-xs'><?= $dr->dokter->nama_dokter?></a>
							<input type='hidden' id='inputkode-dokter<?= $dr->iddokter?>' value='<?= $dr->iddokter?>'>
							<input type='hidden' id='inputnama-dokter<?= $dr->iddokter?>' value='<?= $dr->dokter->nama_dokter?>'>
						</td>
						<td><?= $dr->hari->hari?></td>
						<td><?= $dr->jam_mulai?> - <?= $dr->jam_selesai?></td>
						<td><?= $dr->kuota?></td>
						<td>
							<?php if($dokter_kuotac > 0){echo $dokter_kuota->sisa;}else{echo $dr->kuota;} ?>
						</td>
					</tr>
					<?php 
					$this->registerJs("
						$('#kode-dokter{$dr->iddokter}').on('click',function() {
							$('#dokter-nama').val($('#inputnama-dokter{$dr->iddokter}').val());
							$('#dokter-input').val($('#inputkode-dokter{$dr->iddokter}').val());
							$('#tampildokter').show();
							$('#tampillayanan').show();
							$('#list_dokter').hide();
						});
					", View::POS_READY);
					?>
					<?php } ?>
				<?php }else{ ?>
					<tr>
						<td colspan='4'>Data tidak ada</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<div id='tampildokter'>
		<div class="form-group has-success" >
			<label class="col-md-3 col-sm-3 col-xs-12 control-label">Dokter</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style='margin-left:-15px;'>
				<div class="input-group">
					<input type="text" class="form-control" id='dokter-nama' disabled  id="inputSuccess" placeholder="Enter ...">
					<input type='hidden' name='Rawat[iddokter]' id='dokter-input'>
					<span class="input-group-btn">
						<button type="button" id="btnCariPPKRujukan" class="btn btn-warning btn-sm">
							<span><i class="fa fa-pencil"></i></span> &nbsp;
						</button>
					</span>
				</div>
			</div>
		</div>
		
	</div>
<?php 
	$this->registerJs("
		$('#btnCariPPKRujukan').on('click',function() {
			$('#list_dokter').show();
			$('#tampildokter').hide();
			$('#tampillayanan').hide();
		});
		$('#tampildokter').hide();
		$('#tampillayanan').hide();
	", View::POS_READY);
?>