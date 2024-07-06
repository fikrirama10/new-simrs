<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ruangan_jenis".
 *
 * @property int $id
 * @property string|null $ruangan_jenis
 *
 * @property Ruangan[] $ruangans
 */
class RuanganJenis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ruangan_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ruangan_jenis'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ruangan_jenis' => 'Ruangan Jenis',
        ];
    }

    /**
     * Gets query for [[Ruangans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuangans()
    {
        return $this->hasMany(Ruangan::className(), ['idjenis' => 'id']);
    }
}
