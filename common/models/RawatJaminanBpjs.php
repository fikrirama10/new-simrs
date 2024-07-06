<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_jaminan_bpjs".
 *
 * @property int $id
 * @property int|null $idpasien
 * @property int|null $idrawat
 * @property string|null $noLp
 * @property string|null $tglkejadian
 * @property string|null $keterangan
 * @property string|null $suplesi
 * @property string|null $nosep_suplesi
 * @property string|null $propinsi
 * @property string|null $kabupaten
 * @property string|null $kecamatan
 * @property string|null $kejadian
 */
class RawatJaminanBpjs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_jaminan_bpjs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpasien', 'idrawat'], 'integer'],
            [['tglkejadian'], 'safe'],
            [['noLp', 'keterangan'], 'string', 'max' => 150],
            [['suplesi', 'nosep_suplesi', 'propinsi', 'kabupaten', 'kecamatan', 'kejadian'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpasien' => 'Idpasien',
            'idrawat' => 'Idrawat',
            'noLp' => 'No Lp',
            'tglkejadian' => 'Tglkejadian',
            'keterangan' => 'Keterangan',
            'suplesi' => 'Suplesi',
            'nosep_suplesi' => 'Nosep Suplesi',
            'propinsi' => 'Propinsi',
            'kabupaten' => 'Kabupaten',
            'kecamatan' => 'Kecamatan',
            'kejadian' => 'Kejadian',
        ];
    }
}
