<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "provinsi".
 *
 * @property string $id_prov
 * @property string $nama
 */
class Provinsi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provinsi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_prov', 'nama'], 'required'],
            [['nama'], 'string'],
            [['id_prov'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_prov' => 'Id Prov',
            'nama' => 'Nama',
        ];
    }
}
