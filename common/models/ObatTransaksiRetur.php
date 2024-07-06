<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_transaksi_retur".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property string|null $tgl
 * @property int|null $idresep
 * @property int|null $jumlah
 * @property int|null $iduser
 * @property int|null $iddetail
 */
class ObatTransaksiRetur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_transaksi_retur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idbacth', 'idresep', 'jumlah', 'iduser', 'iddetail'], 'integer'],
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
            'idobat' => 'Idobat',
            'idbacth' => 'Idbacth',
            'tgl' => 'Tgl',
            'idresep' => 'Idresep',
            'jumlah' => 'Jumlah',
            'iduser' => 'Iduser',
            'iddetail' => 'Iddetail',
        ];
    }
}
