<div class='header'>
    <div class='header-satu'>
        RSAU Lanud Sulaiman
    </div>

</div>
<div class='header upper'>
    <b>Laporan KLPCM <?= $bulan .'/'. $tahun ?></b>
</div>
<table  style="border:1px solid;">
    <tr style="border:1px solid;">
        <th rowspan="2">No</th>
        <th rowspan="2">Poli</th>
        <th colspan="2">Keterbacaan</th>
        <th colspan="2">Kelengkapan</th>
    </tr>
    <tr style="border:1px solid;">
        <th >Terbaca</th>
        <th>Tidak Terbaca</th>
        <th>Lengkap</th>
        <th>Tidak Lengkap</th>
    </tr>
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