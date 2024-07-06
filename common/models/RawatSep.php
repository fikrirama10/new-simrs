<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_sep".
 *
 * @property int $id
 * @property int|null $idjenisrawat
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $no_bpjs
 * @property string|null $no_sep
 * @property string|null $no_rujukan
 * @property int|null $idpoli
 * @property int|null $iddokter
 * @property string|null $kode_dokter
 * @property string|null $no_kontrol
 * @property string|null $kode_poli
 * @property string|null $tujuan
 * @property string|null $procedure
 * @property string|null $kdpenunjang
 * @property string|null $assesmenpel;
 */
class RawatSep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_sep';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenisrawat', 'idrawat', 'idpoli', 'iddokter'], 'integer'],
            [['no_rm', 'no_bpjs', 'no_sep', 'no_rujukan', 'kode_dokter', 'no_kontrol', 'kode_poli', 'tujuan', 'procedure', 'kdpenunjang', 'assesmenpel;'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idjenisrawat' => 'Idjenisrawat',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'no_bpjs' => 'No Bpjs',
            'no_sep' => 'No Sep',
            'no_rujukan' => 'No Rujukan',
            'idpoli' => 'Idpoli',
            'iddokter' => 'Iddokter',
            'kode_dokter' => 'Kode Dokter',
            'no_kontrol' => 'No Kontrol',
            'kode_poli' => 'Kode Poli',
            'tujuan' => 'Tujuan',
            'procedure' => 'Procedure',
            'kdpenunjang' => 'Kdpenunjang',
            'assesmenpel;' => 'Assesmenpel;',
        ];
    }
}
