<?php

namespace Framework\Mongo;

use Framework\Base\Database\DatabaseQueryInterface;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Regex;

/**
 * Class MongoQuery
 * @package Framework\Base\Mongo
 */
class MongoQuery implements DatabaseQueryInterface
{
    private static $translation = [
        '=' => '$eq',
        '!=' => '$ne',
        '<' => '$lt',
        '>' => '$gt',
        '<=' => '$lte',
        '>=' => '$gte',
        'in' => '$in',
        'not in' => '$nin',
        'and' => '$and',
        'or' => '$or',
    ];
    /**
     * @var string
     */
    private $database = '';
    /**
     * @var string
     */
    private $collection = '';
    /**
     * @var string
     */
    private $offset = '';
    /**
     * @var string
     */
    private $limit = '';
    /**
     * @var string
     */
    private $orderBy = '_id';
    /**
     * @var string
     */
    private $orderDirection = 'desc';
    /**
     * @var array
     */
    private $selectFields = [];
    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @return array
     */
    public function build()
    {
        return $this->conditions;
    }

    /**
     * @param string $field
     * @param string $operation
     * @param        $value
     *
     * @return DatabaseQueryInterface
     */
    public function addAndCondition(string $field, string $operation, $value): DatabaseQueryInterface
    {
        if ($operation === 'like') {
            $operation = new Regex(".*" . $value . ".*", "i");
        } else {
            $operation = self::$translation[$operation];
        }

        if ($field === '_id') {
            $value = new ObjectID($value);
        }

        if ($operation instanceof Regex) {
            $queryPart = [$field => $operation];
        } else {
            $queryPart = [$field => [$operation => $value]];
        }

        $this->conditions = array_merge_recursive($this->conditions, $queryPart);

        return $this;
    }

    /**
     * @param string $field
     * @param array  $value
     *
     * @return DatabaseQueryInterface
     */
    public function whereInArrayCondition(string $field, $value = []): DatabaseQueryInterface
    {
        $queryPart = [$field => ['$in' => $value]];

        $this->conditions = array_merge_recursive($this->conditions, $queryPart);

        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setDatabase(string $name): DatabaseQueryInterface
    {
        $this->database = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function setCollection(string $name): DatabaseQueryInterface
    {
        $this->collection = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return DatabaseQueryInterface
     */
    public function setOffset(int $offset): DatabaseQueryInterface
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return DatabaseQueryInterface
     */
    public function setLimit(int $limit): DatabaseQueryInterface
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return DatabaseQueryInterface
     */
    public function addSelectField(string $name): DatabaseQueryInterface
    {
        $this->selectFields[] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getSelectFields()
    {
        return $this->selectFields;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $identifier
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderBy(string $identifier): DatabaseQueryInterface
    {
        $this->orderBy = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param string $orderDirection
     *
     * @return DatabaseQueryInterface
     */
    public function setOrderDirection(string $orderDirection): DatabaseQueryInterface
    {
        $this->orderDirection = $orderDirection;

        return $this;
    }
}
