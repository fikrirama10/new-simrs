<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RawatRujukan;

/**
 * RawatRujukanSearch represents the model behind the search form of `common\models\RawatRujukan`.
 */
class RawatRujukanSearch extends RawatRujukan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idrawat', 'idjenisrawat', 'idbayar', 'idpoli', 'iddokter', 'iduser', 'status'], 'integer'],
            [['kode_tujuan', 'kode_asal', 'faskes_tujuan', 'faskes_asal', 'no_rujukan', 'kode_rujukan', 'alasan_rujuk', 'tgl_rujuk', 'tgl_kunjungan', 'idspesialis', 'no_sep', 'catatan', 'kode_dokter', 'tujuan_rujuk', 'diagnosa_klinis', 'icd10', 'no_rm', 'jenis_rujukan'], 'safe'],
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
    public function search($params, $where=null, $andWhere=null,$andWhere2=null,$andWhereTgl=null, $andFilterWhere=null, $andFilterWhere2=null, $orderBy = null ,$groupBy=null)
    {
        $query = RawatRujukan::find();

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
		if($where){
			$query->where($where);
		}
		if($andWhere){
			$query->andWhere($andWhere);
		}
        if($andWhere2){
            $query->andWhere($andWhere2);
        }
		if($andWhereTgl){
            $query->andWhere($andWhereTgl);
        }
		
		if($andFilterWhere){
			$query->andFilterWhere($andFilterWhere);
		}
		
		if($andFilterWhere2){
			$query->andFilterWhere($andFilterWhere2);
		}
		
		if($orderBy){
			$query->orderBy($orderBy);
		}
		
		if($groupBy){
			$query->groupBy($groupBy);
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'idrawat' => $this->idrawat,
            'idjenisrawat' => $this->idjenisrawat,
            'idbayar' => $this->idbayar,
            'idpoli' => $this->idpoli,
            'iddokter' => $this->iddokter,
            'tgl_rujuk' => $this->tgl_rujuk,
            'tgl_kunjungan' => $this->tgl_kunjungan,
            'iduser' => $this->iduser,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'kode_tujuan', $this->kode_tujuan])
            ->andFilterWhere(['like', 'kode_asal', $this->kode_asal])
            ->andFilterWhere(['like', 'faskes_tujuan', $this->faskes_tujuan])
            ->andFilterWhere(['like', 'faskes_asal', $this->faskes_asal])
            ->andFilterWhere(['like', 'no_rujukan', $this->no_rujukan])
            ->andFilterWhere(['like', 'kode_rujukan', $this->kode_rujukan])
            ->andFilterWhere(['like', 'alasan_rujuk', $this->alasan_rujuk])
            ->andFilterWhere(['like', 'idspesialis', $this->idspesialis])
            ->andFilterWhere(['like', 'no_sep', $this->no_sep])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'kode_dokter', $this->kode_dokter])
            ->andFilterWhere(['like', 'tujuan_rujuk', $this->tujuan_rujuk])
            ->andFilterWhere(['like', 'diagnosa_klinis', $this->diagnosa_klinis])
            ->andFilterWhere(['like', 'icd10', $this->icd10])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'jenis_rujukan', $this->jenis_rujukan]);

        return $dataProvider;
    }
}
