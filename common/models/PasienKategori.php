<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pasien_kategori".
 *
 * @property int $id
 * @property string|null $angkatan
 * @property string|null $jenis
 * @property string|null $nama
 */
class PasienKategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pasien_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['angkatan', 'jenis'], 'string'],
            [['nama'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'angkatan' => 'Angkatan',
            'jenis' => 'Jenis',
            'nama' => 'Nama',
        ];
    }
}
