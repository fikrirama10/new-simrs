<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soap_rajalobat".
 *
 * @property int $id
 * @property string|null $idkunjungan
 * @property int|null $idrawat
 * @property int|null $iddokter
 * @property int|null $idobat
 * @property float|null $jumlah
 * @property string|null $takaran
 * @property string|null $dosis
 */
class SoapRajalobat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soap_rajalobat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iddokter', 'idobat','idbatch'], 'integer'],
            [['catatan'], 'safe'],
            [['jumlah'], 'number'],
            [['idkunjungan', 'takaran', 'dosis','no_rm','signa1','signa2'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idkunjungan' => 'Idkunjungan',
            'idrawat' => 'Idrawat',
            'iddokter' => 'Iddokter',
            'idobat' => 'Idobat',
            'jumlah' => 'Jumlah',
            'takaran' => 'Takaran',
            'dosis' => 'Dosis',
        ];
    }
	public function getObat()
    {
        return $this->hasOne(Obat::className(), ['id' => 'idobat']);
    }
	public function getBacth()
    {
        return $this->hasOne(ObatBacth::className(), ['id' => 'idbatch']);
    }
}
