<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "amprah_gudangatk_detail".
 *
 * @property int $id
 * @property int|null $idamprah
 * @property int|null $idbarang
 * @property int|null $jumlah
 * @property int|null $status
 * @property string|null $keterangan
 */
class AmprahGudangatkDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'amprah_gudangatk_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idamprah', 'idbarang', 'jumlah', 'status'], 'integer'],
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
            'idamprah' => 'Idamprah',
            'idbarang' => 'Idbarang',
            'jumlah' => 'Jumlah',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
	public function getBarang()
    {
        return $this->hasOne(DataBarang::className(), ['id' => 'idbarang']);
    }
	public function getAmprah()
    {
        return $this->hasOne(AmprahGudangatk::className(), ['id' => 'idamprah']);
    }
}
