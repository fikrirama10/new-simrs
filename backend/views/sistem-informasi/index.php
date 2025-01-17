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
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<h3>Sistem Informasi RS</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-6">
						<label>Start Date</label>
						<?= DatePicker::widget([
						'id' => 'start_date',
						'name' => 'start_date',
						'value' => $start,
						'options' => ['placeholder' => 'Select issue date ...'],
						'removeButton' => false,
						'pluginOptions' => [
							'format' => 'yyyy-mm-dd',
							'todayHighlight' => true
						]
						]); ?>
					</div>
					<div class="col-sm-6">
						<label>End Date</label>
						<?= DatePicker::widget([
						'id' => 'end_date',
						'name' => 'end_date',
						'value' => $end,
						'options' => ['placeholder' => 'Select issue date ...'],
						'removeButton' => false,
						'pluginOptions' => [
							'format' => 'yyyy-mm-dd',
							'todayHighlight' => true
						]
						]); ?>
					</div>
				
				</div>
				<hr>
			<div id='show-informasi'></div>
			</div>
			
		</div>
	</div>
</div>
<?php 
$urlShowAll = Url::to(['sistem-informasi/show']);
$this->registerJs("	
	$('#end_date').on('change',function(){
		$('#show-informasi').hide();
		end = $(this).val();
		start = $('#start_date').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'start='+start+'&end='+end,
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
