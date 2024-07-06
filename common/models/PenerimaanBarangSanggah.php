<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "penerimaan_barang_sanggah".
 *
 * @property int $id
 * @property int|null $idpenerimaan
 * @property int|null $jumlah_up
 * @property int|null $jumlah_sanggah
 * @property string|null $keterangan
 * @property string|null $tgl
 * @property int|null $iduser
 * @property int|null $status
 * @property int|null $idsanggah
 */
class PenerimaanBarangSanggah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penerimaan_barang_sanggah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpenerimaan', 'jumlah_up', 'jumlah_sanggah', 'iduser', 'status', 'idsanggah'], 'integer'],
            [['keterangan'], 'string'],
            [['tgl'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpenerimaan' => 'Idpenerimaan',
            'jumlah_up' => 'Jumlah Up',
            'jumlah_sanggah' => 'Jumlah Sanggah',
            'keterangan' => 'Keterangan',
            'tgl' => 'Tgl',
            'iduser' => 'Iduser',
            'status' => 'Status',
            'idsanggah' => 'Idsanggah',
        ];
    }
}
