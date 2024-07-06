<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_droping".
 *
 * @property int $id
 * @property string|null $kode_obat
 * @property string|null $nama_obat
 * @property int|null $idjenis
 * @property int|null $idsatuan
 */
class ObatDroping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_droping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenis', 'idsatuan'], 'integer'],
            [['kode_obat', 'nama_obat'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_obat' => 'Kode Obat',
            'nama_obat' => 'Nama Obat',
            'idjenis' => 'Idjenis',
            'idsatuan' => 'Idsatuan',
        ];
    }
	public function getJenis()
    {
        return $this->hasOne(ObatJenis::className(), ['id' => 'idjenis']);
    }
	public function getSatuan()
    {
        return $this->hasOne(ObatSatuan::className(), ['id' => 'idsatuan']);
    }
}
