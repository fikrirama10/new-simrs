<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_simrs".
 *
 * @property int $id
 * @property string|null $kode_rs
 * @property string|null $nama_rs
 * @property string|null $alamat_rs
 * @property string|null $logo_rs
 * @property string|null $direktur_rs
 * @property int|null $idtema
 */
class SettingSimrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_simrs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alamat_rs','no_tlp'], 'string'],
            [['idtema'], 'integer'],
            [['kode_rs', 'nama_rs', 'direktur_rs'], 'string', 'max' => 50],
            [['logo_rs'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_rs' => 'Kode Rs',
            'nama_rs' => 'Nama Rs',
            'alamat_rs' => 'Alamat Rs',
            'logo_rs' => 'Logo Rs',
            'direktur_rs' => 'Direktur Rs',
            'idtema' => 'Idtema',
        ];
    }
}
