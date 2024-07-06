<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_kontrol".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property int|null $idpoli
 * @property string|null $tgl_kontrol
 * @property string|null $kode_kontrol
 * @property string|null $no_surat
 * @property string|null $iddokter
 * @property string|null $tgl_buat
 * @property int|null $iduser
 * @property string|null $kode_dokter
 * @property string|null $no_sep
 */
class RawatKontrol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_kontrol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idpoli', 'iduser'], 'integer'],
            [['tgl_kontrol', 'tgl_buat'], 'safe'],
            [['no_rm', 'kode_kontrol', 'no_surat', 'iddokter', 'kode_dokter', 'no_sep'], 'string', 'max' => 50],
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
            'idpoli' => 'Idpoli',
            'tgl_kontrol' => 'Tgl Kontrol',
            'kode_kontrol' => 'Kode Kontrol',
            'no_surat' => 'No Surat',
            'iddokter' => 'Iddokter',
            'tgl_buat' => 'Tgl Buat',
            'iduser' => 'Iduser',
            'kode_dokter' => 'Kode Dokter',
            'no_sep' => 'No Sep',
        ];
    }
}
