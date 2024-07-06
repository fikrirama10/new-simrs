<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_lab".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property int|null $iddokter
 * @property int|null $iduser
 * @property int|null $idpemeriksaan
 * @property string|null $catatan
 * @property string|null $tgl_permintaan
 * @property string|null $tgl_hasil
 * @property int|null $idhasil
 * @property int|null $status
 */
class SoapLab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_lab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iddokter', 'iduser', 'idpemeriksaan', 'idhasil', 'status'], 'integer'],
            [['catatan'], 'string'],
            [['tgl_permintaan', 'tgl_hasil'], 'safe'],
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
            'iduser' => 'Iduser',
            'idpemeriksaan' => 'Idpemeriksaan',
            'catatan' => 'Catatan',
            'tgl_permintaan' => 'Tgl Permintaan',
            'tgl_hasil' => 'Tgl Hasil',
            'idhasil' => 'Idhasil',
            'status' => 'Status',
        ];
    }
	public function getPemeriksaan()
    {
        return $this->hasOne(LaboratoriumPemeriksaan::className(), ['id' => 'idpemeriksaan']);
    }

	public function getDokter()
    {
        return $this->hasOne(Dokter::className(), ['id' => 'iddokter']);
    }
	
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	
}
