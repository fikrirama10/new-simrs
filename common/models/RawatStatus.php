<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rawat_status".
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $ket
 */
class RawatStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rawat_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'ket'], 'string', 'max' => 50],
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
            'ket' => 'Ket',
        ];
    }
}
