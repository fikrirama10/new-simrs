<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_awalinap".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $anamnesa
 * @property string|null $distole
 * @property string|null $sistole
 * @property string|null $suhu
 * @property string|null $spo2
 * @property string|null $respirasi
 * @property string|null $nadi
 * @property string|null $tinggi
 * @property string|null $berat
 * @property int|null $keadaan
 * @property int|null $kesadaran
 * @property int|null $idpetugas
 * @property string|null $alergi
 * @property string|null $diagnosa
 * @property int|null $iddatang
 * @property string|null $tgl
 * @property string|null $jam_masuk
 */
class RawatAwalinap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_awalinap';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'keadaan', 'kesadaran', 'idpetugas', 'iddatang','idruangan'], 'integer'],
            [['anamnesa', 'alergi'], 'string'],
            [['tgl', 'jam_masuk'], 'safe'],
            [['no_rm', 'distole', 'sistole', 'suhu', 'spo2', 'respirasi', 'nadi', 'tinggi', 'berat', 'diagnosa'], 'string', 'max' => 50],
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
            'anamnesa' => 'Anamnesa',
            'distole' => 'Distole',
            'sistole' => 'Sistole',
            'suhu' => 'Suhu',
            'spo2' => 'Spo2',
            'respirasi' => 'Respirasi',
            'nadi' => 'Nadi',
            'tinggi' => 'Tinggi',
            'berat' => 'Berat',
            'keadaan' => 'Keadaan',
            'kesadaran' => 'Kesadaran',
            'idpetugas' => 'Idpetugas',
            'alergi' => 'Alergi',
            'diagnosa' => 'Diagnosa',
            'iddatang' => 'Iddatang',
            'tgl' => 'Tgl',
            'jam_masuk' => 'Jam Masuk',
        ];
    }
	public function getKeadaans()
    {
        return $this->hasOne(SoapKeadaan::className(), ['id' => 'keadaan']);
    }
	public function getKesadarans()
    {
        return $this->hasOne(SoapKesadaran::className(), ['id' => 'kesadaran']);
    }
}
