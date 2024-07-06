<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tindakan_kategori".
 *
 * @property int $id
 * @property string|null $kategori
 *
 * @property Tindakan[] $tindakans
 */
class TindakanKategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tindakan_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kategori'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kategori' => 'Kategori',
        ];
    }

    /**
     * Gets query for [[Tindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTindakans()
    {
        return $this->hasMany(Tindakan::className(), ['idjenistindakan' => 'id']);
    }
}
