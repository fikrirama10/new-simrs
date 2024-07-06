<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_radiologi".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property int|null $iddokter
 * @property int|null $idtindakan
 * @property string|null $tgl_permintaan
 * @property string|null $tgl_hasil
 * @property int|null $iduser
 * @property string|null $catatan
 * @property string|null $klinis
 * @property int|null $status
 * @property int|null $idhasil
 */
class SoapRadiologi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_radiologi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iddokter', 'idtindakan', 'iduser', 'status', 'idhasil'], 'integer'],
            [['tgl_permintaan', 'tgl_hasil'], 'safe'],
            [['catatan','no_rm'], 'string'],
            [['klinis'], 'string', 'max' => 50],
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
            'iddokter' => 'Iddokter',
            'idtindakan' => 'Idtindakan',
            'tgl_permintaan' => 'Tgl Permintaan',
            'tgl_hasil' => 'Tgl Hasil',
            'iduser' => 'Iduser',
            'catatan' => 'Catatan',
            'klinis' => 'Klinis',
            'status' => 'Status',
            'idhasil' => 'Idhasil',
        ];
    }
	public function getTindakan()
    {
        return $this->hasOne(RadiologiTindakan::className(), ['id' => 'idtindakan']);
    }
	public function getRawats()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
}
