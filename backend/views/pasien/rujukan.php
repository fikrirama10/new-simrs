<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\DataPekerjaan;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pasiens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'><h3>RUJUKAN PASIEN</h3></div>
		<div class='box box-body'>
			<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-barcode"></i> Masukan NO RM PASIEN </h4>
                Silahkan scan dengan scaner barcode atau ketik manual No RM
              </div>
			<input type='text' id='kode-pasien' autofocus class='form-control'>
		<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
		</div>
		<div id='pasien-ajax'></div>
		</div>
	</div>
	
</div>

<?php 
$urlShowAll = Url::to(['pasien/show-rujuk']);
$this->registerJs("
	$('#kode-pasien').on('keypress',function(e) {
		kode = $('#kode-pasien').val();
		if(e.which === 13){
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+kode,
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
		}
	});
", View::POS_READY);
?>