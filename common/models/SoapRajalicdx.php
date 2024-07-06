<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_rajalicdx".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property int|null $idsoap
 * @property string|null $diagnosa
 * @property string|null $icdx
 * @property int|null $iduser
 * @property int|null $idrm
 * @property string|null $tglupdate
 * @property int|null $idjenisdiagnosa
 * @property int|null $diagnosa_jenis
 * @property int|null $idjenisrawat
 */
class SoapRajalicdx extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
	public $jml;
    public static function tableName()
    {
        return 'soap_rajalicdx';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idsoap', 'iduser', 'idrm', 'idjenisdiagnosa', 'diagnosa_jenis', 'idjenisrawat','baru','kat_pasien'], 'integer'],
            [['tglupdate','tgl'], 'safe'],
            [['diagnosa', 'icdx'], 'string', 'max' => 150],
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
            'idsoap' => 'Idsoap',
            'diagnosa' => 'Diagnosa',
            'icdx' => 'Icdx',
            'iduser' => 'Iduser',
            'idrm' => 'Idrm',
            'tglupdate' => 'Tglupdate',
            'idjenisdiagnosa' => 'Idjenisdiagnosa',
            'diagnosa_jenis' => 'Diagnosa Jenis',
            'idjenisrawat' => 'Idjenisrawat',
        ];
    }
	public function getJenis()
    {
        return $this->hasOne(SoapDiagnosajenis::className(), ['id' => 'idjenisdiagnosa']);
    }
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']); 
    }
}
