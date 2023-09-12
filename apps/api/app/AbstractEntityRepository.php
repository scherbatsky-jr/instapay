<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class AbstractEntityRepository
{
    protected $entity_key = 'id';
    protected $user = null;

    public function create(array $data, array $fields = []): Model
    {
        $entity = $this->getModel();

        $entity->fill($data);

        $entity->save();

        $entity->refresh();

        return $entity;
    }

    public function get(array $fields = [], array $args = []): Collection
    {
        $entities = $this->getListQuery($fields, $args)
            ->get();

        $entities = $this->applyFiltersAndSortsToEntities($entities, $fields, $args);

        return $entities;
    }

    public function getById($id, array $fields = []): ?Model
    {
        $query = $this->createBaseBuilder($fields, []);

        return $query->find($id);
    }

    public function getListQuery(array $fields = [], array $args = [])
    {
        $query = $this->createBaseBuilder($fields, $args);

        $this->applyFiltersAndSortsToQuery($query, $fields, $args);

        return $query;
    }

    abstract public function getModelClass(): string;

    final public function getUser()
    {
        return $this->user;
    }

    /**
     * Get resources by a where clause.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return Collection
     */
    public function getWhere($column, $value, array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->where($column, $value);

        return $query->get();
    }

    final public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function update(Model $entity, array $data = [], array $fields = []): ?Model
    {
        $entity->update($data);

        $entity->refresh();

        return $entity;
    }

    final protected function applyFilter($query, array $filter, &$joins, $or = false)
    {
        $key = $filter['key'];
        $operator = data_get($filter, 'operator', 'eq');
        $not = data_get($filter, 'not', false);
        $value = $filter['value'];

        $db_type = $query->getConnection()->getDriverName();

        $table = $query->getModel()->getTable();

        if ('null' === $value || '' === $value) {
            $method = $not ? 'WhereNotNull' : 'WhereNull';

            call_user_func([$query, $method], sprintf('%s.%s', $table, $key));
        } else {
            $method = filter_var($or, FILTER_VALIDATE_BOOLEAN) ? 'orWhere' : 'where';
            $clause_operator = null;
            $database_field = null;

            switch ($operator) {
                case 'ct':
                case 'sw':
                case 'ew':
                    $value_string = [
                        'ct' => '%'.$value.'%', // contains
                        'ew' => '%'.$value, // ends with
                        'sw' => $value.'%', // starts with
                    ];

                    $cast_to_text = (('postgres' === $db_type) ? 'TEXT' : 'CHAR');
                    $database_field = DB::raw(sprintf('CAST(%s.%s AS '.$cast_to_text.')', $table, $key));
                    $clause_operator = ($not ? 'NOT' : '').(('postgres' === $db_type) ? 'ILIKE' : 'LIKE');
                    $value = $value_string[$operator];

                    break;
                case 'eq':
                default:
                    $clause_operator = $not ? '!=' : '=';

                    break;
                case 'gt':
                    $clause_operator = $not ? '<' : '>';

                    break;
                case 'gte':
                    $clause_operator = $not ? '<' : '>=';

                    break;
                case 'lte':
                    $clause_operator = $not ? '>' : '<=';

                    break;
                case 'lt':
                    $clause_operator = $not ? '>' : '<';

                    break;
                case 'in':
                    if (true === $or) {
                        $method = true === $not ? 'orWhereNotIn' : 'orWhereIn';
                    } else {
                        $method = true === $not ? 'whereNotIn' : 'whereIn';
                    }

                    $clause_operator = false;

                    break;
                case 'bt':
                    if (true === $or) {
                        $method = true === $not ? 'orWhereNotBetween' : 'orWhereBetween';
                    } else {
                        $method = true === $not ? 'whereNotBetween' : 'whereBetween';
                    }

                    $clause_operator = false;

                    break;
            }

            // If we do not assign database field, the customer filter method
            // will fail when we execute it with parameters such as CAST(%s AS TEXT)
            // key needs to be reserved
            if (is_null($database_field)) {
                $database_field = sprintf('%s.%s', $table, $key);
            }

            $custom_filter_method = $this->hasCustomMethod('filter', $key);

            if ($custom_filter_method) {
                $joins[] = $key;

                call_user_func_array(
                    [$this, $custom_filter_method],
                    [
                        $query,
                        $method,
                        $clause_operator,
                        $value,
                    ]
                );
            } else {
                // In operations do not have an operator
                if (in_array($operator, ['in', 'bt'])) {
                    call_user_func_array(
                        [$query, $method],
                        [
                            $database_field,
                            $value,
                        ]
                    );
                } else {
                    call_user_func_array(
                        [$query, $method],
                        [
                            $database_field,
                            $clause_operator,
                            $value,
                        ]
                    );
                }
            }
        }
    }

    final protected function applyFilters($query, $filters, &$joins, $or = false)
    {
        $method = $or ? 'orWhere' : 'where';

        if (isset($filters['AND'])) {
            $query->{$method}(
                function ($query) use ($filters, &$joins) {
                    foreach ($filters['AND'] as $filter) {
                        $this->applyFilters($query, $filter, $joins);
                    }
                }
            );

            return;
        }

        if (isset($filters['OR'])) {
            $query->{$method}(
                function ($query) use ($filters, &$joins) {
                    foreach ($filters['OR'] as $filter) {
                        $this->applyFilters($query, $filter, $joins, true);
                    }
                }
            );

            return;
        }

        $this->applyFilter($query, $filters, $joins, $or);
    }

    protected function applyFiltersAndSortsToEntities($entities, array $fields = [], array $args = [])
    {
        return $entities;
    }

    protected function applyFiltersAndSortsToQuery($query, array $fields = [], array $args = [])
    {
        $filter_join_keys = [];

        if (isset($args['filters'])) {
            $this->applyFilters($query, $args['filters'], $filter_join_keys);
        }

        $sort_join_keys = $this->applySortToQuery($query, $args);

        $joins = collect($filter_join_keys)
            ->merge($sort_join_keys)
            ->unique()
            ->toArray();

        $aggregated_columns = [];

        $this->applyJoinsToQuery($query, $joins, $aggregated_columns);

        $table = $query->getModel()->getTable();

        $selects = ["$table.*"];

        foreach ($aggregated_columns as $select) {
            $selects[] = DB::raw($select);
        }

        $query
            ->select($selects);
    }

    final protected function applyJoinsToQuery($query, array $joins, &$aggregated_columns)
    {
        foreach ($joins as $key) {
            $custom_join_method = $this->hasCustomMethod('join', $key);

            if ($custom_join_method) {
                $aggregated_column = $this->{$custom_join_method}($query);

                if ($aggregated_column && is_string($aggregated_column)) {
                    $aggregated_columns[] = $aggregated_column;
                }
            }
        }
    }

    final protected function applySortToQuery($query, array $args = [])
    {
        $joins = [];

        if (!isset($args['sort'])) {
            return;
        }

        $sort = $args['sort'];

        if (!isset($sort[0])) {
            $sort = [$sort];
        }

        foreach ($sort as $sort_data) {
            $key = $sort_data['key'];
            $direction = strtolower($sort_data['direction']);

            $custom_method = $this->hasCustomMethod('sort', $key);

            if ($custom_method) {
                $joins[] = $key;

                $this->{$custom_method}($query, $direction);
            } else {
                $query
                    ->orderBy($key, $direction);
            }
        }

        return $joins;
    }

    /**
     * This is a helper function included to be used for custom join methods for convenience.
     * This method can be used for checking if the table has been already joined to the query.
     * Since multiple joins of the same table results in an query error.
     *
     * @return bool
     */
    final protected function checkQueryHasJoinedTable(Builder $query, $table)
    {
        $joins = $query->getQuery()->joins;

        if (empty($joins)) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    protected function createBaseBuilder(array $fields = [], array $args = [])
    {
        $query = $this->getModelClass()::query();

        $this->eagerLoadQuery($query, $fields, $args);

        return $query;
    }

    protected function eagerLoadQuery($query, array $fields = [], array $args = [])
    {
        return $query;
    }

    protected function getModel(): Model
    {
        $class = $this->getModelClass();

        return new $class();
    }

    private function hasCustomMethod($type, $key)
    {
        $method_name = Str::camel($type."_$key");

        if (method_exists($this, $method_name)) {
            return $method_name;
        }

        return false;
    }
}
