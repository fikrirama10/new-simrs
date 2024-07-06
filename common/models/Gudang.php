<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gudang".
 *
 * @property int $id
 * @property string|null $nama_gudang
 * @property int|null $utama
 * @property int|null $status
 *
 * @property ObatMutasi[] $obatMutasis
 * @property ObatMutasi[] $obatMutasis0
 */
class Gudang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gudang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['utama', 'status'], 'integer'],
            [['nama_gudang'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_gudang' => 'Nama Gudang',
            'utama' => 'Utama',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[ObatMutasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatMutasis()
    {
        return $this->hasMany(ObatMutasi::className(), ['amprah' => 'id']);
    }

    /**
     * Gets query for [[ObatMutasis0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObatMutasis0()
    {
        return $this->hasMany(ObatMutasi::className(), ['amprah_ke' => 'id']);
    }
}
