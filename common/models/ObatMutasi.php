<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_mutasi".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idsatuan
 * @property string|null $tgl
 * @property int|null $idjenismutasi
 * @property int|null $jumlah
 * @property int|null $stokakhir
 * @property int|null $amprah
 * @property int|null $amprah_ke
 * @property string|null $jenis
 * @property int|null $penjualan
 * @property int|null $idbacth
 *
 * @property Gudang $amprah0
 * @property Gudang $amprahKe
 * @property Obat $idobat0
 * @property ObatBacth $idbacth0
 * @property ObatJenismutasi $idjenismutasi0
 * @property ObatSatuan $idsatuan0
 */
class ObatMutasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_mutasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idsatuan', 'idjenismutasi','idsubmutasi', 'jumlah','stokakhir','stokawal','amprah', 'amprah_ke', 'penjualan', 'idbacth','idgudang','idtransaksi'], 'integer'],
            [['tgl'], 'safe'],
            [['jenis'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idobat' => 'Idobat',
            'idsatuan' => 'Idsatuan',
            'tgl' => 'Tgl',
            'idjenismutasi' => 'Idjenismutasi',
            'jumlah' => 'Jumlah',
            'stokakhir' => 'Stokakhir',
            'amprah' => 'Amprah',
            'amprah_ke' => 'Amprah Ke',
            'jenis' => 'Jenis',
            'penjualan' => 'Penjualan',
            'idbacth' => 'Idbacth',
        ];
    }

    /**
     * Gets query for [[Amprah0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAmprah0()
    {
        return $this->hasOne(Gudang::className(), ['id' => 'amprah']);
    }

    /**
     * Gets query for [[AmprahKe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAmprahKe()
    {
        return $this->hasOne(Gudang::className(), ['id' => 'amprah_ke']);
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
     * Gets query for [[Idbacth0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBacth()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }

    /**
     * Gets query for [[Idjenismutasi0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenis()
    {
        return $this->hasOne(ObatJenismutasi::className(), ['id' => 'idjenismutasi']);
    }
	public function getSubjenis()
    {
        return $this->hasOne(ObatSubjenismutasi::className(), ['id' => 'idsubmutasi']);
    }

    /**
     * Gets query for [[Idsatuan0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdsatuan0()
    {
        return $this->hasOne(ObatSatuan::className(), ['id' => 'idsatuan']);
    }
}
