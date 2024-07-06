<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Obat;

/**
 * ObatSeacrh represents the model behind the search form of `common\models\Obat`.
 */
class ObatSeacrh extends Obat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idjenis', 'idsatuan', 'narkotika', 'psikotropika', 'antibiotik', 'fornas'], 'integer'],
            [['nama_obat', 'kandungan'], 'safe'],
            [['min_stokgudang', 'min_stokapotek'], 'number'],
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
        $query = Obat::find()->orderBy(['nama_obat'=>SORT_ASC]);

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
            'min_stokgudang' => $this->min_stokgudang,
            'min_stokapotek' => $this->min_stokapotek,
            'idjenis' => $this->idjenis,
            'idsatuan' => $this->idsatuan,
            'narkotika' => $this->narkotika,
            'psikotropika' => $this->psikotropika,
            'antibiotik' => $this->antibiotik,
            'fornas' => $this->fornas,
        ]);

        $query->andFilterWhere(['like', 'nama_obat', $this->nama_obat])
            ->andFilterWhere(['like', 'kandungan', $this->kandungan]);

        return $dataProvider;
    }
}
