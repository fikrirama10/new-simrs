<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\SettingSimrs;
use common\models\DokterSpesialis;
use common\models\Dokter;
use common\models\RuanganBed;
$setting = SettingSimrs::find()->count();
$spesialis = DokterSpesialis::find()->count();
$dokter = Dokter::find()->count();
$bed = RuanganBed::find()->count();
/* @var $this yii\web\View */
/* @var $searchModel common\models\SettingSimrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setting Simrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="setting-simrs-index">
	<div class='col-md-12'>
		
			
				<?php if($setting < 1 ){ ?>
					<div class='box'>
						<div class='box-header with-border'>
							<h4>Setting Simrs</h4>
						</div>
					<div class='box-body'>
					<a href='<?= Url::to(['setting-simrs/create'])?>' class="btn btn-app">
						<i class="fa fa-plus"></i> Tambah Setting
					</a>
					</div>
					<div class='box-footer'></div>
					</div>
				<?php }else{ 
					$simrs = SettingSimrs::findOne(3);
				?>
					<div class='row'>
						<div class='col-md-3'>
							<div class="box box-primary">
								<div class="box-body box-profile">
								<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/thumbnail/'.$simrs->logo_rs, ['alt'=>'no picture', 'class'=>'profile-user-img img-responsive img-circle'])?>

								<h3 class="profile-username text-center"><?= $simrs->nama_rs?></h3>

								<p class="text-muted text-center"><?= $simrs->direktur_rs?></p>

								<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
								<b>SPESIALIS</b> <a class="pull-right"><?= $spesialis?></a>
								</li>
								<li class="list-group-item">
								<b>TEMPAT TIDUR</b> <a class="pull-right"><?= $bed?></a>
								</li>
								<li class="list-group-item">
								<b>DOKTER</b> <a class="pull-right"><?= $dokter?></a>
								</li>
								</ul>

								<a href="<?= Url::to(['update?id='.$simrs->id])?>" class="btn btn-primary btn-block"><b>UPDATE</b></a>
								</div>
								<!-- /.box-body -->
							</div>
							
						</div>
					</div>
					<div class='col-md-3'>
						
					</div>
					
				<?php } ?>
			
			
	</div>
</div>
