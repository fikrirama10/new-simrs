<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ruangan".
 *
 * @property int $id
 * @property int|null $idjenis
 * @property string|null $nama_ruangan
 * @property string|null $kapasitas
 * @property int|null $gender
 * @property int|null $idkelas
 * @property int|null $status
 * @property string|null $keterangan
 * @property int|null $jenis
 *
 * @property Rawat[] $rawats
 * @property RuanganGender $gender0
 * @property RuanganJenis $idjenis0
 * @property RuanganKelas $idkelas0
 * @property RuanganBed[] $ruanganBeds
 */
class Ruangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ruangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenis', 'gender', 'idkelas', 'status', 'jenis'], 'integer'],
            [['nama_ruangan', 'kapasitas', 'keterangan'], 'string', 'max' => 50],
            [['gender'], 'exist', 'skipOnError' => true, 'targetClass' => RuanganGender::className(), 'targetAttribute' => ['gender' => 'id']],
            [['idjenis'], 'exist', 'skipOnError' => true, 'targetClass' => RuanganJenis::className(), 'targetAttribute' => ['idjenis' => 'id']],
            [['idkelas'], 'exist', 'skipOnError' => true, 'targetClass' => RuanganKelas::className(), 'targetAttribute' => ['idkelas' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idjenis' => 'Idjenis',
            'nama_ruangan' => 'Nama Ruangan',
            'kapasitas' => 'Kapasitas',
            'gender' => 'Gender',
            'idkelas' => 'Idkelas',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'jenis' => 'Jenis',
        ];
    }

    /**
     * Gets query for [[Rawats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawats()
    {
        return $this->hasMany(Rawat::className(), ['idruangan' => 'id']);
    }

    /**
     * Gets query for [[Gender0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGender0()
    {
        return $this->hasOne(RuanganGender::className(), ['id' => 'gender']);
    }

    /**
     * Gets query for [[Idjenis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdjenis0()
    {
        return $this->hasOne(RuanganJenis::className(), ['id' => 'idjenis']);
    }

    /**
     * Gets query for [[Idkelas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdkelas0()
    {
        return $this->hasOne(RuanganKelas::className(), ['id' => 'idkelas']);
    }

    /**
     * Gets query for [[RuanganBeds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuanganBeds()
    {
        return $this->hasMany(RuanganBed::className(), ['idruangan' => 'id']);
    }
}
