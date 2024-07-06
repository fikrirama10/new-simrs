<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "personel".
 *
 * @property int $id
 * @property string|null $kode_personel
 * @property string|null $nik
 * @property string|null $no_bpjs
 * @property string|null $nrp_nip
 * @property string|null $nama_lengkap
 * @property string|null $alamat
 * @property int|null $idjabatan
 * @property int|null $idpangkat
 * @property string|null $foto
 * @property string|null $no_tlp
 * @property int|null $status
 */
class Personel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alamat'], 'string'],
            [['idjabatan', 'idpangkat', 'status'], 'integer'],
            [['kode_personel', 'nik', 'no_bpjs', 'nrp_nip', 'nama_lengkap', 'no_tlp'], 'string', 'max' => 50],
            [['foto'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_personel' => 'Kode Personel',
            'nik' => 'Nik',
            'no_bpjs' => 'No Bpjs',
            'nrp_nip' => 'Nrp Nip',
            'nama_lengkap' => 'Nama Lengkap',
            'alamat' => 'Alamat',
            'idjabatan' => 'Idjabatan',
            'idpangkat' => 'Idpangkat',
            'foto' => 'Foto',
            'no_tlp' => 'No Tlp',
            'status' => 'Status',
        ];
    }
}
