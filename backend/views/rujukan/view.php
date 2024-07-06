<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;
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
?>
<div id='utama'>
<div class="col-md-3">
	<div class="box box-solid box-success">
		<div class="box-header with-border">
			<span><i class="fa fa-envelope"> SEP</i> </span>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<li><a title="No.SEP"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnosep"><?= $model->no_sep ?></label></a></li>
				<li><a title="Tgl.SEP"><i class="fa fa-calendar"></i> <label id="lbltglsep"><?= date('Y-m-d',strtotime($model->tglmasuk))?></label></a></li>
				<li><a title="Jns.Pelayanan"><i class="fa fa-medkit"></i> <label id="lbljenpel"><?= $model->jenisrawat->jenis?> (<?= $model->poli->poli?>)</label></a></li>
				<li><a title="Diagnosa"><i class="fa fa-heartbeat"></i> <label id="lbldiagnosa"><?= $model->icdx?></label></a></li>
			</ul>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /. box -->
	<div class="box box-solid">
		<div class="box-header with-border">
			<span><i class="fa fa-user"> Peserta</i> </span>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<li><a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu"><?= $pasien->no_bpjs ?></label></a></li>
				<li><a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta"><?= $pasien->nama_pasien ?></label></a></li>
				<li><a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst"><?= $pasien->tgllahir ?></label></a></li>
				<li><a title="Kelamin"><i class="fa fa-intersex  text-blue"></i> <label id="lbljkpst"><?php if($pasien->jenis_kelamin == 'L'){echo'Laki-laki';}else{echo'Perempuan';} ?></label></a></li>
				<li><a title="Kelas Peserta"><i class="fa fa-user  text-blue"></i> <label id="lblklpst"><?= $peserta['peserta']['hakKelas']['keterangan']?></label></a></li>
				<li><a title="PPK Asal Peserta"><i class="fa fa-user-md  text-blue"></i> <label id="lblppkpst"><?= $peserta['peserta']['provUmum']['nmProvider']?> - <?= $peserta['peserta']['provUmum']['kdProvider'] ?></label></a></li>
			</ul>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
<div class="col-md-9">
	<?php $form = ActiveForm::begin([
		 'type' => ActiveForm::TYPE_HORIZONTAL,
			'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
	]); ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<i class="fa fa-battery-half"></i>
			<small class="pull-right">
				<label style="font-size:medium" id="lblnorujukan"></label>
			</small>
				<small class="pull-right">
                        <label style="font-size:medium" id="lblnorujukan"><?= $rujukan->no_rujukan?></label>
                    </small>
		</div>
		<div class="box-body">
			
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rujukan</label>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="input-group date">
							<input readonly type="date" class="form-control datepicker" id="txttglrujukan" value='<?=date('Y-m-d',strtotime($model->tglmasuk))?>' max='<?=date('Y-m-d',strtotime($model->tglmasuk))?>' min='<?=date('Y-m-d',strtotime($model->tglmasuk))?>' name='tglrujukan'>
							<span class="input-group-addon">
								<span class="fa fa-calendar">
								</span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<select class="form-control" name='jpelayanan' readonly id="cbpelayanan">
							<option value="2">Rawat Jalan</option>
							<option value="1">Rawat Inap</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tipe</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<label><input type="radio" name="rbrujukan" value="0" id="rbpenuh" checked=""> Penuh</label>
						<label><input type="radio" name="rbrujukan" value="1" id="rbpartial"> Partial</label>
						<label><input type="radio" name="rbrujukan" value="2" id="rbbalik"> Rujuk Balik (Non PRB)</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa Rujukan</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?= $form->field($rujukan, 'icd10')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Cari ICD X .....','readonly'=>true],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => "https://new-simrs.rsausulaiman.com/auth/listdiagnosa",
						'dataType' => 'json',
						'delay' => 250,
						'data' => new JsExpression('function(params) { return {q:params.term};}'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true
						],
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
						'templateResult' => new JsExpression('formatRepo'),
						'templateSelection' => new JsExpression('formatRepoSelection'),
						],
					])->label(false);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Di Rujuk Ke</label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group">
							<input type="text" class="form-control" id="txtnmppkdirujuk" name='namappk' value='<?= $rujukan->faskes_tujuan?>' placeholder="nama ppk rujuk" disabled="">
							<input type="hidden" id="txtkdppkdirujuk" name='kodeppk' value='<?= $rujukan->kode_tujuan?>' value="">
							<span class="input-group-btn">
								<button type="button" id="btnCariPPKRujukan" class="btn btn-success">
									<span><i class="fa fa-hospital-o"></i></span> &nbsp;
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div id="divPoli">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input type="text" class="form-control" id="txtnmpoli" name='namapoli' value='<?= $get_rujukan['response']['rujukan']['namaPoliRujukan'] ?>' disabled="">
							<input type="hidden" id="txtkdpoli" value='<?= $rujukan->idspesialis ?>' name='kodepolii'>
						</div>
					</div>


				</div>
				
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Catatan Rujukan</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<textarea type="text" class="form-control" name='catatanrujukan' id="txtketerangan"></textarea>
					</div>

				</div>
				<input type='hidden' name='tglrencanaRujuk'id='tglrencanaRujuk'>
			
			<!-- obat -->
		</div>

		<div class="box-footer">
			<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<button id="btnSimpan" type="button" class="btn btn-primary" style="display: none;"><i class="fa fa-save"></i> Simpan</button>
					<a href='<?= Url::to(['rujukan/hapus-bpjs?id='.$rujukan->id])?>' id="btnHapus" type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</a>
					<a target='_blank' href='<?= Url::to(['rujukan/print-rujukan?id='.$rujukan->id])?>' id="btnCetak" type="button" class="btn btn-info"><i class="fa fa-print"></i> Cetak</a>
					<a href='<?= Url::to(['/rujukan'])?>' id="btnBatal" type="button" class="btn btn-default pull-right"><i class="fa fa-undo"></i> Batal</a>
				</div>
			</div>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>
</div>
</div>
