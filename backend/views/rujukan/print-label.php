<div style='width:100%; float:left; text-align:center; font-size:12;font-family:arial;'>
	<div style=' border-bottom:1px; solid ;width:100%;'>
		PANGKALAN TNI AU SULAIMAN<br>
		RUMAH SAKIT<hr>
	</div>
	<div style='letter-spacing:1px;'><b>Nomor : <i><?= substr($model->kode_rujukan,7)?> / <?= date('Y',strtotime($model->tgl_rujuk))?> / <?= Yii::$app->rujukan->getRomawi(date('n',strtotime($model->tgl_rujuk))) ?>/ RS</i></b></div>
	</div>	