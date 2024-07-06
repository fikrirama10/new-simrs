<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping_transaksi_detail".
 *
 * @property int $id
 * @property int|null $idtrx
 * @property int|null $idobat
 * @property int|null $idbatch
 * @property int|null $jumlah
 * @property int|null $jenis
 * @property int|null $status
 */
class ObatDropingTransaksiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping_transaksi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtrx', 'idobat', 'idbatch', 'jumlah', 'jenis', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
	public function getObat()
    {
        return $this->hasOne(ObatDroping::className(), ['id' => 'idobat']);
    }
	public function getMerk()
    {
        return $this->hasOne(ObatDropingBatch::className(), ['id' => 'idbatch']);
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtrx' => 'Idtrx',
            'idobat' => 'Idobat',
            'idbatch' => 'Idbatch',
            'jumlah' => 'Jumlah',
            'jenis' => 'Jenis',
            'status' => 'Status',
        ];
    }
}
