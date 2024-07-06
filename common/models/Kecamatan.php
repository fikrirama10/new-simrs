<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kecamatan".
 *
 * @property string $id_kec
 * @property string $id_kab
 * @property string $nama
 */
class Kecamatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kecamatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kec', 'id_kab', 'nama'], 'required'],
            [['nama'], 'string'],
            [['id_kec'], 'string', 'max' => 6],
            [['id_kab'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_kec' => 'Id Kec',
            'id_kab' => 'Id Kab',
            'nama' => 'Nama',
        ];
    }
	public function getKabupaten()
    {
        return $this->hasOne(Kabupaten::className(), ['id_kab' => 'id_kab']);
    }
}
