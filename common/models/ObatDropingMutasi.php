<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_mutasi".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property int|null $jumlah
 * @property int|null $idjenis
 * @property int|null $idsubmutasi
 * @property int|null $stok_awal
 * @property int|null $stok_akhir
 * @property string|null $tgl
 * @property string|null $keterangan
 */
class ObatDropingMutasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_mutasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idbacth', 'jumlah', 'idjenis', 'idsubmutasi', 'stok_awal', 'stok_akhir'], 'integer'],
            [['tgl'], 'safe'],
            [['keterangan'], 'string'],
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
            'idbacth' => 'Idbacth',
            'jumlah' => 'Jumlah',
            'idjenis' => 'Idjenis',
            'idsubmutasi' => 'Idsubmutasi',
            'stok_awal' => 'Stok Awal',
            'stok_akhir' => 'Stok Akhir',
            'tgl' => 'Tgl',
            'keterangan' => 'Keterangan',
        ];
    }
}
