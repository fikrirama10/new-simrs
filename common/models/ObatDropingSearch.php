<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatDroping;

/**
 * ObatDropingSearch represents the model behind the search form of `common\models\ObatDroping`.
 */
class ObatDropingSearch extends ObatDroping
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'idsatuan'], 'integer'],
            [['kode_obat', 'nama_obat'], 'safe'],
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
        $query = ObatDroping::find();

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
            'idjenis' => $this->idjenis,
            'idsatuan' => $this->idsatuan,
        ]);

        $query->andFilterWhere(['like', 'kode_obat', $this->kode_obat])
            ->andFilterWhere(['like', 'nama_obat', $this->nama_obat]);

        return $dataProvider;
    }
}
