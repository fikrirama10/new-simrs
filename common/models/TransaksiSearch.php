<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transaksi;

/**
 * TransaksiSearch represents the model behind the search form of `common\models\Transaksi`.
 */
class TransaksiSearch extends Transaksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iduser', 'status', 'idkunjungan'], 'integer'],
            [['idtransaksi', 'tgltransaksi', 'no_rm', 'tgl_masuk', 'tgl_keluar', 'kode_kunjungan', 'keterangan'], 'safe'],
            [['total', 'total_bayar', 'total_ditanggung', 'diskon'], 'number'],
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
        $query = Transaksi::find()->orderBy(['status'=>SORT_DESC])->orderBy(['tgltransaksi'=>SORT_DESC]);

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
           // 'tgltransaksi' => $this->tgltransaksi,
            'iduser' => $this->iduser,
            'status' => $this->status,
            'total' => $this->total,
            'idkunjungan' => $this->idkunjungan,
            'tgl_masuk' => $this->tgl_masuk,
            'tgl_keluar' => $this->tgl_keluar,
            'total_bayar' => $this->total_bayar,
            'total_ditanggung' => $this->total_ditanggung,
            'diskon' => $this->diskon,
        ]);

        $query->andFilterWhere(['like', 'idtransaksi', $this->idtransaksi])
            ->andFilterWhere(['like', 'no_rm', $this->no_rm])
            ->andFilterWhere(['like', 'kode_kunjungan', $this->kode_kunjungan])
            ->andFilterWhere(['like', 'tgltransaksi', $this->tgltransaksi])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
