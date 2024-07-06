<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_simrs_tema".
 *
 * @property int $id
 * @property string|null $tema
 */
class SettingSimrsTema extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_simrs_tema';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tema'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tema' => 'Tema',
        ];
    }
}
