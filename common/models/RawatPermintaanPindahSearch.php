<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RawatPermintaanPindah;

/**
 * RawatPermintaanPindahSearch represents the model behind the search form of `common\models\RawatPermintaanPindah`.
 */
class RawatPermintaanPindahSearch extends RawatPermintaanPindah
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idrawat', 'idasal', 'idtujuan', 'idbedasal', 'idbedtujuan', 'iduser', 'iduser2', 'status', 'keadaan', 'kesadaran', 'idkelasasal', 'idkelastujuan'], 'integer'],
            [['idkunjungan', 'no_rm', 'keterangan', 'distole', 'sistole', 'suhu', 'spo2', 'nadi', 'respirasi', 'tgl'], 'safe'],
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
        $query = RawatPermintaanPindah::find();

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
            'idasal' => $this->idasal,
            'idtujuan' => $this->idtujuan,
            'idbedasal' => $this->idbedasal,
            'idbedtujuan' => $this->idbedtujuan,
            'iduser' => $this->iduser,
            'iduser2' => $this->iduser2,
            'status' => $this->status,
            'keadaan' => $this->keadaan,
            'kesadaran' => $this->kesadaran,
            'idkelasasal' => $this->idkelasasal,
            'idkelastujuan' => $this->idkelastujuan,
            'tgl' => $this->tgl,
        ]);

        $query->andFilterWhere(['like', 'idkunjungan', $this->idkunjungan])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'distole', $this->distole])
            ->andFilterWhere(['like', 'sistole', $this->sistole])
            ->andFilterWhere(['like', 'suhu', $this->suhu])
            ->andFilterWhere(['like', 'spo2', $this->spo2])
            ->andFilterWhere(['like', 'nadi', $this->nadi])
            ->andFilterWhere(['like', 'respirasi', $this->respirasi]);

        return $dataProvider;
    }
}
