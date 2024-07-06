<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Dokter;

/**
 * DokterSeach represents the model behind the search form of `common\models\Dokter`.
 */
class DokterSeach extends Dokter
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idpoli', 'status','idspesialis'], 'integer'],
            [['kode_dokter', 'sip', 'nama_dokter', 'jenis_kelamin', 'tgl_lahir', 'foto'], 'safe'],
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
        $query = Dokter::find();

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
            'idpoli' => $this->idpoli,
            'tgl_lahir' => $this->tgl_lahir,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'kode_dokter', $this->kode_dokter])
            ->andFilterWhere(['like', 'sip', $this->sip])
            ->andFilterWhere(['like', 'nama_dokter', $this->nama_dokter])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'foto', $this->foto]);

        return $dataProvider;
    }
}
