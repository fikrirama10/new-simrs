<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "obat_subjenismutasi".
 *
 * @property int $id
 * @property string|null $subjenis
 * @property int|null $idjenis
 *
 * @property ObatJenismutasi $idjenis0
 * @property ObatTransaksi[] $obatTransaksis
 */
class ObatSubjenismutasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obat_subjenismutasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idjenis'], 'integer'],
            [['subjenis'], 'string', 'max' => 50],
            [['idjenis'], 'exist', 'skipOnError' => true, 'targetClass' => ObatJenismutasi::className(), 'targetAttribute' => ['idjenis' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subjenis' => 'Subjenis',
            'idjenis' => 'Idjenis',
        ];
    }

    /**
     * Gets query for [[Idjenis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdjenis0()
    {
        return $this->hasOne(ObatJenismutasi::className(), ['id' => 'idjenis']);
    }

    /**
     * Gets query for [[ObatTransaksis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatTransaksis()
    {
        return $this->hasMany(ObatTransaksi::className(), ['idjenis' => 'id']);
    }
}
