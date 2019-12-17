<?php

namespace Mailjet\Model\Base;

use \Exception;
use \PDO;
use Mailjet\Model\MailjetContactList as ChildMailjetContactList;
use Mailjet\Model\MailjetContactListQuery as ChildMailjetContactListQuery;
use Mailjet\Model\Map\MailjetContactListTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mailjet_contact_list' table.
 *
 *
 *
 * @method     ChildMailjetContactListQuery orderByIdCl($order = Criteria::ASC) Order by the id_cl column
 * @method     ChildMailjetContactListQuery orderByNameCl($order = Criteria::ASC) Order by the name_cl column
 * @method     ChildMailjetContactListQuery orderBySlugCl($order = Criteria::ASC) Order by the slug_cl column
 * @method     ChildMailjetContactListQuery orderByLocale($order = Criteria::ASC) Order by the locale column
 * @method     ChildMailjetContactListQuery orderByDefaultList($order = Criteria::ASC) Order by the default_list column
 *
 * @method     ChildMailjetContactListQuery groupByIdCl() Group by the id_cl column
 * @method     ChildMailjetContactListQuery groupByNameCl() Group by the name_cl column
 * @method     ChildMailjetContactListQuery groupBySlugCl() Group by the slug_cl column
 * @method     ChildMailjetContactListQuery groupByLocale() Group by the locale column
 * @method     ChildMailjetContactListQuery groupByDefaultList() Group by the default_list column
 *
 * @method     ChildMailjetContactListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailjetContactListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailjetContactListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailjetContactList findOne(ConnectionInterface $con = null) Return the first ChildMailjetContactList matching the query
 * @method     ChildMailjetContactList findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailjetContactList matching the query, or a new ChildMailjetContactList object populated from the query conditions when no match is found
 *
 * @method     ChildMailjetContactList findOneByIdCl(int $id_cl) Return the first ChildMailjetContactList filtered by the id_cl column
 * @method     ChildMailjetContactList findOneByNameCl(string $name_cl) Return the first ChildMailjetContactList filtered by the name_cl column
 * @method     ChildMailjetContactList findOneBySlugCl(string $slug_cl) Return the first ChildMailjetContactList filtered by the slug_cl column
 * @method     ChildMailjetContactList findOneByLocale(string $locale) Return the first ChildMailjetContactList filtered by the locale column
 * @method     ChildMailjetContactList findOneByDefaultList(boolean $default_list) Return the first ChildMailjetContactList filtered by the default_list column
 *
 * @method     array findByIdCl(int $id_cl) Return ChildMailjetContactList objects filtered by the id_cl column
 * @method     array findByNameCl(string $name_cl) Return ChildMailjetContactList objects filtered by the name_cl column
 * @method     array findBySlugCl(string $slug_cl) Return ChildMailjetContactList objects filtered by the slug_cl column
 * @method     array findByLocale(string $locale) Return ChildMailjetContactList objects filtered by the locale column
 * @method     array findByDefaultList(boolean $default_list) Return ChildMailjetContactList objects filtered by the default_list column
 *
 */
abstract class MailjetContactListQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Mailjet\Model\Base\MailjetContactListQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Mailjet\\Model\\MailjetContactList', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailjetContactListQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailjetContactListQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Mailjet\Model\MailjetContactListQuery) {
            return $criteria;
        }
        $query = new \Mailjet\Model\MailjetContactListQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMailjetContactList|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailjetContactListTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailjetContactListTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildMailjetContactList A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID_CL, NAME_CL, SLUG_CL, LOCALE, DEFAULT_LIST FROM mailjet_contact_list WHERE ID_CL = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildMailjetContactList();
            $obj->hydrate($row);
            MailjetContactListTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMailjetContactList|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_cl column
     *
     * Example usage:
     * <code>
     * $query->filterByIdCl(1234); // WHERE id_cl = 1234
     * $query->filterByIdCl(array(12, 34)); // WHERE id_cl IN (12, 34)
     * $query->filterByIdCl(array('min' => 12)); // WHERE id_cl > 12
     * </code>
     *
     * @param     mixed $idCl The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByIdCl($idCl = null, $comparison = null)
    {
        if (is_array($idCl)) {
            $useMinMax = false;
            if (isset($idCl['min'])) {
                $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $idCl['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idCl['max'])) {
                $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $idCl['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $idCl, $comparison);
    }

    /**
     * Filter the query on the name_cl column
     *
     * Example usage:
     * <code>
     * $query->filterByNameCl('fooValue');   // WHERE name_cl = 'fooValue'
     * $query->filterByNameCl('%fooValue%'); // WHERE name_cl LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameCl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByNameCl($nameCl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameCl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameCl)) {
                $nameCl = str_replace('*', '%', $nameCl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailjetContactListTableMap::NAME_CL, $nameCl, $comparison);
    }

    /**
     * Filter the query on the slug_cl column
     *
     * Example usage:
     * <code>
     * $query->filterBySlugCl('fooValue');   // WHERE slug_cl = 'fooValue'
     * $query->filterBySlugCl('%fooValue%'); // WHERE slug_cl LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slugCl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterBySlugCl($slugCl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slugCl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slugCl)) {
                $slugCl = str_replace('*', '%', $slugCl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailjetContactListTableMap::SLUG_CL, $slugCl, $comparison);
    }

    /**
     * Filter the query on the locale column
     *
     * Example usage:
     * <code>
     * $query->filterByLocale('fooValue');   // WHERE locale = 'fooValue'
     * $query->filterByLocale('%fooValue%'); // WHERE locale LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locale The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByLocale($locale = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locale)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $locale)) {
                $locale = str_replace('*', '%', $locale);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailjetContactListTableMap::LOCALE, $locale, $comparison);
    }

    /**
     * Filter the query on the default_list column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultList(true); // WHERE default_list = true
     * $query->filterByDefaultList('yes'); // WHERE default_list = true
     * </code>
     *
     * @param     boolean|string $defaultList The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function filterByDefaultList($defaultList = null, $comparison = null)
    {
        if (is_string($defaultList)) {
            $default_list = in_array(strtolower($defaultList), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailjetContactListTableMap::DEFAULT_LIST, $defaultList, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMailjetContactList $mailjetContactList Object to remove from the list of results
     *
     * @return ChildMailjetContactListQuery The current query, for fluid interface
     */
    public function prune($mailjetContactList = null)
    {
        if ($mailjetContactList) {
            $this->addUsingAlias(MailjetContactListTableMap::ID_CL, $mailjetContactList->getIdCl(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mailjet_contact_list table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetContactListTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MailjetContactListTableMap::clearInstancePool();
            MailjetContactListTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMailjetContactList or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMailjetContactList object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetContactListTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailjetContactListTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MailjetContactListTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailjetContactListTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MailjetContactListQuery
