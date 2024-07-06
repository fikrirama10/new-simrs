<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\RadiologiHasilfoto;
use common\models\RadiologiHasildetail;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use slavkovrn\prettyphoto\PrettyPhotoWidget;
$rad = RadiologiHasildetail::findOne($r->id);
$hasilFoto = RadiologiHasilfoto::find()->where(['idhasil'=>$rad->id])->all();
$url = Yii::$app->params['baseUrl']."dashboard/rest/list-foto?id=".$rad->id;
$content = file_get_contents($url);
$json = json_decode($content, true);
?>
<table class='table table-bordered'>
	<tr>
		<th>Klinis</th>
		<th>:</th>
		<td><?= $rad->klinis?></td>
	</tr>
	<tr>
		<th>Hasil</th>
		<th>:</th>
		<td><?= $rad->hasil?></td>
	</tr>
	<tr>
		<th>Kesan</th>
		<th>:</th>
		<td><?= $rad->kesan?></td>
	</tr>
</table>
<div class='row'>
	<div class='col-md-4'>
		<?php if($hasilFoto){ ?>
			<?= PrettyPhotoWidget::widget([
			'id'     =>'prettyPhoto',   // id of plugin should be unique at page
			'class'  =>'galary img-thumbnail',        // class of plugin to define a style
			'width' => '100%',           // width of image visible in widget (omit - initial width)
			'height' => '300px',        // height of image visible in widget (omit - initial height)
			'images' => $json,
			]) ?>
		<?php }else{ ?>
			<h3>Belum ada Foto di upload</h3>
		<?php } ?>
	</div>
</div>
