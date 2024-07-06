<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_privilages".
 *
 * @property int $id
 * @property string $privilages
 */
class UserPrivilages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_privilages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['privilages'], 'required'],
            [['privilages'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'privilages' => 'Privilages',
        ];
    }
}
