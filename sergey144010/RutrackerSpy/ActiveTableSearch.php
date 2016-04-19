<?php

namespace sergey144010\RutrackerSpy;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use sergey144010\RutrackerSpy\ActiveTable;

class ActiveTableSearch extends ActiveTable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = ActiveTable::find();

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
        $query->andFilterWhere(['like', 'name', iconv("utf-8", "cp1251", $this->name)]);
        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }

}