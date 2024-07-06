<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\models\DaftarUmumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Umums';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daftar-umum-index">
	
    <?php $form = ActiveForm::begin(); ?>

   
	<label>Poliklinik</label>
	<select class="form-control" id='daftarumumidpoli' name="DaftarUmum[idpoli]">
		<option value='0'>-- Pilih Poli --</option>
		<?php foreach($data_poli as $poli): ?>
			<option value='<?= $poli['id']?>'><?= $poli['poli']?></option>
		<?php endforeach; ?>
	</select>
	<br>
	<div id='pasien-poli'></div>
   
<?php ActiveForm::end(); ?>
</div>

<?php
$urlShowAll = Url::to(['daftar-umum/show']);
$this->registerJs("
	
	$('#daftarumumidpoli').on('change',function(){
			poli = $('#daftarumumidpoli').val();
			//$('#ipoli').val(poli);
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli,
				// beforeSend: function(){
				// Show image container
				//$('#loading').show();
				// },
				success: function (data) {
					$('#pasien-poli').html(data);
					
					console.log(data);
					
				},
				// complete:function(data){
				// Hide image container
				// $('#loading').hide();
				// }
			});
		
	});


	
           
	

", View::POS_READY);
?>