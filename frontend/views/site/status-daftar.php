<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
$this->title = 'Kuota Pasien';
?>

<div class='container'>
	
	<div class='row'>
		<div class='col-md-12'>
			<h4>Cek Status Daftar</h4>
			<hr>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-5'>
			<div class='form-group'>
				<label>Nomor Register</label>
				<input class='form-control' type ='text' id='pasien-id'>
			</div>
		</div>	
		
		<div class='col-md-2'>
		<label style='color:#fff;'>`</label>
		<br>
		<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cari</a></div>
	</div>
	<div class='row'>
		<div class='col-md-12'>
			<div id='pasien-ajax'></div>
		</div>
	</div>
</div>
<?php
$urlShowAll = Url::to(['site/show-daftar']);
$this->registerJs("
	
	$('#show-all').on('click',function(){
			id = $('#pasien-id').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});


	
           
	

", View::POS_READY);
?>