<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatJenis;
use common\models\Poli;
use yii\bootstrap\Modal;
use common\models\Rawat;
$rawat = Rawat::find()->where(['idkunjungan'=>$kunjungan->idkunjungan])->andwhere(['<>','status',5])->limit(15)->all();
$ranap = Rawat::find()->where(['idjenisrawat'=>2])->andwhere(['idkunjungan'=>$kunjungan->idkunjungan])->one();
$day = date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))));
?>
<div class='row'>
<div class='col-md-4'>
<div class='box box-warning'>
	<div class='box-header with-border'><h3><a href='<?= Url::to(['pasien/'.$model->id])?>' class='btn btn-default'><i class="fa fa-backward"></i> </a> Data Pasien</h3></div>
	<div class='box-body'>
	<h4>Riwayat Pelayanan</h4>
	<table class='table table-bordered'>
		<?php $btn=0; foreach($rawat as $r): ?>
		<?php if($r->status == 5){$btn = "btn-danger "; }else{ $btn= "btn-vk";} ?>
		<tr>
			<th>
			<?php if($r->status == 5){ ?>
			
			<?php }else{ ?>
				<?php if(Yii::$app->user->identity->idpriv == 17){ ?>
					<a id='ranap-<?= $r->id ?>' class='btn btn-block btn-social <?= $btn ?>'><i class="fa <?= $r->jenisrawat->icon?>"></i><?= $r->idrawat ?>| <?= $r->jenisrawat->jenis?> - <?php if($r->idjenisrawat != 2){ ?><?= $r->poli->poli ?> <?php } ?></a>
					<input type='hidden' value ='<?= $r->id?>' id='input-ranap<?= $r->id ?>'>
				<?php }else{ ?>
					<a id='kunjungan-<?= $r->id ?>' class='btn btn-block btn-social <?= $btn ?>'><i class="fa <?= $r->jenisrawat->icon?>"></i><?= date('d/m/Y',strtotime($r->tglmasuk)) ?>| <?= $r->jenisrawat->jenis?> - <?php if($r->idjenisrawat != 2){ ?><?= $r->poli->poli ?> <?php } ?></a>
					<input type='hidden' value ='<?= $r->id?>' id='input-kunjungan<?= $r->id ?>'>
				<?php } ?>
			<?php } ?>
			</th>
		</tr>
		<?php 
			$urlInput = Url::to(['pasien/show-pelayanan']);
			$urlGetRanap = Url::to(['pasien/get-ranap']);
			$this->registerJs("
			$('#kunjungan-{$r->id}').on('click',function(){
				
				$('#hidden-pelayanan').hide();
				kode = $('#input-kunjungan{$r->id}').val();
				$.ajax({
					type: 'GET',
					url: '{$urlInput}',
					data: 'id='+kode,
					
					success: function (data) {
						$('#riwayat-pelayanan').show();
						$('#riwayat-pelayanan').animate({ scrollTop: 0 }, 200);
						$('#riwayat-pelayanan').html(data);
						
						console.log(data);
						
					},
				
				});
					
			}) ;
			$('#ranap-{$r->id}').on('click',function(){
				
				id = $('#input-ranap{$r->id}').val();
					$.ajax({
					type: 'POST',
					url: '{$urlGetRanap}',
					data: {id: id},
					dataType: 'json',
					success: function (data) {
						$('#form-ranap').show();
						if(data !== null){
							var res = JSON.parse(JSON.stringify(data));
							
							$('#rawat-idmasuk').val(res.id);
							$('#inputdata-ranap').val(res.idrawat);
							
							//$('#transaksidetail-harga-disp').val(format_money(parseInt(harga),''));
							// console.log(kode +' '+ idstok);
						}else{
							alert('data tidak ditemukan');
						}
					},
					error: function (exception) {
						alert(exception);
					}
				});	
			}) ;

		", View::POS_READY);
		?>
		<?php endforeach; ?>
	</table>
	<br>
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'no_rm',
			'nik',
			'no_bpjs',
			'nama_pasien',
			'tgllahir',
			'nohp',
			'usia_tahun',
		],
	]) ?>
	
	<br>
	
	</div>
</div>
</div>
<div class='col-md-8'>
	
	<div class='box box-primary'>
		<div id='hidden-pelayanan'>
		<div class='box-header with-border'><h3>Pelayanan</h3></div>
		<div class='box-body'>
			<?php if($ranap){ ?>
			<a href=''  data-toggle="modal" data-target="#mdModal">
			<div class="info-box" style="-webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
			-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
			box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);">
            <span class="info-box-icon bg-yellow"><i class="fa fa-bed"></i></span>
				
				<div class="info-box-content">
				  <span class="info-box-text">Dirawat</span>
				  <span class="info-box-number"><?= $ranap->ruangan->nama_ruangan?></span> 
				  <span class="info-box-text"><?= $ranap->kelas->kelas?></span>
				</div> 
			
			
            <!-- /.info-box-content -->
			  </div>
			  </a>
			<?php }else{ ?>
				<?php if($kunjungan->tgl_kunjungan == $day){ ?>
					<?php if(Yii::$app->user->identity->idpriv == 8){ ?>
					<?= $this->render('_form-rajal',[
						'pelayanan' => $pelayanan,
						'kunjungan' => $kunjungan,
						// 'bpjs' => $bpjs,
						'model' => $model,
					]) ?>
					<?php }else{ ?>
					<?= $this->render('_form-ranap',[
						'pelayanan' => $pelayanan,
						'kunjungan' => $kunjungan,
						// 'bpjs' => $bpjs,
						'model' => $model,
					]) ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
		</div>
		<div id='riwayat-pelayanan'></div>
	</div>
	
</div>
</div>

<?php
$urlShowAll = Url::to(['pasien/show-dokter']);
$urlShowRuangan = Url::to(['pasien/show-ruangan']);
$this->registerJs("
		$('#form-ranap').hide();
		$('#rawat-idpoli').on('change',function(){
			$('#pasien-ajax').hide();
		});
		$('#rawat-idjenisrawat').on('change',function(){
			$('#pasien-ajax').hide();
		});
		$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			poli = $('#rawat-idpoli').val();
			rm = $('#rawat-no_rm').val();
			jenis = $('#rawat-idjenisrawat').val();
			kunjungan = $('#tglkunjungan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&rm='+rm+'&jenis='+jenis+'&kunjungan='+kunjungan,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').animate({ scrollTop: 0 }, 200);
					$('#pasien-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});
	$('#show-ruangan').on('click',function(){
			$('#ruangan-ajax').hide();
			ruangan = $('#rawat-idruangan').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowRuangan}',
				data: 'id='+ruangan,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#ruangan-ajax').show();
					$('#ruangan-ajax').animate({ scrollTop: 0 }, 200);
					$('#ruangan-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);

Modal::begin([
	'id' => 'mdModal',
	'header' => '<h3>Rawat Inap</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_modalRanap', ['pindah'=>$pindah,'model'=>$model,'kunjungan'=>$kunjungan ]).'</div>';
 
Modal::end();
?>