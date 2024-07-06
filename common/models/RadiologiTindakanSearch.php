<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RadiologiTindakan;

/**
 * RadiologiTindakanSearch represents the model behind the search form of `common\models\RadiologiTindakan`.
 */
class RadiologiTindakanSearch extends RadiologiTindakan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idpelayanan', 'idrad', 'status', 'idtindakan', 'idbayar'], 'integer'],
            [['kode_tindakan', 'nama_tindakan', 'keterangan'], 'safe'],
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
        $query = RadiologiTindakan::find();

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
            'idpelayanan' => $this->idpelayanan,
            'idrad' => $this->idrad,
            'status' => $this->status,
            'idtindakan' => $this->idtindakan,
            'idbayar' => $this->idbayar,
        ]);

        $query->andFilterWhere(['like', 'kode_tindakan', $this->kode_tindakan])
            ->andFilterWhere(['like', 'nama_tindakan', $this->nama_tindakan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
