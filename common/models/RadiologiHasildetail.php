<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "radiologi_hasildetail".
 *
 * @property int $id
 * @property int|null $idhasil
 * @property int|null $idradiologi
 * @property int|null $idpelayanan
 * @property int|null $idtindakan
 * @property string|null $klinis
 * @property string|null $kesan
 * @property string|null $hasil
 * @property string|null $nofoto
 * @property string|null $tgl_hasil
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $template
 * @property int|null $idjenisrawat
 * @property int|null $idrawat
 * @property int|null $idbayar
 *
 * @property RadiologiHasilfoto[] $radiologiHasilfotos
 */
class RadiologiHasildetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radiologi_hasildetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idhasil', 'idradiologi', 'idpelayanan', 'idtindakan', 'status', 'template', 'idjenisrawat', 'idrawat', 'idbayar','idpengantar','kat_pasien'], 'integer'],
            [['klinis', 'kesan', 'hasil', 'keterangan'], 'string'],
            [['tgl_hasil'], 'safe'],
            [['nofoto'], 'string', 'max' => 50],
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
            'idradiologi' => 'Idradiologi',
            'idpelayanan' => 'Idpelayanan',
            'idtindakan' => 'Idtindakan',
            'klinis' => 'Klinis',
            'kesan' => 'Kesan',
            'hasil' => 'Hasil',
            'nofoto' => 'Nofoto',
            'tgl_hasil' => 'Tgl Hasil',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'template' => 'Template',
            'idjenisrawat' => 'Idjenisrawat',
            'idrawat' => 'Idrawat',
            'idbayar' => 'Idbayar',
        ];
    }

    /**
     * Gets query for [[RadiologiHasilfotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRadiologiHasilfotos()
    {
        return $this->hasMany(RadiologiHasilfoto::className(), ['idhasil' => 'id']);
    }
		public function getTindakan()
    {
        return $this->hasOne(RadiologiTindakan::className(), ['id' => 'idtindakan']);
    }
	public function getRawat()
    {
        return $this->hasOne(Rawat::className(), ['id' => 'idrawat']);
    }
	public function getHasilrad()
    {
        return $this->hasOne(RadiologiHasil::className(), ['id' => 'idhasil']);
    }
}
