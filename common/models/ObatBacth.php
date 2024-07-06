<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_bacth".
 *
 * @property int $id
 * @property string|null $no_bacth
 * @property int|null $idobat
 * @property int|null $idsuplier
 * @property string|null $merk
 * @property float|null $stok_apotek
 * @property float|null $stok_gudang
 * @property int|null $idbayar
 * @property string|null $tgl_produksi
 * @property string|null $tgl_kadaluarsa
 * @property int|null $idsatuan
 * @property float|null $harga_jual
 * @property float|null $harga_beli
 *
 * @property Obat $idobat0
 * @property ObatSatuan $idsatuan0
 * @property ObatSuplier $idsuplier0
 * @property RawatBayar $idbayar0
 */
class ObatBacth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_bacth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idsuplier', 'idbayar', 'idsatuan','kat_obat','aktif'], 'integer'],
            [['stok_apotek', 'stok_gudang', 'harga_jual', 'harga_beli'], 'number'],
            [['tgl_produksi', 'tgl_kadaluarsa'], 'safe'],
            [['no_bacth'], 'string', 'max' => 50],
            [['merk'], 'string', 'max' => 150],
            [['idsatuan'], 'exist', 'skipOnError' => true, 'targetClass' => ObatSatuan::className(), 'targetAttribute' => ['idsatuan' => 'id']],
            [['idsuplier'], 'exist', 'skipOnError' => true, 'targetClass' => ObatSuplier::className(), 'targetAttribute' => ['idsuplier' => 'id']],
            [['idbayar'], 'exist', 'skipOnError' => true, 'targetClass' => RawatBayar::className(), 'targetAttribute' => ['idbayar' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_bacth' => 'No Bacth',
            'idobat' => 'Idobat',
            'idsuplier' => 'Idsuplier',
            'merk' => 'Merk',
            'stok_apotek' => 'Stok Apotek',
            'stok_gudang' => 'Stok Gudang',
            'idbayar' => 'Idbayar',
            'tgl_produksi' => 'Tgl Produksi',
            'tgl_kadaluarsa' => 'Tgl Kadaluarsa',
            'idsatuan' => 'Idsatuan',
            'harga_jual' => 'Harga Jual',
            'harga_beli' => 'Harga Beli',
        ];
    }

    /**
     * Gets query for [[Idobat0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }

    /**
     * Gets query for [[Idsatuan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSatuan()
    {
        return $this->hasOne(ObatSatuan::className(), ['id' => 'idsatuan']);
    }

    /**
     * Gets query for [[Idsuplier0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(ObatSuplier::className(), ['id' => 'idsuplier']);
    }

    /**
     * Gets query for [[Idbayar0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBayar()
    {
        return $this->hasOne(RawatBayar::className(), ['id' => 'idbayar']);
    }
}
