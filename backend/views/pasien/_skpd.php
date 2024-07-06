<div class="box-header with-border">
		<div class="row">
			<div class="col-sm-3">						
				<label>Bulan</label>
				<select class="form-control" name="Bulan" id="bulan">
				  <option value="01">Januari</option>
				  <option value="02">Februari</option>
				  <option value="03">Maret</option>
				  <option value="04">April</option>
				  <option value="05">Mei</option>
				  <option value="06">Juni</option>
				  <option value="07">Juli</option>
				  <option value="08">Agustus</option>
				  <option value="09">September</option>
				  <option value="10">Oktober</option>
				  <option value="11">November</option>
				  <option value="12">Desember</option>
				</select>
			</div>
			<div class="col-sm-3">
				<label>Tahun</label>
				<select class="form-control" name="Tahun" id="tahun">
				    <option value="2024">2024</option>
				    <option value="2023">2023</option>
				  <option value="2022">2022</option>
				  <option value="2021">2021</option>
				  <option value="2020">2020</option>
				  <option value="2019">2019</option>
				</select>
			</div>
			<div class="col-sm-3">
				<label>Filter</label>
				<select class="form-control" name="Poli" id="filter">
					<option value="0">-- Pilih Jenis --</option>
							<option value="1">Tanggal Entry</option>
							<option value="2">Tanggal Rencana Kontrol</option>
					</select>
			</div>
		</div>
	</div>
	
		<div class="row">
			<div class="col-md-12">
				<br>
				<a class="btn btn-info" id="cek">Cek</a>
			</div>
		</div>
		<div id='show_skpd'></div>
</div>
<?php
use yii\helpers\Url;
use yii\web\View;
$urlList = Url::to(['pasien/show-skpd']);
$this->registerJs("

	
	$('#cek').on('click',function(){
			bulan = $('#bulan').val();
			tahun = $('#tahun').val();
			filter = $('#filter').val();
			idrawat = '{$rawat->id}';
			nokartu = '{$pasien->no_bpjs}';
			$.ajax({
				type: 'GET',
				url: '{$urlList}',
				data: 'bulan='+bulan+'&tahun='+tahun+'&filter='+filter+'&nokartu='+nokartu+'&idrawat='+idrawat,
				
				success: function (data) {
					$('#show_skpd').html(data);
					
					console.log(data);
					
				},
				
			});
	});

", View::POS_READY);
?>