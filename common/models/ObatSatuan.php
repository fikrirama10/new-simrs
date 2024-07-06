<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_satuan".
 *
 * @property int $id
 * @property string|null $satuan
 *
 * @property Obat[] $obats
 * @property ObatBacth[] $obatBacths
 * @property ObatMutasi[] $obatMutasis
 */
class ObatSatuan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_satuan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['satuan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'satuan' => 'Satuan',
        ];
    }

    /**
     * Gets query for [[Obats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObats()
    {
        return $this->hasMany(Obat::className(), ['idsatuan' => 'id']);
    }

    /**
     * Gets query for [[ObatBacths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatBacths()
    {
        return $this->hasMany(ObatBacth::className(), ['idsatuan' => 'id']);
    }

    /**
     * Gets query for [[ObatMutasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatMutasis()
    {
        return $this->hasMany(ObatMutasi::className(), ['idsatuan' => 'id']);
    }
}
