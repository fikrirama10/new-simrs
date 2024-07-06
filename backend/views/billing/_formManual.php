<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\RawatBayar;
use common\models\Tarif;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($tambah_dua, 'tindakan')->textinput(['required'=>true])->label('Nama Tindakan')?>
			<?= $form->field($tambah_dua, 'tarif')->textinput(['required'=>true])->label('Tarif / Harga Rp.')?>
			<?= $form->field($tambah_dua, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label('Jenis Bayar')?>
		</div>
	</div>
	<?= $form->field($tambah_dua, 'idtransaksi')->hiddeninput(['value'=>$model->id])->label(false)?>
	<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>