<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SoapRadiologi;

/**
 * SoapRadiologiSearch represents the model behind the search form of `common\models\SoapRadiologi`.
 */
class SoapRadiologiSearch extends SoapRadiologi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idrawat', 'iddokter', 'idtindakan', 'iduser', 'status', 'idhasil', 'idbayar'], 'integer'],
            [['tgl_permintaan', 'tgl_hasil', 'catatan', 'klinis', 'no_rm'], 'safe'],
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
        $query = SoapRadiologi::find()->joinWith(['rawats as rawats'])->orderBy(['tgl_permintaan'=>SORT_DESC,'status'=>SORT_ASC]);

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
            'idrawat' => $this->idrawat,
            'iddokter' => $this->iddokter,
            'idtindakan' => $this->idtindakan,
            'tgl_permintaan' => $this->tgl_permintaan,
            'tgl_hasil' => $this->tgl_hasil,
            'iduser' => $this->iduser,
            'soap_radiologi.status' => $this->status,
            'idhasil' => $this->idhasil,
            'idbayar' => $this->idbayar,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'klinis', $this->klinis])
            ->andFilterWhere(['like', 'rawats.no_rm', $this->no_rm]);

        return $dataProvider;
    }
}
