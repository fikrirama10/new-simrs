<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tarif;

/**
 * TarifSearch represents the model behind the search form of `common\models\Tarif`.
 */
class TarifSearch extends Tarif
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idkategori', 'idjenisrawat', 'kat_tindakan', 'idpoli', 'idkelas','idruangan'], 'integer'],
            [['kode_tarif', 'nama_tarif'], 'safe'],
            [['medis', 'paramedis', 'petugas', 'apoteker', 'gizi', 'bph', 'sewakamar', 'sewaalat', 'makanpasien', 'laundry', 'cs', 'opsrs', 'nova_t', 'perekam_medis', 'tarif'], 'number'],
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
        $query = Tarif::find();

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
            'idkategori' => $this->idkategori,
            'idjenisrawat' => $this->idjenisrawat,
            'idruangan' => $this->idruangan,
            'kat_tindakan' => $this->kat_tindakan,
            'idpoli' => $this->idpoli,
            'idkelas' => $this->idkelas,
            'medis' => $this->medis,
            'paramedis' => $this->paramedis,
            'petugas' => $this->petugas,
            'apoteker' => $this->apoteker,
            'gizi' => $this->gizi,
            'bph' => $this->bph,
            'sewakamar' => $this->sewakamar,
            'sewaalat' => $this->sewaalat,
            'makanpasien' => $this->makanpasien,
            'laundry' => $this->laundry,
            'cs' => $this->cs,
            'opsrs' => $this->opsrs,
            'nova_t' => $this->nova_t,
            'perekam_medis' => $this->perekam_medis,
            'tarif' => $this->tarif,
        ]);

        $query->andFilterWhere(['like', 'kode_tarif', $this->kode_tarif])
            ->andFilterWhere(['like', 'nama_tarif', $this->nama_tarif]);

        return $dataProvider;
    }
}
