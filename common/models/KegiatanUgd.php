<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kegiatan_ugd".
 *
 * @property int $id
 * @property string|null $kegiatan
 */
class KegiatanUgd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_ugd';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kegiatan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kegiatan' => 'Kegiatan',
        ];
    }
}
