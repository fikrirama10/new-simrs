<?php
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$response = Yii::$app->bpjs->surat_kontrol($kontrol);
// print_r($response);
?>
<div class='row'>
	<div class="col-md-12">
            <div class="box box-primary">
			    <?php $form = ActiveForm::begin([
					'action' => ['surat-kontrol/post-update?id='.$kontrol],
					'method' => 'post',
					'options' => [
						'enctype' => 'multipart/form-data',
						'class' => 'form-horizontal'
					]
				]); ?>
                <div class="box-header with-border">
                   
                </div>
                <div class="box-body">
                
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl. Rencana Kontrol / Inap</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="input-group date">
                                    <input type="date" class="form-control datepicker_rencana" name='rencana<?= $kontrol?>' min='<?= date('Y-m-d')?>' value='<?= $response['response']['tglRencanaKontrol']?>' id="txttglrencanakontrol<?= $kontrol?>" placeholder="yyyy-MM-dd" maxlength="10">
                                    <span class="input-group-btn">
                                        <a id='cekPoli<?= $kontrol?>' class="btn btn-success btn-sm">
										Cek
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control" id="cbpelayanan" value='' name='cbpelayanan<?= $kontrol?>' >
									<option selected="selected" value="<?= $response['response']['jnsKontrol']?>"><?php if($response['response']['jnsKontrol'] == 1){echo'Rawat Inap';}else{echo'Rawat Jalan';}?></option>
									<?php if($response['response']['jnsKontrol'] == 1){echo'<option value="2">Rawat Jalan</option>';}else{echo'<option value="1">Rawat Inap</option>';} ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
							<div class='col-md-12'>
								<div id='listPoli<?= $kontrol?>'></div>
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Surat Kontrol</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="nokontrol" name='nokontrol<?= $kontrol?>' disabled="" placeholder="Surat Kontrol" value=<?= $kontrol?>>
                            </div>
                        </div>
						 <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Poli</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="namapoli<?= $kontrol?>" disabled="" required placeholder="Poli" value='<?= $response['response']['namaPoliTujuan']?>'>
                                <input type="hidden" class="form-control" name='kodepoli<?= $kontrol?>' id="kodepoli<?= $kontrol?>" placeholder="" value="<?= $response['response']['poliTujuan']?>">
                                <input type="hidden" class="form-control" name='nosep<?= $kontrol?>' id="nosep<?= $kontrol?>" placeholder="" value="<?= $response['response']['sep']['noSep']?>">
                            </div>
                        </div>
						 <div class="form-group">
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="namadpjp<?= $kontrol?>"  required disabled="" value='<?= $response['response']['namaDokter']?>' placeholder="DPJP">
                                <input type="hidden" class="form-control"  name='kodedpjp<?= $kontrol?>' id="kodedpjp<?= $kontrol?>" placeholder="" value="<?= $response['response']['kodeDokter']?>">
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
	$('#txttglrencanakontrol{$kontrol}').on('change',function(e) {
		$('#kodedpjp{$kontrol}').val('');
		$('#namadpjp{$kontrol}').val('');
		$('#namapoli{$kontrol}').val('');
		$('#kodepolipoli{$kontrol}').val('');
	});
	$('#cekPoli{$kontrol}').on('click',function(e) {
		tgl = $('#txttglrencanakontrol{$kontrol}').val();
		nosep = '{$response["response"]["sep"]["noSep"]}';
		nokontrol = '{$kontrol}';
		$.ajax({
			type: 'GET',
			url: '{$urlKunjungan}',
			data: 'tgl='+tgl+'&nosep='+nosep+'&kontrol='+nokontrol,
			
			success: function (data) {
				// alert(nokontrol);
				$('#listPoli{$kontrol}').html(data);
				
				console.log(data);
				
			},
			
		});
	});
", View::POS_READY);

?>