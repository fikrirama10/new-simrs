<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatDropingTransaksi;

/**
 * ObatDropingTransaksiSearch represents the model behind the search form of `common\models\ObatDropingTransaksi`.
 */
class ObatDropingTransaksiSearch extends ObatDropingTransaksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'iduser', 'status'], 'integer'],
            [['idtrx', 'ketrangan', 'tgl'], 'safe'],
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
        $query = ObatDropingTransaksi::find();

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
            'tgl' => $this->tgl,
            'iduser' => $this->iduser,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'idtrx', $this->idtrx])
            ->andFilterWhere(['like', 'ketrangan', $this->ketrangan]);

        return $dataProvider;
    }
}
