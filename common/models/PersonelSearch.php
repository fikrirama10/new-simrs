<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Personel;

/**
 * PersonelSearch represents the model behind the search form of `common\models\Personel`.
 */
class PersonelSearch extends Personel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjabatan', 'idpangkat', 'status', 'idjenis'], 'integer'],
            [['kode_personel', 'nik', 'no_bpjs', 'nrp_nip', 'nama_lengkap', 'alamat', 'foto', 'no_tlp', 'mulai_bergabung', 'akhir_bergabung'], 'safe'],
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
        $query = Personel::find();

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
            'idjabatan' => $this->idjabatan,
            'idpangkat' => $this->idpangkat,
            'status' => $this->status,
            'idjenis' => $this->idjenis,
            'mulai_bergabung' => $this->mulai_bergabung,
            'akhir_bergabung' => $this->akhir_bergabung,
        ]);

        $query->andFilterWhere(['like', 'kode_personel', $this->kode_personel])
            ->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'no_bpjs', $this->no_bpjs])
            ->andFilterWhere(['like', 'nrp_nip', $this->nrp_nip])
            ->andFilterWhere(['like', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'no_tlp', $this->no_tlp]);

        return $dataProvider;
    }
}
