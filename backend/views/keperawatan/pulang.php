<div class="row">
	<div class="col-md-4">
	<div class="box box-widget widget-user">
		<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
		</div>
	
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a title="Profile Peserta" href="#tab_1" data-toggle="tab"><span class="fa fa-user"></span></a></li>
				
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<span class="fa fa-sort-numeric-asc"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->nik?></span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-credit-card"></span>  <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->no_bpjs?></span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->tgllahir ?>  (<?= $pasien->usia_tahun?>th)</span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->tglmasuk ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-user-md"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->dokter->nama_dokter ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-bed"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->ruangan->nama_ruangan ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa  fa-money"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->bayar->bayar ?> </span>
						</li>
					
					</ul>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab_2">
					
				</div>
				<div class="tab-pane" id="tab_3">
					
					<div>
						<button type="button" id="btnHistori" class="btn btn-xs btn-default btn-block"><span class="fa fa-cubes"></span> Histori</button>
					</div>
				</div>
			</div>
			<!-- /.tab-content -->
		</div>
		</div>
	</div>
	<div class="col-md-8"></div>
</div>