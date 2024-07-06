<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UsulPesan;

/**
 * UsulPesanSearch represents the model behind the search form of `common\models\UsulPesan`.
 */
class UsulPesanSearch extends UsulPesan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'jenis_up', 'iduser'], 'integer'],
            [['kode_up', 'tgl_up', 'tgl_setuju'], 'safe'],
            [['total_harga'], 'number'],
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
        $query = UsulPesan::find();

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
            'tgl_up' => $this->tgl_up,
            'tgl_setuju' => $this->tgl_setuju,
            'total_harga' => $this->total_harga,
            'status' => $this->status,
            'jenis_up' => $this->jenis_up,
            'iduser' => $this->iduser,
        ]);

        $query->andFilterWhere(['like', 'kode_up', $this->kode_up]);

        return $dataProvider;
    }
}
