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
                <!-- <th>Task</th> -->
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if ($json['data']['code'] == 200) {
                $no = 1;
                foreach ($json['response'] as $js) :
                    // if($js['status'] == 'Belum dilayani' || $js['status'] == 'Sedang dilayani'){
                    $rm = Yii::$app->hfis->searchForRm($js['norekammedis']);
                    $post = array(
                        'kodebooking' => $js['kodebooking'],
                    );
                    $taks = Yii::$app->hfis->list_taks($post);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $js['norekammedis'] ?></td>
                        <td><?= isset($rm->nama_pasien) ? $rm->nama_pasien : '' ?></td>
                        <td><?= $js['kodepoli'] ?> (<?= $js['noantrean'] ?>) <br> <?= $js['jampraktek']?></td>
                        <td>
                            <?php if($taks['data']['code'] == 200){ ?>
                            <ul>
                                <?php  foreach ($taks['response'] as $ts) : ?>
                                    <li>Taks Id : <?= $ts['taskid'] ?> (<?= $ts['wakturs']?>)</li>
                                <?php endforeach; ?>
                            </ul>
                            <?php } ?>
                        </td>
                        <td><?= $js['status']?></td>
                        <td>
                            <?php echo"<a class='btn btn-success' href='".Url::to(['antrean-update2?kode='.$js['kodebooking']])."'>Update Taks</a>"; ?>
                        </td>
                    </tr>
                    <?php //} 
                    ?>
                <?php endforeach; ?>
            <?php } ?>
        </table>
    </div>
</div>