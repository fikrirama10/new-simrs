<?php
use common\models\Rawat;
$tgl = date('Y-m-d H:i:s',strtotime('+6 hour ,-1 day'));
$rawat_inap = Rawat::find()->andwhere(['idjenisrawat'=>2])->andWhere(['<>','status',5])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>date('Y-m-d',strtotime('+6 hour ,-1 day'))])->count();
?>
Assalamu'alaikum..selamat pagi ijin melaporkan Kunjungan <b>Pasien Rawat Jalan RSAU dr. Norman T. Lubis</b><br>
<br>
<b><?= Yii::$app->algo->tglIndo($tgl) ?></b> 
    <br>
    <br>
    <?php foreach($poli as $p): 
    $rawat = Rawat::find()->where(['idpoli'=>$p->id])->andwhere(['<>','idjenisrawat',2])->andWhere(['<>','status',5])->andwhere(['DATE_FORMAT(tglmasuk,"%Y-%m-%d")'=>date('Y-m-d',strtotime('+6 hour ,-1 day'))])->count();
        ?>
    
    Poli <?= $p->poli ?> : <?= $rawat?><br>
    
    <?php endforeach; ?>
    Rawat Inap : <?= $rawat_inap ?>
    <br>
    <br>
<b>Demikian kami sampaikan terima kasih 🙏</b> 