<?php
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class='row'>
	<div class='col-md-3'>
		<div class="box box-solid box-primary">
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
					<li><a title="No.SEP"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnosep"><?= $sep['response']['noSep']?></label></a></li>
					<li><a title="Tgl.SEP"><i class="fa fa-calendar"></i> <label id="lbltglsep"><?= $sep['response']['tglSep']?></label></a></li>
					<li><a title="Jns.Pelayanan"><i class="fa fa-medkit"></i> <label id="lbljenpel"><?= $sep['response']['jnsPelayanan']?></label></a></li>
					<li><a title="Poli"><i class="fa fa-bookmark-o"></i> <label id="lblpoli"><?= $sep['response']['poli']?></label></a></li>
					<li><a title="Diagnosa"><i class="fa fa-heartbeat"></i> <label id="lbldiagnosa"><?= $sep['response']['diagnosa']?></label></a></li>
				</ul>
				<label id="lbltglplgsep" class="hidden">2022-06-20</label>
			</div>
			<!-- /.box-body -->
		</div>
		
		<div class="box box-solid box-success">
			<div class="box-header with-border">
				<span><i class="fa fa-hospital-o"> Asal Rujukan SEP</i> </span>
				<div class="box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse">
						<i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li><a title="No.Rujukan"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnorujukan"><?= $rujukan['response']['rujukan']['noKunjungan']?></label></a></li>
					<li><a title="Masa Aktif Rujukan"><i class="fa fa-calendar"></i> <label id="lbltglrujukan"><?= $rujukan['response']['rujukan']['tglKunjungan']?> s/d <?= date('Y-m-d',strtotime('+90 day',strtotime($rujukan['response']['rujukan']['tglKunjungan'])))?></label></a></li>
					<li><a title="Faskes Asal Rujukan"><i class="fa fa-search"></i> <label id="lblfaskesasalrujukan"><?= $rujukan['response']['rujukan']['provPerujuk']['nama']?> (<?= $rujukan['response']['rujukan']['provPerujuk']['kode']?>)</label></a></li>
				</ul>
			</div>
			<!-- /.box-body -->
		</div>
		
		<div class="box box-solid box-warning">
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
					<li><a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu"><?= $peserta['response']['peserta']['noKartu']?></label></a></li>
					<li><a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta"><?= $peserta['response']['peserta']['nama']?></label></a></li>
					<li><a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst"><?= $peserta['response']['peserta']['tglLahir']?></label></a></li>
					<li><a title="Kelamin"><i class="fa fa-intersex  text-blue"></i> <label id="lbljkpst"><?= $peserta['response']['peserta']['sex']?></label></a></li>
					<li><a title="Kelas Peserta"><i class="fa fa-user  text-blue"></i> <label id="lblklpst"><?= $peserta['response']['peserta']['hakKelas']['keterangan']?></label></a></li>
					<li><a title="PPK Asal Peserta"><i class="fa fa-user-md  text-blue"></i> <label id="lblppkpst"><?= $peserta['response']['peserta']['provUmum']['nmProvider']?> - <?= $peserta['response']['peserta']['provUmum']['kdProvider']?></label></a></li>
				</ul>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
	<div class="col-md-9">
            <div class="box box-primary">
			    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
                <div class="box-header with-border">
                    <i class="fa fa-battery-half"></i>
                    <small class="pull-right">
                        <label style="font-size:medium" id="lblnorujukan"></label>
                    </small>
                </div>
                <div class="box-body">
                
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl. Rencana Kontrol / Inap</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="input-group date">
                                    <input type="date" class="form-control datepicker_rencana" name='rencana' min='<?= date('Y-m-d')?>' value='<?= date('Y-m-d')?>' id="txttglrencanakontrol" placeholder="yyyy-MM-dd" maxlength="10">
                                    <span class="input-group-btn">
                                        <a id='cekPoli' class="btn btn-success btn-sm">
										Cek
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <select class="form-control" id="cbpelayanan" name='cbpelayanan'onchange="clearFormRencanaKontrol();" disabled="">
                                    <option value="2">Rawat Jalan</option>
                                    <option value="1">Rawat Inap</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
							<div class='col-md-12'>
								<div id='listPoli'></div>
							</div>
                        </div>
						 <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Poli</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="namapoli" disabled="" placeholder="Poli">
                                <input type="hidden" class="form-control" name='kodepoli' id="kodepoli" placeholder="" value="">
                            </div>
                        </div>
						 <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="namadpjp" disabled="" placeholder="DPJP">
                                <input type="hidden" class="form-control"  name='kodedpjp' id="kodedpjp" placeholder="" value="">
                            </div>
                        </div>

                      

                  
                    <!-- obat -->
                </div>

                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class='btn btn-success'>Simpan</button>
                        </div>
                    </div>
                </div>
				<?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
<?php 
$urlKunjungan = Url::to(['surat-kontrol/show-poli']);
$this->registerJs("
	$('#cekPoli').on('click',function(e) {
		tgl = $('#txttglrencanakontrol').val();
		nosep = '{$sep["response"]["noSep"]}';
		$.ajax({
			type: 'GET',
			url: '{$urlKunjungan}',
			data: 'tgl='+tgl+'&nosep='+nosep,
			
			success: function (data) {
				$('#listPoli').html(data);
				
				console.log(data);
				
			},
			
		});
	});
", View::POS_READY);

?>