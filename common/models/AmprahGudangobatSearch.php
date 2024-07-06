<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AmprahGudangobat;

/**
 * AmprahGudangobatSearch represents the model behind the search form of `common\models\AmprahGudangobat`.
 */
class AmprahGudangobatSearch extends AmprahGudangobat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idamprah', 'idpermintaan', 'idasal', 'idpeminta'], 'integer'],
            [['tgl_permintaan', 'tgl_penyerahan', 'keterangan'], 'safe'],
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
        $query = AmprahGudangobat::find();

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
            'idamprah' => $this->idamprah,
            'idpermintaan' => $this->idpermintaan,
            'tgl_permintaan' => $this->tgl_permintaan,
            'tgl_penyerahan' => $this->tgl_penyerahan,
            'idasal' => $this->idasal,
            'idpeminta' => $this->idpeminta,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
