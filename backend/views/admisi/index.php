<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\Dokter;
use common\models\RawatJenis;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admisi SPRI';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body' style='background:#222d32; -webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>
	<h3 style="color:#fff;">Ketersediaan Tempat Tidur</h3><hr/>
</div>
<div class='row'>
	<?php foreach($bed as $b){ ?>
		<a href='#'  data-toggle="modal" data-target="#modal-<?= $b['id']?>">
			<div class="col-md-4 col-sm-6 col-xs-12">
			  <div class="info-box" style='-webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>

				<span class="info-box-icon bg-navy"><i class='fa fa-bed'></i></span>
					
				<div class="info-box-content">
				  <span class="info-box-text"><?= $b['ruangan']?> ( <?= $b['bed']?> BED )</span>
				  <span class="info-box-number"> <?= $b['bed'] - $b['terisi']?>  Kosong</span> 
				    <span class="info-box-text">Kelas <?php if($b['kelas'] == 4){echo 'VIP';}else{echo $b['kelas'];} ?></span>
				</div> 
				
				
				<!-- /.info-box-content -->
			  </div> 
			  <!-- /.info-box -->
			</div>
		</a>
	<?php } ?>
</div>
<div class='box box-body'>
		<h2>SPRI</h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data SPRI'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data SPRI</h3>',
				'type'=>'success',
				
				'before'=>Html::a('<i class="fas fa-redo"></i> Buat SPRI ', ['create'], ['class' => 'btn bg-red']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_rm',
					'no_spri',
					'tgl_rawat',
					'dokter.nama_dokter',
					'pasien.nama_pasien',
					
					[
					'attribute' => 'idpoli', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idpoli == null){
								return '-';
							}else{
								return $model->poli->poli;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Poli::find()->all(), 'id', 'poli'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Poli'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idjenisrawat', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idjenisrawat == null){
								return '-';
							}else{
								return $model->jenisrawat->jenis;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RawatJenis::find()->all(), 'id', 'jenis'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Rawat'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}{delete}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												$url);
									
								},
								'delete' => function ($url,$model) {
										return Html::a(
												'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
												$url,
												[
												'title' => Yii::t('yii', 'Delete'),
												'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
												'data-method' => 'post',
												]);
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
		
	</div>