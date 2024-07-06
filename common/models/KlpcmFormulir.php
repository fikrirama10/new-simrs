<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "klpcm_formulir".
 *
 * @property int $id
 * @property string|null $formulir
 * @property string|null $kode_rm
 * @property string|null $keterangan
 */
class KlpcmFormulir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'klpcm_formulir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formulir', 'kode_rm', 'keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'formulir' => 'Formulir',
            'kode_rm' => 'Kode Rm',
            'keterangan' => 'Keterangan',
        ];
    }
}
