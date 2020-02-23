<?php

namespace App\Presenters;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractPresenter
{
    private $model;

    /**
     * AbstractPresenter constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->setModel($model);
    }

    private function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
}