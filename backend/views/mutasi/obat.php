<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
$start = (isset($_GET['start']))? $_GET['start'] : date('Y-m-d');
$end = (isset($_GET['end']))? $_GET['end'] : date('Y-m-d');
$cek = (isset($_GET['cek']))? $_GET['cek'] : 'today';
?>
<div class='box box-header'>
	<h4>LAPORAN MUTASI OBAT / ALKES</h4>
</div>
<div class='box box-body'>
		<div class="row">
		<div class="col-sm-4">
			<label>Start Date</label>
			<?= DatePicker::widget([
			'id' => 'start_date',
			'name' => 'start_date',
			'value' => $start,
			'options' => ['placeholder' => 'Select issue date ...'],
			'removeButton' => false,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'autoclose'=>true
			]
			]); ?>
		</div>
		<div class="col-sm-4">
			<label>End Date</label>
			<?= DatePicker::widget([
			'id' => 'end_date',
			'name' => 'end_date',
			'value' => $end,
			'options' => ['placeholder' => 'Select issue date ...'],
			'removeButton' => false,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'autoclose'=>true
			]
			]); ?>
		</div>
		<div class="col-sm-4">
			<label>JENIS GUDANG</label>
			<select class='form-control' id='gudang'>
				<option value = 0>PILIH GUDANG</option>
				<option value = 1>GUDANG</option>
				<option value = 2>FARMASI</option>
			</select>
		</div>

	</div>
</div>
<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
		</div>
<div id='show-informasi'></div>
</div>
<?php 
$urlShowAll = Url::to(['mutasi/show-obat']);
$this->registerJs("	
	$('#gudang').on('change',function(){
		$('#show-informasi').hide();
		idgudang = $(this).val();
		end = $('#end_date').val();
		start = $('#start_date').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'start='+start+'&end='+end+'&idgudang='+idgudang,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#show-informasi').show();
					$('#show-informasi').animate({ scrollTop: 0 }, 200);
					$('#show-informasi').html(data);
					
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
