<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserDetail;

/**
 * UserDetailSearch represents the model behind the search form of `common\models\UserDetail`.
 */
class UserDetailSearch extends UserDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iddokter', 'idpoli', 'dokter','idunit'], 'integer'],
            [['kode_user', 'email', 'nama', 'nohp', 'alamat', 'jenis_kelamin', 'foto'], 'safe'],
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
        $query = UserDetail::find();

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
            'iddokter' => $this->iddokter,
            'idpoli' => $this->idpoli,
            'idunit' => $this->idunit,
            'dokter' => $this->dokter,
        ]);

        $query->andFilterWhere(['like', 'kode_user', $this->kode_user])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nohp', $this->nohp])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'foto', $this->foto]);

        return $dataProvider;
    }
}
