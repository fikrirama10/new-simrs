<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "personel_jabatan".
 *
 * @property int $id
 * @property string|null $jabatan
 * @property string|null $keterangan
 */
class PersonelJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personel_jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan'], 'string'],
            [['jabatan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jabatan' => 'Jabatan',
            'keterangan' => 'Keterangan',
        ];
    }
}
