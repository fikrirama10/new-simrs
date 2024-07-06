<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_status".
 *
 * @property int $id
 * @property string|null $status
 *
 * @property PasienStatus[] $pasienStatuses
 */
class DataStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_status';
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

    /**
     * Gets query for [[PasienStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPasienStatuses()
    {
        return $this->hasMany(PasienStatus::className(), ['idstatus' => 'id']);
    }
}
