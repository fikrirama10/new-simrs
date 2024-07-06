<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usul_pesan_detail".
 *
 * @property int $id
 * @property int|null $idup
 * @property int|null $idobat
 * @property int|null $idbacth
 * @property float|null $harga
 * @property int|null $jumlah
 * @property float|null $total
 *
 * @property UsulPesan $idup0
 */
class UsulPesanDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usul_pesan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idup', 'idobat', 'idbacth', 'jumlah','status'], 'integer'],
            [['harga', 'total'], 'number'],
            [['keterangan'], 'string'],
            [['idup'], 'exist', 'skipOnError' => true, 'targetClass' => UsulPesan::className(), 'targetAttribute' => ['idup' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idup' => 'Idup',
            'idobat' => 'Idobat',
            'idbacth' => 'Idbacth',
            'harga' => 'Harga',
            'jumlah' => 'Jumlah',
            'total' => 'Total',
        ];
    }

    /**
     * Gets query for [[Idup0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUp()
    {
        return $this->hasOne(UsulPesan::className(), ['id' => 'idup']);
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getMerk()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbacth']);
    }
	public function getJeniss()
    {
        return $this->hasOne(ObatJenis::className(), ['id' => 'jenis']);
    }
}
