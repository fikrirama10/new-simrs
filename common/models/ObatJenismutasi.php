<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_jenismutasi".
 *
 * @property int $id
 * @property string|null $jenis_mutasi
 *
 * @property ObatSubjenismutasi[] $obatSubjenismutasis
 */
class ObatJenismutasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_jenismutasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_mutasi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_mutasi' => 'Jenis Mutasi',
        ];
    }

    /**
     * Gets query for [[ObatSubjenismutasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatSubjenismutasis()
    {
        return $this->hasMany(ObatSubjenismutasi::className(), ['idjenis' => 'id']);
    }
}
