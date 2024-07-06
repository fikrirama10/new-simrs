<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_farmasi_detail".
 *
 * @property int $id
 * @property int|null $idresep
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property int|null $jumlah
 * @property int|null $keuntungan
 * @property int|null $harga
 * @property int|null $total
 * @property string|null $keterangan
 * @property int|null $status
 * @property string|null $signa
 * @property int|null $dosis
 * @property string|null $takaran
 * @property int|null $tuslah
 * @property string|null $diminum
 */
class ObatFarmasiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_farmasi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idresep', 'idobat', 'idbacth', 'jumlah', 'keuntungan', 'harga', 'total', 'status', 'dosis', 'tuslah'], 'integer'],
            [['keterangan', 'signa', 'takaran', 'diminum'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idresep' => 'Idresep',
            'idobat' => 'Idobat',
            'idbacth' => 'Idbacth',
            'jumlah' => 'Jumlah',
            'keuntungan' => 'Keuntungan',
            'harga' => 'Harga',
            'total' => 'Total',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'signa' => 'Signa',
            'dosis' => 'Dosis',
            'takaran' => 'Takaran',
            'tuslah' => 'Tuslah',
            'diminum' => 'Diminum',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
}
