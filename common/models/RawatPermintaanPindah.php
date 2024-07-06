<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_permintaan_pindah".
 *
 * @property int $id
 * @property string|null $idkunjungan
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property int|null $idasal
 * @property int|null $idtujuan
 * @property int|null $idbedasal
 * @property int|null $idbedtujuan
 * @property int|null $iduser
 * @property int|null $iduser2
 * @property int|null $status
 * @property string|null $keterangan
 * @property int|null $keadaan
 * @property int|null $kesadaran
 * @property string|null $distole
 * @property string|null $sistole
 * @property string|null $suhu
 * @property string|null $spo2
 * @property string|null $nadi
 * @property string|null $respirasi
 * @property int|null $idkelasasal
 * @property int|null $idkelastujuan
 * @property string|null $tgl
 */
class RawatPermintaanPindah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_permintaan_pindah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idasal', 'idtujuan', 'idbedasal', 'idbedtujuan', 'iduser', 'iduser2', 'status', 'keadaan', 'kesadaran', 'idkelasasal', 'idkelastujuan'], 'integer'],
            [['keterangan'], 'string'],
            [['tgl'], 'safe'],
            [['idkunjungan', 'no_rm', 'distole', 'sistole', 'suhu', 'spo2', 'nadi', 'respirasi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idkunjungan' => 'Idkunjungan',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'idasal' => 'Idasal',
            'idtujuan' => 'Idtujuan',
            'idbedasal' => 'Idbedasal',
            'idbedtujuan' => 'Idbedtujuan',
            'iduser' => 'Iduser',
            'iduser2' => 'Iduser2',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'keadaan' => 'Keadaan',
            'kesadaran' => 'Kesadaran',
            'distole' => 'Distole',
            'sistole' => 'Sistole',
            'suhu' => 'Suhu',
            'spo2' => 'Spo2',
            'nadi' => 'Nadi',
            'respirasi' => 'Respirasi',
            'idkelasasal' => 'Idkelasasal',
            'idkelastujuan' => 'Idkelastujuan',
            'tgl' => 'Tgl',
        ];
    }
	public function getRuanganasal()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idasal']);
    }
	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'iduser']);
    }
	public function getRuangantujuan()
    {
        return $this->hasOne(Ruangan::className(), ['id' => 'idtujuan']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
}
