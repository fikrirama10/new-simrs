<?php
use common\models\Poli;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
use yii\bootstrap\Modal;
use dosamigos\chartjs\ChartJs;
?>
<form action="<?= Url::to(['klpcm/klpcm-print-laporan']) ?>" target="_blank" method="get">
<div class="box">
    <div class='box-header with-border'>
        <h4>Laporan KLPCM</h4><br>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" id="bulan">
                        <option value="">Pilih Bulan</option>
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
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class='row'>
                <div class='col-md-12'>
                    <br>
                    <a class='btn btn-info' id='cek'>Cek</a>
                    <button type="submit" class='btn btn-warning' id='cek'>Print</button> 
                </div>
            </div>
        </div>
    </div>
    <div id='laporan'></div>
    <!-- <div class="box-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Poli</th>
                    <th colspan="2">Keterbacaan</th>
                    <th colspan="2">Kelengkapan</th>
                </tr>
                <tr>
                    <th>Terbaca</th>
                    <th>Tidak Terbaca</th>
                    <th>Lengkap</th>
                    <th>Tidak Lengkap</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($json as $j) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $j['poli'] ?></td>
                        <td><?= $j['terbaca_persen'] ?> %</td>
                        <td><?= $j['tidak_terbaca_persen'] ?> %</td>
                        <td><?= $j['lengkap_persen'] ?> %</td>
                        <td><?= $j['tidak_lengkap_persen'] ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> -->
</div>
</form>
<?php 
$urlShowAll = Url::to(['klpcm/klpcm-show-laporan']);
$this->registerJs("	
	$('#cek').on('click',function(){
		$('#laporan').hide();
		start = $('#bulan').val();
		end = $('#tahun').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'bulan='+start+'&tahun='+end,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#laporan').show();
					$('#laporan').animate({ scrollTop: 0 }, 200);
					$('#laporan').html(data);
					
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