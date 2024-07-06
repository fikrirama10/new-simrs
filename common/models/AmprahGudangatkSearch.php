<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AmprahGudangatk;

/**
 * AmprahGudangatkSearch represents the model behind the search form of `common\models\AmprahGudangatk`.
 */
class AmprahGudangatkSearch extends AmprahGudangatk
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idpermintaan', 'idasal', 'idpeminta', 'status'], 'integer'],
            [['idamprah', 'tgl_permintaan', 'tgl_penyerahan', 'keterangan'], 'safe'],
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
        $query = AmprahGudangatk::find();

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
            'idpermintaan' => $this->idpermintaan,
            'tgl_permintaan' => $this->tgl_permintaan,
            'tgl_penyerahan' => $this->tgl_penyerahan,
            'idasal' => $this->idasal,
            'idpeminta' => $this->idpeminta,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'idamprah', $this->idamprah])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
