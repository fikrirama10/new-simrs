<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "undangan".
 *
 * @property int $id
 * @property string|null $nama
 * @property string|null $instansi
 */
class Undangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'undangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'instansi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'instansi' => 'Instansi',
        ];
    }
}
