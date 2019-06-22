<?php
/**
 * Created by PhpStorm.
 * User: klinson <klinson@163.com>
 * Date: 2017/6/29
 * Time: 18:04
 */

namespace Lihq1403\ThinkRbac\helper;


class PageHelper
{
    protected $Model = false;
    protected $where = [];
    protected $join = [];
    protected $page = 1;
    protected $pageRows = 10;
    protected $order = '';
    protected $getFields = true;
    protected $with = [];
    protected $withCount = [];
    protected $alias = '';
    protected $countField = '';
    protected $group = '';
    protected $having = '';
    protected $or_where = [];


    /**
     * PageHelper constructor.
     * @param \think\Model $Model
     *
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    public function result()
    {
        $result = [
            'page' => $this->page,
            'total' => $this->count(),
            'list' => [],
        ];
        if ($result['total']) {
            $result['list'] = $this->getList();
        }
        return $result;
    }

    public function count()
    {
        $query = $this->Model;
        if (!empty($this->alias)) {
            $query = $query->alias($this->alias);
        }
        if (!empty($this->where)) {
            $query = $query->where($this->where);
        }
        if (!empty($this->or_where)) {
            $query = $query->whereOr($this->or_where);
        }
        if (!empty($this->group)) {
            $query = $query->group($this->group);
        }
        if (!empty($this->join)) {
            $query = $query->join($this->join);
        }
        if (!empty($this->having)) {
            $query = $query->having($this->having);
        }
        if (empty($this->countField)) {
            $this->countField = '*';
        }
        return $query->count($this->countField);
    }

    public function getList()
    {
        $query = $this->Model;
        if (!empty($this->alias)) {
            $query = $query->alias($this->alias);
        }
        if (!empty($this->where)) {
            $query = $query->where($this->where);
        }
        if (!empty($this->or_where)) {
            $query = $query->whereOr($this->or_where);
        }
        if (!empty($this->group)) {
            $query = $query->group($this->group);
        }
        if (!empty($this->join)) {
            $query = $query->join($this->join);
        }
        if (!empty($this->order)) {
            $query = $query->order($this->order);
        }
        if (!empty($this->getFields)) {
            $query = $query->field($this->getFields);
        }
        if (!empty($this->with)) {
            $query = $query->with($this->with);
        }
        if (!empty($this->withCount)) {
            $query = $query->withCount($this->withCount);
        }
        if (!empty($this->having)) {
            $query = $query->having($this->having);
        }
        $query = $query->page($this->page, $this->pageRows);
        return $query->select()->toArray();
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function join($join)
    {
        $this->join = $join;
        return $this;
    }

    public function page($page)
    {
        $page = intval($page);
        if ($page > 0) {
            $this->page = $page;
        }
        return $this;
    }

    public function pageRows($page_rows)
    {
        $page_rows = intval($page_rows);
        if ($page_rows > 0) {
            $this->pageRows = $page_rows;
        }
        return $this;
    }

    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setFields($fields)
    {
        $this->getFields = $fields;
        return $this;
    }

    public function with($with)
    {
        $this->with = $with;
        return $this;
    }

    public function withCount($with_count)
    {
        $this->withCount = $with_count;
        return $this;
    }

    public function alias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function countField($count_field)
    {
        $this->countField = $count_field;
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    public function having($having)
    {
        $this->having = $having;
        return $this;
    }

    public function whereOr($or_where)
    {
        $this->or_where = $or_where;
        return $this;
    }

}