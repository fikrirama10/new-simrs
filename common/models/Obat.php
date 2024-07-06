<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat".
 *
 * @property int $id
 * @property string|null $nama_obat
 * @property string|null $kandungan
 * @property float|null $min_stokgudang
 * @property float|null $min_stokapotek
 * @property int|null $idjenis
 * @property int|null $idsatuan
 * @property int|null $narkotika
 * @property int|null $psikotropika
 * @property int|null $antibiotik
 * @property int|null $fornas
 *
 * @property ObatBacth[] $obatBacths
 */
class Obat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kandungan','khasiat','kadaluarsa'], 'string'],
            [['min_stokgudang', 'min_stokapotek','stok_apotek','stok_gudang','harga_jual','harga_beli'], 'number'],
            [['idjenis', 'idsatuan', 'narkotika', 'psikotropika', 'antibiotik', 'fornas','obat_luar'], 'integer'],
            [['nama_obat','abjad'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_obat' => 'Nama Obat',
            'kandungan' => 'Kandungan',
            'min_stokgudang' => 'Min Stokgudang',
            'min_stokapotek' => 'Min Stokapotek',
            'idjenis' => 'Idjenis',
            'idsatuan' => 'Idsatuan',
            'narkotika' => 'Narkotika',
            'psikotropika' => 'Psikotropika',
            'antibiotik' => 'Antibiotik',
            'fornas' => 'Fornas',
        ];
    }

    /**
     * Gets query for [[ObatBacths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatBacths()
    {
        return $this->hasMany(ObatBacth::className(), ['idobat' => 'id']);
    }
	public function getJenis()
    {
        return $this->hasOne(ObatJenis::className(), ['id' => 'idjenis']);
    }
	public function getSatuan()
    {
        return $this->hasOne(ObatSatuan::className(), ['id' => 'idsatuan']);
    }
}
