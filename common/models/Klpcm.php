<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "klpcm".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $tgl_kunjungan
 * @property int|null $iddokter
 * @property int|null $status
 * @property string|null $ketrangan
 * @property int|null $keterbacaan
 * @property int|null $kelengkapan
 * @property int|null $idjenisrawat
 * @property string|null $no_rm
 *
 * @property KlpcmDetail[] $klpcmDetails
 */
class Klpcm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $jml;
    public static function tableName()
    {
        return 'klpcm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iddokter', 'status', 'keterbacaan', 'kelengkapan', 'idjenisrawat', 'kat_diagnosa', 'obat'], 'integer'],
            [['tgl_kunjungan', 'icdx'], 'safe'],
            [['ketrangan', 'no_rm', 'jenis_pelayanan', 'jenis_pasien'], 'string', 'max' => 50],
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
            'tgl_kunjungan' => 'Tgl Kunjungan',
            'iddokter' => 'Iddokter',
            'status' => 'Status',
            'ketrangan' => 'Ketrangan',
            'keterbacaan' => 'Keterbacaan',
            'kelengkapan' => 'Kelengkapan',
            'idjenisrawat' => 'Idjenisrawat',
            'no_rm' => 'No Rm',
        ];
    }

    /**
     * Gets query for [[KlpcmDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKlpcmDetails()
    {
        return $this->hasMany(KlpcmDetail::className(), ['idklpcm' => 'id']);
    }
    public function getJenisrawat()
    {
        return $this->hasOne(RawatJenis::className(), ['id' => 'idjenisrawat']);
    }
    public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['no_rm' => 'no_rm']);
    }
    public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
}
