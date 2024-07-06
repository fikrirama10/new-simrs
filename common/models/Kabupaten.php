<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kabupaten".
 *
 * @property string $id_kab
 * @property string $id_prov
 * @property string $nama
 * @property integer $id_jenis
 */
class Kabupaten extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kabupaten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kab', 'id_prov', 'nama', 'id_jenis'], 'required'],
            [['nama'], 'string'],
            [['id_jenis','ok'], 'integer'],
            [['id_kab'], 'string', 'max' => 4],
            [['id_prov'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_kab' => 'Id Kab',
            'id_prov' => 'Id Prov',
            'nama' => 'Nama',
            'id_jenis' => 'Id Jenis',
        ];
    }
	public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id_prov' => 'id_prov']);
    }
}
