<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tindakan;

/**
 * TindakanSeach represents the model behind the search form of `common\models\Tindakan`.
 */
class TindakanSeach extends Tindakan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenistindakan', 'idpoli', 'idpenerimaan', 'idjenispenerimaan', 'status'], 'integer'],
            [['nama_tindakan', 'kode_tindakan'], 'safe'],
           
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
        $query = Tindakan::find();

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
            'idjenistindakan' => $this->idjenistindakan,
            'idpoli' => $this->idpoli,
            'idpenerimaan' => $this->idpenerimaan,
            'idjenispenerimaan' => $this->idjenispenerimaan,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nama_tindakan', $this->nama_tindakan])
            ->andFilterWhere(['like', 'kode_tindakan', $this->kode_tindakan]);

        return $dataProvider;
    }
}
