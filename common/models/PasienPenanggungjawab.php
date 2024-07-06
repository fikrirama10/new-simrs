<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pasien_penanggungjawab".
 *
 * @property int $id
 * @property string|null $penaggungjawab
 */
class PasienPenanggungjawab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pasien_penanggungjawab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penaggungjawab'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'penaggungjawab' => 'Penaggungjawab',
        ];
    }
}
