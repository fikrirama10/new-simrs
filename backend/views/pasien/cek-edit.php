<?php
	use common\models\DokterKuota;
	use common\models\DokterJadwal;
	use yii\widgets\ActiveForm;
	use yii\web\View;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

<?php
	if($rawat->idpoli == $poli && $rawat->iddokter == $dokter && date('Y-m-d',strtotime($rawat->tglmasuk)) == $tgl){
?>
	<button type='submit' id='confirm2' class='btn btn-info pull-right'>Simpan</button>
	<?php } else if($rawat->idjenisrawat == 2){ ?>
	<button type='submit' id='confirm2' class='btn btn-info pull-right'>Simpan</button>
	<?php }else{ ?>
		<?php
			$cjadwal = DokterJadwal::find()->where(['iddokter'=>$dokter])->andwhere(['idpoli'=>$poli])->andwhere(['status'=>1])->andwhere(['idhari'=>date('N',strtotime($tgl))])->count();
			$jadwal = DokterJadwal::find()->where(['iddokter'=>$dokter])->andwhere(['idpoli'=>$poli])->andwhere(['status'=>1])->andwhere(['idhari'=>date('N',strtotime($tgl))])->one();
			if($cjadwal > 0){
				$ckuota = DokterKuota::find()->where(['iddokter'=>$dokter])->andwhere(['idpoli'=>$poli])->andwhere(['tgl'=>$tgl])->count();
				$kuota = DokterKuota::find()->where(['iddokter'=>$dokter])->andwhere(['idpoli'=>$poli])->andwhere(['tgl'=>$tgl])->one();
				if($ckuota < 1){
					echo "<button type='submit' id='confirm2' class='btn btn-info pull-right'>Simpan</button>";
				}else{
					if($kuota->sisa > 0){
						echo "<button type='submit' id='confirm2' class='btn btn-info pull-right'>Simpan</button>";
					}else{
						echo'<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-ban"></i> Alert!</h4>
								Kuota Tidak tersedia
							</div>';
					}
				}
			}else{
				echo '<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-ban"></i> Alert!</h4>
						Jadwal Dokter tidak tersedia
					</div>';
			}	
		?>
	<?php } ?>
	
	
	<?php
	$this->registerJs("
	$('#confirm2').on('click', function(event){
		age = confirm('Yakin mengedit data ??? ');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	", View::POS_READY);


	?>