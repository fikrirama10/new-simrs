<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permintaan_obatstatus".
 *
 * @property int $id
 * @property string|null $status
 */
class PermintaanObatstatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permintaan_obatstatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
        ];
    }
}
