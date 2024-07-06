<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pasien_alamat".
 *
 * @property int $id
 * @property string|null $no_rm
 * @property int|null $idprov
 * @property int|null $idkab
 * @property int|null $idkel
 * @property string|null $alamat
 * @property string|null $kode_pos
 */
class PasienAlamat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pasien_alamat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idprov', 'idkab', 'idkel','idkec','utama'], 'string'],
            [['alamat'], 'string'],
            [['no_rm', 'kodepos'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_rm' => 'No Rm',
            'idprov' => 'Idprov',
            'idkab' => 'Idkab',
            'idkel' => 'Idkel',
            'alamat' => 'Alamat',
            'kode_pos' => 'Kode Pos',
        ];
    }
	public function getKabupaten()
    {
        return $this->hasOne(Kabupaten::className(), ['id_kab' => 'idkab']);
    }
	public function getPasien()
    {
        return $this->hasOne(Pasien::className(), ['id' => 'idpasien']);
    }
	public function getKelurahan()
    {
        return $this->hasOne(Kelurahan::className(), ['id_kel' => 'idkel']);
    }
	public function getKecamatan()
    {
        return $this->hasOne(Kecamatan::className(), ['id_kec' => 'idkec']);
    }
	public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id_prov' => 'idprov']);
    }
}
