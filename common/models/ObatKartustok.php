<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_kartustok".
 *
 * @property int $id
 * @property int|null $idobat
 * @property int|null $idbatch
 * @property int|null $idasal
 * @property float|null $jumlah
 * @property string|null $jenis
 * @property string|null $tgl
 */
class ObatKartustok extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_kartustok';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idobat', 'idbatch', 'idasal'], 'integer'],
            [['jumlah'], 'number'],
            [['jenis'], 'string'],
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
            'idbatch' => 'Idbatch',
            'idasal' => 'Idasal',
            'jumlah' => 'Jumlah',
            'jenis' => 'Jenis',
            'tgl' => 'Tgl',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getBatch()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbatch']);
    }
}
