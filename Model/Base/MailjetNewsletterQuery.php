<?php

namespace Mailjet\Model\Base;

use \Exception;
use \PDO;
use Mailjet\Model\MailjetNewsletter as ChildMailjetNewsletter;
use Mailjet\Model\MailjetNewsletterQuery as ChildMailjetNewsletterQuery;
use Mailjet\Model\Map\MailjetNewsletterTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mailjet_newsletter' table.
 *
 *
 *
 * @method     ChildMailjetNewsletterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMailjetNewsletterQuery orderByMailjetId($order = Criteria::ASC) Order by the mailjet_id column
 * @method     ChildMailjetNewsletterQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildMailjetNewsletterQuery orderByRelationId($order = Criteria::ASC) Order by the relation_id column
 *
 * @method     ChildMailjetNewsletterQuery groupById() Group by the id column
 * @method     ChildMailjetNewsletterQuery groupByMailjetId() Group by the mailjet_id column
 * @method     ChildMailjetNewsletterQuery groupByEmail() Group by the email column
 * @method     ChildMailjetNewsletterQuery groupByRelationId() Group by the relation_id column
 *
 * @method     ChildMailjetNewsletterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailjetNewsletterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailjetNewsletterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailjetNewsletter findOne(ConnectionInterface $con = null) Return the first ChildMailjetNewsletter matching the query
 * @method     ChildMailjetNewsletter findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailjetNewsletter matching the query, or a new ChildMailjetNewsletter object populated from the query conditions when no match is found
 *
 * @method     ChildMailjetNewsletter findOneById(int $id) Return the first ChildMailjetNewsletter filtered by the id column
 * @method     ChildMailjetNewsletter findOneByMailjetId(string $mailjet_id) Return the first ChildMailjetNewsletter filtered by the mailjet_id column
 * @method     ChildMailjetNewsletter findOneByEmail(string $email) Return the first ChildMailjetNewsletter filtered by the email column
 * @method     ChildMailjetNewsletter findOneByRelationId(int $relation_id) Return the first ChildMailjetNewsletter filtered by the relation_id column
 *
 * @method     array findById(int $id) Return ChildMailjetNewsletter objects filtered by the id column
 * @method     array findByMailjetId(string $mailjet_id) Return ChildMailjetNewsletter objects filtered by the mailjet_id column
 * @method     array findByEmail(string $email) Return ChildMailjetNewsletter objects filtered by the email column
 * @method     array findByRelationId(int $relation_id) Return ChildMailjetNewsletter objects filtered by the relation_id column
 *
 */
abstract class MailjetNewsletterQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Mailjet\Model\Base\MailjetNewsletterQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Mailjet\\Model\\MailjetNewsletter', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailjetNewsletterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailjetNewsletterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Mailjet\Model\MailjetNewsletterQuery) {
            return $criteria;
        }
        $query = new \Mailjet\Model\MailjetNewsletterQuery();
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
     * @return ChildMailjetNewsletter|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MailjetNewsletterTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailjetNewsletterTableMap::DATABASE_NAME);
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
     * @return   ChildMailjetNewsletter A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, MAILJET_ID, EMAIL, RELATION_ID FROM mailjet_newsletter WHERE ID = :p0';
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
            $obj = new ChildMailjetNewsletter();
            $obj->hydrate($row);
            MailjetNewsletterTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMailjetNewsletter|array|mixed the result, formatted by the current formatter
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
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailjetNewsletterTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailjetNewsletterTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailjetNewsletterTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailjetNewsletterTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailjetNewsletterTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the mailjet_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMailjetId('fooValue');   // WHERE mailjet_id = 'fooValue'
     * $query->filterByMailjetId('%fooValue%'); // WHERE mailjet_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mailjetId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterByMailjetId($mailjetId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mailjetId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mailjetId)) {
                $mailjetId = str_replace('*', '%', $mailjetId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailjetNewsletterTableMap::MAILJET_ID, $mailjetId, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MailjetNewsletterTableMap::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the relation_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRelationId(1234); // WHERE relation_id = 1234
     * $query->filterByRelationId(array(12, 34)); // WHERE relation_id IN (12, 34)
     * $query->filterByRelationId(array('min' => 12)); // WHERE relation_id > 12
     * </code>
     *
     * @param     mixed $relationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function filterByRelationId($relationId = null, $comparison = null)
    {
        if (is_array($relationId)) {
            $useMinMax = false;
            if (isset($relationId['min'])) {
                $this->addUsingAlias(MailjetNewsletterTableMap::RELATION_ID, $relationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($relationId['max'])) {
                $this->addUsingAlias(MailjetNewsletterTableMap::RELATION_ID, $relationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailjetNewsletterTableMap::RELATION_ID, $relationId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMailjetNewsletter $mailjetNewsletter Object to remove from the list of results
     *
     * @return ChildMailjetNewsletterQuery The current query, for fluid interface
     */
    public function prune($mailjetNewsletter = null)
    {
        if ($mailjetNewsletter) {
            $this->addUsingAlias(MailjetNewsletterTableMap::ID, $mailjetNewsletter->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mailjet_newsletter table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetNewsletterTableMap::DATABASE_NAME);
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
            MailjetNewsletterTableMap::clearInstancePool();
            MailjetNewsletterTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildMailjetNewsletter or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildMailjetNewsletter object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetNewsletterTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailjetNewsletterTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        MailjetNewsletterTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailjetNewsletterTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // MailjetNewsletterQuery
