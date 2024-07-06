<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "laboratorium_hasildetail".
 *
 * @property int $id
 * @property int|null $idhasil
 * @property int|null $idpemeriksaan
 * @property string|null $nama_pemeriksaan
 * @property int|null $status
 * @property int|null $idbayar
 * @property string|null $no_rm
 */
class LaboratoriumHasildetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium_hasildetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idhasil', 'idpemeriksaan', 'status', 'idbayar','idpengantar','tgl_hasil','jam_hasil'], 'integer'],
            [['nama_pemeriksaan', 'no_rm'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idhasil' => 'Idhasil',
            'idpemeriksaan' => 'Idpemeriksaan',
            'nama_pemeriksaan' => 'Nama Pemeriksaan',
            'status' => 'Status',
            'idbayar' => 'Idbayar',
            'no_rm' => 'No Rm',
        ];
    }
	public function getPemeriksaan()
    {
        return $this->hasOne(LaboratoriumPemeriksaan::className(), ['id' => 'idpemeriksaan']);
    }
	public function getHasil()
    {
        return $this->hasOne(LaboratoriumHasil::className(), ['id' => 'idhasil']);
    }
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }

}
