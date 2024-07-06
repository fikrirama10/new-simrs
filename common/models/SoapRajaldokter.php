<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_rajaldokter".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $iduser
 * @property string|null $anamnesa
 * @property string|null $tgl_soap
 * @property string|null $planing
 * @property int|null $idkeluar
 * @property int|null $idjenisrawat
 * @property string|null $usia
 * @property int|null $edit
 * @property string|null $tgl_edit
 */
class SoapRajaldokter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_rajaldokter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'idkeluar', 'idjenisrawat', 'edit'], 'integer'],
            [['anamnesa', 'planing'], 'string'],
            [['tgl_soap', 'tgl_edit'], 'safe'],
            [['no_rm', 'iduser', 'usia'], 'string', 'max' => 50],
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
            'anamnesa' => 'Anamnesa',
            'tgl_soap' => 'Tgl Soap',
            'planing' => 'Planing',
            'idkeluar' => 'Idkeluar',
            'idjenisrawat' => 'Idjenisrawat',
            'usia' => 'Usia',
            'edit' => 'Edit',
            'tgl_edit' => 'Tgl Edit',
        ];
    }
}
