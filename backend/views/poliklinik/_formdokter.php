<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use yii\bootstrap\Modal;
use common\models\SoapDiagnosajenis;
use common\models\SoapRajalicdx;
use common\models\KategoriDiagnosa;
use common\models\RawatRujukan;
use common\models\RawatSpri;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$rrujuk = RawatRujukan::find()->where(['idrawat'=>$model->id])->one(); 
$suratinap = RawatSpri::find()->where(['idrawat'=>$model->id])->one(); 
$urlTindakan = "http://localhost/simrs2021/dashboard/rest/tarif?jenis=".$model->idjenisrawat;
$urlRadiologi = "http://localhost/simrs2021/dashboard/rest/list-radiologi";
$urlLab = "http://localhost/simrs2021/dashboard/rest/list-lab";
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
	<div class='col-md-8'>
		<?php if($soapdoktercount){ ?>
		
		<table class='table table-bordered'>
			<tr>
				<th>Anamnesa</th>
				<td><?= $soapdoktercount->anamnesa ?> </td>
			</tr>
			<tr>
				<th>Planing</th>
				<td><?= $soapdoktercount->planing ?> </td>
			</tr>
			<tr>
				<th>Keterangan Rawat</th>
				<td><?= $model->rawatstatus->status ?> </td>
			</tr>
			<?php if($rrujuk){ ?>
		<tr>
				<th>Tujuan Rujuk</th>
				<td><?= $rrujuk->tujuan_rujuk ?>  <a href='<?= Url::to(['poliklinik/edit-rujuk?id='.$rrujuk->id])?>'  class='btn btn-success btn-xs'><span class='fa fa-pencil'></span></a></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan=2>
						<a href='<?= Url::to(['/poliklinik/edit-soap?id='.$soapdoktercount->id.'&jenis=1']);?>' class='btn btn-warning btn-xs'>Edit</a>
					<?php if($model->status == 8){ ?>
						<?php if(!$rrujuk){?>
						<a href='' data-toggle="modal" data-target="#mdRujuk" class='btn btn-success btn-xs'>Buat Rujukan</a>
						<?php }?>
					<?php }else if($model->status == 2){ ?>
						<?php if(!$suratinap){?>
						<a href='' data-toggle="modal" data-target="#mdSpri" class='btn btn-danger btn-xs'>Buat SPRI</a>
						<?php } ?>
					<?php } ?>
				</td>
			</tr>
		</table>

		<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Diagnosa</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($icdx, 'idjenisrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->idjenisrawat])->label(false) ?>
					<?= $form->field($icdx, 'idrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->id])->label(false) ?>
					<?= $form->field($icdx, 'idrm')->hiddeninput(['maxlength' => true, 'value'=>$model->pasien->id])->label(false) ?>
					<?= $form->field($icdx, 'iduser')->hiddeninput(['maxlength' => true, 'value'=>Yii::$app->user->identity->id])->label(false) ?>
					<?= $form->field($icdx, 'diagnosa')->textarea(['maxlength' => true, ])->label('Diagnosis Klinis') ?>
					<?= $form->field($icdx, 'icdx')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Cari ICD X .....'],
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
					])->label('ICD X');?>
					<?= $form->field($icdx, 'baru')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
					<?= $form->field($icdx, 'idjenisdiagnosa')->dropDownList(ArrayHelper::map(SoapDiagnosajenis::find()->all(), 'id', 'jenis'),['prompt'=>'--Jenis Diagnosa--','required'=>true])->label('Jenis Diagnosa')?>
				
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm-dokter']) ?>
			  </div>
			  <?php ActiveForm::end(); ?>
			</div>
		  </div>
		</div>
			
		<?php }else{ ?>
			<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($soapdokter, 'idjenisrawat')->hiddeninput(['maxlength' => true, 'value'=>$model->idjenisrawat])->label(false) ?>
				<?= $form->field($soapdokter, 'no_rm')->hiddeninput(['maxlength' => true, 'value'=>$model->no_rm])->label(false) ?>
				<?= $form->field($soapdokter, 'tgl_soap')->hiddeninput(['maxlength' => true, 'value'=>date('Y-m-d')])->label(false) ?>
				<?= $form->field($soapdokter, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
				<?= $form->field($soapdokter, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
				<?= $form->field($model, 'kat_penyakit')->dropDownList(ArrayHelper::map(KategoriDiagnosa::find()->all(), 'id', 'jenisdiagnosa'),['prompt'=>'--Kategori Penyakit--','required'=>true])->label('Kategori Penyakit')?>
				<?= $form->field($soapdokter, 'anamnesa')->textarea(['maxlength' => true,'rows'=>4,'required'=>true])->label() ?>
				<?= $form->field($soapdokter, 'planing')->textarea(['maxlength' => true,'rows'=>4,'required'=>true])->label() ?>
				<?= $form->field($model, 'status')->dropDownList(ArrayHelper::map(RawatStatus::find()->where(['ket'=>1])->all(), 'id', 'status'),['prompt'=>'--Cara Keluar--','required'=>true])->label('Keluar')?>
				<div class="form-group">
					<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
				</div>
			<?php ActiveForm::end(); ?>
		<?php } ?>
	</div>
	
</div>
	
<?php 
$urlShowAll = Url::to(['poliklinik/show-dokter']);
$this->registerJs("
	$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			poli = $('#rawatspri-idpoli').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli,
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
	$('#confirm-dokter').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#confirm-spri').on('click', function(event){
		tgl = $('#rawatspri-tgl_rawat').val();
		age = confirm('Pasien akan dirawat / dijadwalkan rawat inap pada '+ tgl +' ,Yakin Untuk menyimpan data ??');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#confirm2').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#confirm3').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#btn-diagnosa').on('click', function(event){
		age = confirm('Yakin Untuk Menghapus data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	
	

", View::POS_READY);
Modal::begin([
	'id' => 'mdRujuk',
	'header' => '<h3>Rujukan Pasien</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formRujukan', ['model'=>$model,'rujukan'=>$rujukan ]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'mdSpri',
	'header' => '<h3>Surat Permintaan Rawat Inap Pasien</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formSpri', ['model'=>$model,'spri'=>$spri]).'</div>';
 
Modal::end();
?>