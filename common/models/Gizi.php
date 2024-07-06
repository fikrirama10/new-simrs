<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gizi".
 *
 * @property int $id
 * @property int|null $idrawat
 * @property string|null $no_rm
 * @property string|null $tgl
 * @property string|null $diit
 * @property int|null $iddokter
 */
class Gizi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gizi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrawat', 'iddokter'], 'integer'],
            [['tgl'], 'safe'],
            [['diit'], 'string'],
            [['no_rm'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idrawat' => 'Idrawat',
            'no_rm' => 'No Rm',
            'tgl' => 'Tgl',
            'diit' => 'Diit',
            'iddokter' => 'Iddokter',
        ];
    }
}
