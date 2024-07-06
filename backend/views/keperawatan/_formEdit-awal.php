<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Soapkesadaran;
use common\models\Soapkeadaan;
use common\models\DataDatang;
use kartik\time\TimePicker;
?>
<?php $form = ActiveForm::begin(['action' => ['/keperawatan/edit-awal?id='.$awalinap->id.'&idrawat='.$model->id],]); ?>
<?= $form->field($awalinap, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
<?= $form->field($awalinap, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
<?= $form->field($awalinap, 'idpetugas')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
<?= $form->field($awalinap, 'iddatang')->dropDownList(ArrayHelper::map(DataDatang::find()->all(), 'id', 'datang'),['prompt'=>'- Cara datang -','required'=>true])->label('Cara Datang')?>
<?= $form->field($awalinap, 'jam_masuk')->widget(TimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
			'minuteStep' => 1,
			'secondStep' => 5,
			'required'=>true
		]
	]); ?>
<?= $form->field($awalinap, 'anamnesa')->textArea(['rows' => 5])->label('Anamnesa') ?>
<h6>Keadaan Umum</h6>
		<?= $form->field($awalinap, 'keadaan')->radioList(ArrayHelper::map(SoapKeadaan::find()->all(), 'id','keadaan'),['required'=>true])->label(false)?>
		<h6>Kesadaran</h6>
		<?= $form->field($awalinap, 'kesadaran')->radioList(ArrayHelper::map(Soapkesadaran::find()->all(), 'id','kesadaran'),['required'=>true])->label(false)?>
		<div class='row'>
				<div class='col-md-12'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Distole' value='<?= $awalinap->distole ?>' name='RawatAwalinap[distole]' id="rawatawalinap-distole">
					<input type='text' class='form-control' value='<?= $awalinap->sistole ?>' placeholder='Sistole' name='RawatAwalinap[sistole]' id="rawatawalinap-sistole">
					<span class="input-group-addon" id="basic-addon1">mmHg</span>
					</div>
				</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' value='<?= $awalinap->suhu ?>' placeholder='Suhu' name='RawatAwalinap[suhu]' id="rawatawalinap-suhu">
					<span class="input-group-addon" id="basic-addon1">C</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' value='<?= $awalinap->respirasi ?>' placeholder='Respirasi' name='RawatAwalinap[respirasi]' id="rawatawalinap-respirasi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
			
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' value='<?= $awalinap->spo2 ?>' placeholder='SpO2' name='RawatAwalinap[spo2]' id="rawatawalinap-spo2">
					<span class="input-group-addon" id="basic-addon1">%</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Nadi' value='<?= $awalinap->nadi ?>' name='RawatAwalinap[nadi]' id="rawatawalinap-nadi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Berat' value='<?= $awalinap->berat ?>' name='RawatAwalinap[berat]' id="rawatawalinap-berat">
					<span class="input-group-addon" id="basic-addon1">Kg</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Tinggi' name='RawatAwalinap[tinggi]' value='<?= $awalinap->tinggi ?>' id="rawatawalinap-tinggi">
					<span class="input-group-addon" id="basic-addon1">cm</span>
					</div>
				</div>
			</div>
			<?= $form->field($awalinap, 'alergi')->textArea(['rows' => 2]) ?>
			<hr>
			<div class="form-group">
				<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			</div>
<?php ActiveForm::end(); ?>