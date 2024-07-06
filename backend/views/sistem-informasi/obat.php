<?php 
use common\models\ObatDropingBatch;
?>
<H3>Daftar Obat / Alkes Dropping</H3>
<table>
    <tr>
        <th WIDTH=50>No</th>
        <th>Nama Obat / Alkes</th>
        <th WIDTH=150>Satuan Obat</th>
        <th>Stok</th>
    </tr>
    <?php $no=1; foreach($droping as $o){ 
    $bacth = ObatDropingBatch::find()->where(['idobat'=>$o->id])->all();
    $stok = 0;
    foreach($bacth as $b){
        $stok += $b->stok;
    }
    ?>
    <tr>
        <td><?= $no++?></td>
        <td><?= $o->nama_obat?> </td>
        <td><?= $o->satuan->satuan?> </td>
        <td><?= $stok ?> </td>
    </tr>
    <?php } ?>
</table>