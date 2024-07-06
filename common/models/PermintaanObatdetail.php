<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permintaan_obatdetail".
 *
 * @property int $id
 * @property int|null $idpermintaan
 * @property int|null $idobat
 * @property float|null $jumlah_setuju
 * @property float|null $harga
 * @property float|null $total
 * @property float|null $jumlah_permintaan
 * @property string|null $keterangan
 */
class PermintaanObatdetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permintaan_obatdetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpermintaan', 'idobat','status','idbacth'], 'integer'],
            [['jumlah_setuju', 'harga', 'total', 'jumlah_permintaan'], 'number'],
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
            'idpermintaan' => 'Idpermintaan',
            'idobat' => 'Idobat',
            'jumlah_setuju' => 'Jumlah Setuju',
            'harga' => 'Harga',
            'total' => 'Total',
            'jumlah_permintaan' => 'Jumlah Permintaan',
            'keterangan' => 'Keterangan',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getBacth()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }
}
