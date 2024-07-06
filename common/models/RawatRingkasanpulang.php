<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_ringkasanpulang".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $diagnosa_primer
 * @property string|null $diagnosa_sekunder
 * @property string|null $tindakan_primer
 * @property string|null $tindakan_sekunder
 * @property string|null $riwayat_penyakit
 * @property string|null $pemeriksaan_fisik
 * @property string|null $penunjang
 * @property string|null $prognosa
 * @property string|null $anjuran
 * @property string|null $terapi
 * @property int|null $kondisi_waktupulang
 * @property string|null $tgl_pulang
 *
 * @property Pasien $noRm
 */
class RawatRingkasanpulang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_ringkasanpulang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'kondisi_waktupulang','idpoli'], 'integer'],
            [['diagnosa_primer', 'diagnosa_sekunder', 'tindakan_primer', 'tindakan_sekunder', 'riwayat_penyakit', 'pemeriksaan_fisik', 'penunjang', 'prognosa', 'anjuran', 'terapi'], 'string'],
            [['tgl_pulang','jam_pulang','tgl_kontrol'], 'safe'],
            [['no_rm'], 'string', 'max' => 50],
            [['no_rm'], 'exist', 'skipOnError' => true, 'targetClass' => Pasien::className(), 'targetAttribute' => ['no_rm' => 'no_rm']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'diagnosa_primer' => 'Diagnosa Primer',
            'diagnosa_sekunder' => 'Diagnosa Sekunder',
            'tindakan_primer' => 'Tindakan Primer',
            'tindakan_sekunder' => 'Tindakan Sekunder',
            'riwayat_penyakit' => 'Riwayat Penyakit',
            'pemeriksaan_fisik' => 'Pemeriksaan Fisik',
            'penunjang' => 'Penunjang',
            'prognosa' => 'Prognosa',
            'anjuran' => 'Anjuran',
            'terapi' => 'Terapi',
            'kondisi_waktupulang' => 'Kondisi Waktupulang',
            'tgl_pulang' => 'Tgl Pulang',
        ];
    }

    /**
     * Gets query for [[NoRm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	public function getPulang()
    {
        return $this->hasOne(DataPulang::className(), ['id' => 'kondisi_waktupulang']);
    }
	public function getPoli()
    {
        return $this->hasOne(Poli::className(), ['id' => 'idpoli']);
    }
}
