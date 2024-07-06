<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_rajalperawat".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property int|null $iduser
 * @property string|null $tgl_soap
 * @property string|null $anamnesa
 * @property string|null $sistole
 * @property string|null $distole
 * @property string|null $respirasi
 * @property string|null $saturasi
 * @property string|null $suhu
 * @property string|null $tinggi
 * @property string|null $berat
 * @property string|null $usia
 * @property int|null $triase
 * @property int|null $idjenisrawat
 * @property string|null $nadi
 * @property string|null $alergi
 * @property int|null $edit
 * @property string|null $tgl_edit
 */
class SoapRajalperawat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_rajalperawat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iduser', 'triase', 'idjenisrawat', 'edit','kesadaran','keadaan_umum'], 'integer'],
            [['tgl_soap', 'tgl_edit'], 'safe'],
            [['anamnesa'], 'string'],
            [['no_rm', 'sistole', 'distole', 'respirasi', 'saturasi', 'suhu', 'tinggi', 'berat', 'usia', 'nadi'], 'string', 'max' => 50],
            [['alergi'], 'string', 'max' => 200],
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
            'iduser' => 'Iduser',
            'tgl_soap' => 'Tgl Soap',
            'anamnesa' => 'Anamnesa',
            'sistole' => 'Sistole',
            'distole' => 'Distole',
            'respirasi' => 'Respirasi',
            'saturasi' => 'Saturasi',
            'suhu' => 'Suhu',
            'tinggi' => 'Tinggi',
            'berat' => 'Berat',
            'usia' => 'Usia',
            'triase' => 'Triase',
            'idjenisrawat' => 'Idjenisrawat',
            'nadi' => 'Nadi',
            'alergi' => 'Alergi',
            'edit' => 'Edit',
            'tgl_edit' => 'Tgl Edit',
        ];
    }
	public function getTriases()
    {
        return $this->hasOne(SoapTriase::className(), ['id' => 'triase']);
    }
	public function getKeadaan()
    {
        return $this->hasOne(SoapKeadaan::className(), ['id' => 'keadaan_umum']);
    }
	public function getKesadarans()
    {
        return $this->hasOne(SoapKesadaran::className(), ['id' => 'kesadaran']);
    }
}
