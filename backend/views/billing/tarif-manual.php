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
<div class='row'>
	<div class='col-md-3'>
		<div class='box box-body'>
		<?= DetailView::widget([
			'model' => $pasien,
			'attributes' => [
				'no_rm',
				'nama_pasien',
				'nohp',
			],
		]) ?>
		</div>
	</div>
	<div class='col-md-9'>
		<div class='box box-body'>
			<?=
				$this->render('_formManual', ['model'=>$model,'tambah_dua'=>$tambah_dua])
			?>
		</div>
	</div>
</div>
