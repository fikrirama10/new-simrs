<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permintaan_obat_request".
 *
 * @property int $id
 * @property int|null $idpermintaan
 * @property int|null $idobat
 * @property float|null $jumlah
 * @property int|null $status
 */
class PermintaanObatRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permintaan_obat_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           [['idpermintaan', 'idobat', 'status','harga','idbacth','total','baru','jumlah_setuju','total_setuju'], 'integer'],
            [['keterangan','nama_obat'], 'string'],
            [['jumlah'], 'number'],
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
            'jumlah' => 'Jumlah',
            'status' => 'Status',
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
    public function getPermintaan()
    {
        return $this->hasOne(PermintaanObat::className(), ['id' => 'idpermintaan']);
    }
}
