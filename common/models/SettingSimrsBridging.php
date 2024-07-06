<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_simrs_bridging".
 *
 * @property int $id
 * @property string|null $cons_id
 * @property string|null $secret_key
 * @property string|null $type
 * @property int|null $status
 */
class SettingSimrsBridging extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_simrs_bridging';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['status'], 'integer'],
            [['cons_id'], 'string', 'max' => 50],
            [['secret_key'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cons_id' => 'Cons ID',
            'secret_key' => 'Secret Key',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }
}
