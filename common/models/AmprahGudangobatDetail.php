<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "amprah_gudangobat_detail".
 *
 * @property int $id
 * @property int|null $idamprah
 * @property int|null $idobat
 * @property int|null $jumlah_permintaan
 * @property int|null $jumlah_diserahkan
 * @property int|null $status
 * @property string|null $keterangan
 * @property int|null $idbacth
 */
class AmprahGudangobatDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'amprah_gudangobat_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idamprah', 'idobat', 'jumlah_permintaan', 'jumlah_diserahkan', 'status', 'idbacth'], 'integer'],
            [['keterangan'], 'string', 'max' => 50],
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
            'idobat' => 'Idobat',
            'jumlah_permintaan' => 'Jumlah Permintaan',
            'jumlah_diserahkan' => 'Jumlah Diserahkan',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'idbacth' => 'Idbacth',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getMerk()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }
	public function getAmprah()
    {
        return $this->hasOne(AmprahGudangobat::className(), ['id' => 'idamprah']);
    }
}
