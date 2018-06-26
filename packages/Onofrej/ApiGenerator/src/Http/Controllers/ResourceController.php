<?php

namespace Onofrej\ApiGenerator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ResourceController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getFields($resource)
    {
        $resource = $this->getResourceName($resource);
        $fields = \Schema::getColumnListing((new $resource())->getTable());

        return $fields;
    }

    private function getResourceName($resource)
    {
        $resources = [
            'tag' => 'App\Tag',
            'article' => 'App\Article',
            'page' => 'App\Page',
      	    'menuItem' => 'App\MenuItem',
      	    'hamburg' => 'App\Hamburg'
        ];

        return $resources[$resource];
    }

    public function index()
    {
        $sort = request('sort');
        $limit = request('limit');
        $offset = request('offset');
        $with = request('with');
        $filter = request('filter', []);

        $query = $this->model::query();
        $this->filterData($query, $filter);

        if($with) {
          $query->with(explode(',', $with));
        }

        if($offset && $limit) {
          $query->offset($offset);
          $query->limit($limit);
        }

        if($sort) {
          list($sortBy, $direction) = explode(',', $sort);
          $query->orderBy($sortBy, $direction);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $model = new $this->model();
        $model->fill($request->all());
        $model->save();

        return response()->json($model);
    }

    public function update(Request $request, $id)
    {
      $model = $this->model::find($id);

      $model->fill($request->all());
      $model->save();

      return response()->json($model);
    }

    public function destroy($id)
    {
        //
    }

    public function getOperators()
    {
        return [
            'like' => 'like',
            'gt' => '>',
            'gte' => '>=',
            'lt' => '<',
            'lte' => '<=',
            'eq' => '=',
            'neq' => '<>',
            'in' => 'whereIn',
            'between' => 'whereBetween'
        ];
    }

    public function filterData($query, $filter)
    {
        $operators = $this->getOperators();

        foreach($filter as $key => $ops) {
          foreach($ops as $operator => $value) {
            $value = trim($value, '"[]');
            if($operator == 'between') {
              $query->whereBetween($key, explode(',', $value));
            } else if($operator == 'not_between') {
              $query->whereNotBetween($key, explode(',', $value));
            } else if ($operator == 'in') {
              $query->whereIn($key, explode(',', $value));
            } else if ($operator == 'not_in') {
              $query->whereNotIn($key, explode(',', $value));
            } else {
              $query->where($key, $operators[$operator], $value);
            }
          }
        }
    }
}
