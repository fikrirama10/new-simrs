<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PermintaanObat;

/**
 * PermintaanObatSearch represents the model behind the search form of `common\models\PermintaanObat`.
 */
class PermintaanObatSearch extends PermintaanObat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iduser_peminta', 'iduser_persetujuan', 'status','asal_permintaan'], 'integer'],
            [['kode_permintaan', 'tgl_permintaan', 'keterangan'], 'safe'],
            [['total_biaya'], 'number'],
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
        $query = PermintaanObat::find();

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
            'asal_permintaan' => $this->asal_permintaan,
            'tgl_permintaan' => $this->tgl_permintaan,
            'iduser_peminta' => $this->iduser_peminta,
            'iduser_persetujuan' => $this->iduser_persetujuan,
            'status' => $this->status,
            'total_biaya' => $this->total_biaya,
        ]);

        $query->andFilterWhere(['like', 'kode_permintaan', $this->kode_permintaan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
