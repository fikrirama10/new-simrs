<div class='col-md-12'>
	<div class='box box-body'>
		<div class='text-center'>
			<h1 class="display-1">Antrian Poli <?= $poli->poli?></h1>
			<?php if($rawat_one){ ?>
				<p style='font-size:200px;'><?= $rawat_one->poli->kode_antrean.'-'.substr($rawat_one->no_antrian,-3) ?></p>
			<?php } ?>
		</div>
	</div>
</div>
<div class='col-md-6'>
	<div class='box box-body'>
		<h3>Antrian Selanjutnya</h3>
		<?php if($rawat_one){ ?>
		<table>
			<?php foreach($rawat as $r){ ?>
			<tr>
				<th><h2><?= $r->poli->kode_antrean.'-'.substr($r->no_antrian,-3) ?></h2></th>
			</tr>
			<?php } ?>
		</table>
		<?php } ?>
	</div>
</div>