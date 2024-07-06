<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "laboratorium_layanan".
 *
 * @property int $id
 * @property string|null $nama_layanan
 * @property string|null $keterangan
 * @property int|null $urutan
 *
 * @property LaboratoriumForm[] $laboratoriumForms
 * @property LaboratoriumPemeriksaan[] $laboratoriumPemeriksaans
 */
class LaboratoriumLayanan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laboratorium_layanan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'string'],
            [['urutan'], 'integer'],
            [['nama_layanan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_layanan' => 'Nama Layanan',
            'keterangan' => 'Keterangan',
            'urutan' => 'Urutan',
        ];
    }

    /**
     * Gets query for [[LaboratoriumForms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLaboratoriumForms()
    {
        return $this->hasMany(LaboratoriumForm::className(), ['idpelayanan' => 'id']);
    }

    /**
     * Gets query for [[LaboratoriumPemeriksaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLaboratoriumPemeriksaans()
    {
        return $this->hasMany(LaboratoriumPemeriksaan::className(), ['idlab' => 'id']);
    }
}
