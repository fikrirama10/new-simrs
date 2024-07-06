<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SettingSimrs;

/**
 * SettingSimrsSearch represents the model behind the search form of `common\models\SettingSimrs`.
 */
class SettingSimrsSearch extends SettingSimrs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idtema'], 'integer'],
            [['kode_rs', 'nama_rs', 'alamat_rs', 'logo_rs', 'direktur_rs'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SettingSimrs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'idtema' => $this->idtema,
        ]);

        $query->andFilterWhere(['like', 'kode_rs', $this->kode_rs])
            ->andFilterWhere(['like', 'nama_rs', $this->nama_rs])
            ->andFilterWhere(['like', 'alamat_rs', $this->alamat_rs])
            ->andFilterWhere(['like', 'logo_rs', $this->logo_rs])
            ->andFilterWhere(['like', 'direktur_rs', $this->direktur_rs]);

        return $dataProvider;
    }
}
