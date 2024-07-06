<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_suplier".
 *
 * @property int $id
 * @property string|null $suplier
 * @property string|null $alamat
 * @property string|null $no_telp
 *
 * @property ObatBacth[] $obatBacths
 */
class ObatSuplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_suplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alamat'], 'string'],
            [['suplier', 'no_telp'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'suplier' => 'Suplier',
            'alamat' => 'Alamat',
            'no_telp' => 'No Telp',
        ];
    }

    /**
     * Gets query for [[ObatBacths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatBacths()
    {
        return $this->hasMany(ObatBacth::className(), ['idsuplier' => 'id']);
    }
}
