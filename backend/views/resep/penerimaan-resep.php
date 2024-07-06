<div class='box'>
	<div class='box-header with-border'><h3>Penerimaan Farmasi</h3></div>
	<div class='box-body'>
		<div class='row'>
		<div class='col-md-6'>
			<table class='table'>
				<tr>
					<td>Start</td>
					<td>End</td>
				</tr>
				<tr>
					<td><input type='date' id='start-tgl' class='form-control'></td>
					<td><input type='date' id='end-tgl' class='form-control'></td>
				</tr>
			</table>
		</div>
		</div>
		<div class='row'>
			<div class="col-lg-6 col-xs-6">
				<div class="small-box" style="background:#b7b7b7;">
				<div class="inner">
				<h3 style="color:#fff;" class="text-center">Rp. <?= Yii::$app->algo->IndoCurr($json['totalYanmas'])?></h3>	
				<p style="color:#fff;" class="text-center">Pemasukan Umum</p>
				</div>	
				<div class="icon ">
				<i class="fa fa-money"></i>
				</div>		
				<a class="small-box-footer">

				</a> 
				</div>
			</div>
			<div class="col-lg-6 col-xs-6">
				<div class="small-box" style="background:#54ad6b;">
				<div class="inner">
				<h3 style="color:#fff;" class="text-center">Rp. <?= Yii::$app->algo->IndoCurr($json['totalBpjs'])?></h3>	
				<p style="color:#fff;" class="text-center">Pemasukan BPJS</p>
				</div>	
				<div class="icon ">
				<i class="fa fa-money"></i>
				</div>		
				<a class="small-box-footer">

				</a> 
				</div>
			</div>
		</div>
	</div>
</div>