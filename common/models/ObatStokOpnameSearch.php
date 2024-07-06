<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObatStokopname;

/**
 * ObatStokOpnameSearch represents the model behind the search form of `common\models\ObatStokOpname`.
 */
class ObatStokOpnameSearch extends ObatStokopname
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idperiode', 'iduser', 'idgudang'], 'integer'],
            [['kode_so', 'tgl_so', 'jam_mulai', 'jam_selesai', 'keterangan'], 'safe'],
            [['lama', 'selisih'], 'number'],
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
        $query = ObatStokopname::find();

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
            'tgl_so' => $this->tgl_so,
            'idperiode' => $this->idperiode,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'lama' => $this->lama,
            'selisih' => $this->selisih,
            'iduser' => $this->iduser,
            'idgudang' => $this->idgudang,
        ]);

        $query->andFilterWhere(['like', 'kode_so', $this->kode_so])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
