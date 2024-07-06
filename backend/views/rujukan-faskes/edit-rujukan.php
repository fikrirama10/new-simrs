<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
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

<div class="col-md-12">
	<h3>Edit Rujukan Keluar RS</h3>
</div>
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
				<li><a title="No.SEP"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnosep"><?= $sep['response']['noSep'] ?></label></a></li>
				<li><a title="Tgl.SEP"><i class="fa fa-calendar"></i> <label id="lbltglsep"><?= $sep['response']['tglSep'] ?></label></a></li>
				<li><a title="Jns.Pelayanan"><i class="fa fa-medkit"></i> <label id="lbljenpel"><?= $sep['response']['jnsPelayanan'] ?> (<?= $sep['response']['poli'] ?>)</label></a></li>
				<li><a title="Diagnosa"><i class="fa fa-heartbeat"></i> <label id="lbldiagnosa"><?= $sep['response']['diagnosa']?></label></a></li>
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
				<li><a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu"><?= $peserta['response']['peserta']['noKartu'] ?></label></a></li>
				<li><a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta"><?= $peserta['response']['peserta']['nama'] ?></label></a></li>
				<li><a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst"><?= $peserta['response']['peserta']['tglLahir'] ?></label></a></li>
				<li><a title="Kelamin"><i class="fa fa-intersex  text-blue"></i> <label id="lbljkpst"><?php if($peserta['response']['peserta']['sex'] == 'L'){echo'Laki-laki';}else{echo'Perempuan';} ?></label></a></li>
				<li><a title="Kelas Peserta"><i class="fa fa-user  text-blue"></i> <label id="lblklpst"><?= $peserta['response']['peserta']['hakKelas']['keterangan']?></label></a></li>
				<li><a title="PPK Asal Peserta"><i class="fa fa-user-md  text-blue"></i> <label id="lblppkpst"><?= $peserta['response']['peserta']['provUmum']['nmProvider']?> - <?= $peserta['response']['peserta']['provUmum']['kdProvider'] ?></label></a></li>
			</ul>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
<div class="col-md-9">
	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<i class="fa fa-battery-half"></i>
			<small class="pull-right">
				<label style="font-size:medium" id="lblnorujukan"><?= $rujukan['response']['rujukan']['noRujukan'] ?></label>
			</small>
		
		</div>
		<div class="box-body">
			
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rujukan</label>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="input-group date">
							<input type="date" class="form-control datepicker" id="txttglrujukan" value='<?= $rujukan['response']['rujukan']['tglRujukan'] ?>' max='<?=date('Y-m-d',strtotime($sep['response']['tglSep']))?>' min='<?= $rujukan['response']['rujukan']['tglRujukan'] ?>' name='tglrujukan' disabled=''>
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
						<select class="form-control" name='jpelayanan' id="cbpelayanan">
							<option selected="selected" value="<?= $rujukan['response']['rujukan']['jnsPelayanan']?>"><?php if($rujukan['response']['rujukan']['jnsPelayanan'] == 1){echo'Rawat Inap';}else{echo'Rawat Jalan';}?></option>
									<?php if($rujukan['response']['rujukan']['jnsPelayanan'] == 1){echo'<option value="2">Rawat Jalan</option>';}else{echo'<option value="1">Rawat Inap</option>';} ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tipe</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?php if($rujukan['response']['rujukan']['tipeRujukan'] == 0){ ?>
							<label><input type="radio" name="rbrujukan" value="0" id="rbpenuh" checked=""> Penuh</label>
							<label><input type="radio" name="rbrujukan" value="1" id="rbpartial"> Partial</label>
							<label><input type="radio" name="rbrujukan" value="2" id="rbbalik"> Rujuk Balik (Non PRB)</label>
						<?php }else if($rujukan['response']['rujukan']['tipeRujukan'] == 1){ ?>
							<label><input type="radio" name="rbrujukan" value="0" id="rbpenuh" > Penuh</label>
							<label><input type="radio" name="rbrujukan" value="1" id="rbpartial" checked=""> Partial</label>
							<label><input type="radio" name="rbrujukan" value="2" id="rbbalik"> Rujuk Balik (Non PRB)</label>
						<?php }else{ ?>
							<label><input type="radio" name="rbrujukan" value="0" id="rbpenuh" > Penuh</label>
							<label><input type="radio" name="rbrujukan" value="1" id="rbpartial" > Partial</label>
							<label><input type="radio" name="rbrujukan" value="2" id="rbbalik" checked=""> Rujuk Balik (Non PRB)</label>
						<?php } ?>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa Rujukan</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?= Select2::widget([
							'name' => 'icdx',
							'id'=>'kdiagnosa',
							'options' => ['placeholder' => 'Cari ICD X .....'],
							'value' => $rujukan['response']['rujukan']['diagRujukan'].' - '.$rujukan['response']['rujukan']['namaDiagRujukan'],
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
						]);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Di Rujuk Ke</label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="input-group">
							<input type="text" class="form-control" value='<?= $rujukan['response']['rujukan']['namaPpkDirujuk'] ?>' id="txtnmppkdirujuk" name='namappk' placeholder="nama ppk rujuk"  disabled="">
							<input type="hidden" id="txtkdppkdirujuk" name='kodeppk'value='<?= $rujukan['response']['rujukan']['ppkDirujuk'] ?>'>
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
							<input type="text" class="form-control" id="txtnmpoli" value='<?= $rujukan['response']['rujukan']['namaPoliRujukan'] ?>' name='namapoli' placeholder="spesialis atau subspesialis" disabled="">
							<input type="hidden" id="txtkdpoli" value='<?= $rujukan['response']['rujukan']['poliRujukan'] ?>' name='kodepolii'>
						</div>
					</div>


				</div>
				
				<div class="form-group">
					<label class="col-md-3 col-sm-3 col-xs-12 control-label">Catatan Rujukan</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<textarea type="text" class="form-control" name='catatanrujukan' value='' id="txtketerangan"><?= $rujukan['response']['rujukan']['catatan'] ?></textarea>
					</div>

				</div>
				<input type='hidden' value='<?= $rujukan['response']['rujukan']['tglRencanaKunjungan'] ?>'  name='tglrencanaRujuk'id='tglrencanaRujuk'>
			
			<!-- obat -->
		</div>

		<div class="box-footer">
			<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 <?= Html::submitButton('Update', ['class' => 'btn btn-primary','id'=>'simpan']) ?>
					<a id='hapus'  href='<?= Url::to(['/rujukan-faskes/hapus?norujukan='.$rujukan['response']['rujukan']['noRujukan']])?>'  class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</a>
					<a id="btnBatal" type="button" href='<?= Url::to(['/rujukan-faskes'])?>' class="btn btn-default pull-right"><i class="fa fa-undo"></i> Batal</a>
				</div>
			</div>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>
