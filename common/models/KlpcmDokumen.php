<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "klpcm_dokumen".
 *
 * @property int $id
 * @property int|null $idklpcm
 * @property string|null $dokumen
 * @property string|null $keterangan
 */
class KlpcmDokumen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'klpcm_dokumen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idklpcm'], 'integer'],
            [['dokumen'], 'string', 'max' => 250],
            [['keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idklpcm' => 'Idklpcm',
            'dokumen' => 'Dokumen',
            'keterangan' => 'Keterangan',
        ];
    }
}
