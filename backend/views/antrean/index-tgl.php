<?php 
    use yii\helpers\Url; 
?>
<div class="box">
    <div class="box-header">
        <h3>List Antrean</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Poli</th>
                <th>Taks 3(Daftar)</th>
                <th>Taks 4(Antri Poli)</th>
                <th>Taks 5(Selesai Poli)</th>
                <th>Taks 6(Antri Farmasi)</th>
                <th>Taks 7(Selesai Farmasi)</th>
                <th>Aksi</th>
            </tr>
            <?php if($json['data']['code'] == 200){ 
                $no=1;
                foreach($json['response'] as $js):
                // if($js['status'] == 'Belum dilayani' || $js['status'] == 'Sedang dilayani'){
                $rm = Yii::$app->hfis->searchForRm($js['norekammedis']);
                $taks3 = Yii::$app->hfis->searchtaksForId($js['kodebooking'],3);
                $taks4 = Yii::$app->hfis->searchtaksForId($js['kodebooking'],4);
                $taks5 = Yii::$app->hfis->searchtaksForId($js['kodebooking'],5);
                $taks6 = Yii::$app->hfis->searchtaksForId($js['kodebooking'],6);
                $taks7 = Yii::$app->hfis->searchtaksForId($js['kodebooking'],7);
                ?>
                <tr>
                    <td><?= $no++ ?></td> 
                    <td><?= $js['norekammedis'] ?></td>
                    <td><?= isset($rm->nama_pasien) ? $rm->nama_pasien:'' ?></td>
                    <td><?= $js['kodepoli'] ?> (<?= $js['noantrean'] ?>)</td>
                    <td><?= $taks3 ?> </td>
                    <td><?= $taks4 ?> </td>
                    <td><?= $taks5 ?> </td>
                    <td><?= $taks6 ?> </td>
                    <td><?= $taks7 ?> </td> 
                    <td>
                        <?php 
                            if($taks3 == 0){
                                echo "<a class='btn btn-primary' href='".Url::to(['antrean-update?kode='.$js['kodebooking'].'&taks=3'])."'>Update 3</a>";
                            }else{
                                if($taks4 == 0){
                                    echo "<a class='btn btn-success' href='".Url::to(['antrean-update?kode='.$js['kodebooking'].'&taks=4'])."'>Update 4</a>"; 
                                }else{
                                    if($taks5 == 0){
                                        echo "<a class='btn btn-info' href='".Url::to(['antrean-update?kode='.$js['kodebooking'].'&taks=5'])."'>Update 5</a>"; 
                                    }else{
                                        if($taks6 == 0){
                                            echo "<a class='btn btn-default' href='".Url::to(['antrean-update?kode='.$js['kodebooking'].'&taks=6'])."'>Update 6</a>"; 
                                        }else{
                                            if($taks7 == 0){
                                                echo "<a class='btn btn-warning' href='".Url::to(['antrean-update?kode='.$js['kodebooking'].'&taks=7'])."'>Update 7</a>"; 
                                            }else{
                                                echo '<a class="badge badge-primary">Selesai</a>';
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </td>
                </tr>
                <?php //} ?>
            <?php endforeach; ?>
            <?php }?>
        </table>
    </div>
</div>