</div>
</div>
<input type='hidden' id='inputpartial'>
<div id='kedua'>
	<?=  $this->render('_listppk') ?>
	
</div>

<?php 
// $urlShowAll = Url::to(['rujukan/show-data']);
$this->registerJs("
jenisrujukan = '{$rujukan['response']['rujukan']['tipeRujukan']}';
if(jenisrujukan == 1){
	$('#divPoli').hide();
}
$('#kedua').hide();
$('#btnApprovePPK').hide();
	$('#cbpelayanan').on('change',function(e) {
		layanan = $('#cbpelayanan').val();
		if(layanan == 1){
			$('#divPoli').hide();
		}else{
			$('#divPoli').show();
		}
	});
	$('#rbpartial').on('change',function(e) {
		$('#inputpartial').val(1);
		$('#txtnmppkdirujuk').val('');
		$('#tglrencanaRujuk').val('');
		$('#txtkdppkdirujuk').val('');
		$('#divPoli').hide();
		$('#btnCariPPKRujukan').show();
	});
	$('#rbpenuh').on('change',function(e) {
		$('#inputpartial').val(0);
		$('#txtnmppkdirujuk').val('');
		$('#tglrencanaRujuk').val('');
		$('#txtnmpoli').val('');
		$('#txtkdpoli').val('');
		$('#txtkdppkdirujuk').val('');
		$('#divPoli').show();
		$('#btnCariPPKRujukan').show();
	});
	$('#rbbalik').on('change',function(e) {
		$('#inputpartial').val(0);
		$('#txtnmpoli').val('');
		$('#tglrencanaRujuk').val('');
		$('#txtkdpoli').val('');
		$('#txtkdppkdirujuk').val('');
		$('#txtnmppkdirujuk').val('{$peserta['response']['peserta']['provUmum']['nmProvider']}');
		$('#txtkdppkdirujuk').val('{$peserta['response']['peserta']['provUmum']['kdProvider']}');
		$('#divPoli').hide();
		$('#btnCariPPKRujukan').hide();
	});
	$('#btnCariPPKRujukan').on('click',function(e) {
		partial = $('#inputpartial').val();
		$('#txtnmppkjadwal').val('');
		$('#dataPpk').val('');
		$('#utama').hide();
		$('#kedua').show();
		if(partial == 1){
			$('#btnApprovePPK').show();
			
		}else{
			$('#btnApprovePPK').hide();
		}
	});
	$('#btnBatalJadwal').on('click',function(e) {
		
		$('#utama').show();
		$('#kedua').hide();
	});
	
	$('#simpan').on('click',function(e) {
		diagnosa = $('#kdiagnosa').val();
		ppkperujuk = $('#txtnmppkdirujuk').val();
		if(diagnosa == ''){
			alert('Kode Diagnosa kosong');
			event.preventDefault();
		}else if(ppkperujuk == ''){
			alert('PPK perujuk kosong');
			event.preventDefault();
		}else{
			age = confirm('Yakin Untuk menyimpan data');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}		
		}
	});
", View::POS_READY);
?>