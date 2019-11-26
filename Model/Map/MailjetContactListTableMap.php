<?php

namespace Mailjet\Model\Map;

use Mailjet\Model\MailjetContactList;
use Mailjet\Model\MailjetContactListQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'mailjet_contact_list' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MailjetContactListTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Mailjet.Model.Map.MailjetContactListTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'mailjet_contact_list';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Mailjet\\Model\\MailjetContactList';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Mailjet.Model.MailjetContactList';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the ID_CL field
     */
    const ID_CL = 'mailjet_contact_list.ID_CL';

    /**
     * the column name for the NAME_CL field
     */
    const NAME_CL = 'mailjet_contact_list.NAME_CL';

    /**
     * the column name for the SLUG_CL field
     */
    const SLUG_CL = 'mailjet_contact_list.SLUG_CL';

    /**
     * the column name for the LOCALE field
     */
    const LOCALE = 'mailjet_contact_list.LOCALE';

    /**
     * the column name for the DEFAULT_LIST field
     */
    const DEFAULT_LIST = 'mailjet_contact_list.DEFAULT_LIST';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdCl', 'NameCl', 'SlugCl', 'Locale', 'DefaultList', ),
        self::TYPE_STUDLYPHPNAME => array('idCl', 'nameCl', 'slugCl', 'locale', 'defaultList', ),
        self::TYPE_COLNAME       => array(MailjetContactListTableMap::ID_CL, MailjetContactListTableMap::NAME_CL, MailjetContactListTableMap::SLUG_CL, MailjetContactListTableMap::LOCALE, MailjetContactListTableMap::DEFAULT_LIST, ),
        self::TYPE_RAW_COLNAME   => array('ID_CL', 'NAME_CL', 'SLUG_CL', 'LOCALE', 'DEFAULT_LIST', ),
        self::TYPE_FIELDNAME     => array('id_cl', 'name_cl', 'slug_cl', 'locale', 'default_list', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdCl' => 0, 'NameCl' => 1, 'SlugCl' => 2, 'Locale' => 3, 'DefaultList' => 4, ),
        self::TYPE_STUDLYPHPNAME => array('idCl' => 0, 'nameCl' => 1, 'slugCl' => 2, 'locale' => 3, 'defaultList' => 4, ),
        self::TYPE_COLNAME       => array(MailjetContactListTableMap::ID_CL => 0, MailjetContactListTableMap::NAME_CL => 1, MailjetContactListTableMap::SLUG_CL => 2, MailjetContactListTableMap::LOCALE => 3, MailjetContactListTableMap::DEFAULT_LIST => 4, ),
        self::TYPE_RAW_COLNAME   => array('ID_CL' => 0, 'NAME_CL' => 1, 'SLUG_CL' => 2, 'LOCALE' => 3, 'DEFAULT_LIST' => 4, ),
        self::TYPE_FIELDNAME     => array('id_cl' => 0, 'name_cl' => 1, 'slug_cl' => 2, 'locale' => 3, 'default_list' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('mailjet_contact_list');
        $this->setPhpName('MailjetContactList');
        $this->setClassName('\\Mailjet\\Model\\MailjetContactList');
        $this->setPackage('Mailjet.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID_CL', 'IdCl', 'INTEGER', true, null, null);
        $this->addColumn('NAME_CL', 'NameCl', 'VARCHAR', true, 255, null);
        $this->addColumn('SLUG_CL', 'SlugCl', 'VARCHAR', true, 255, null);
        $this->addColumn('LOCALE', 'Locale', 'VARCHAR', true, 255, null);
        $this->addColumn('DEFAULT_LIST', 'DefaultList', 'BOOLEAN', true, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCl', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCl', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('IdCl', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? MailjetContactListTableMap::CLASS_DEFAULT : MailjetContactListTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (MailjetContactList object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MailjetContactListTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MailjetContactListTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MailjetContactListTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MailjetContactListTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MailjetContactListTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MailjetContactListTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MailjetContactListTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MailjetContactListTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MailjetContactListTableMap::ID_CL);
            $criteria->addSelectColumn(MailjetContactListTableMap::NAME_CL);
            $criteria->addSelectColumn(MailjetContactListTableMap::SLUG_CL);
            $criteria->addSelectColumn(MailjetContactListTableMap::LOCALE);
            $criteria->addSelectColumn(MailjetContactListTableMap::DEFAULT_LIST);
        } else {
            $criteria->addSelectColumn($alias . '.ID_CL');
            $criteria->addSelectColumn($alias . '.NAME_CL');
            $criteria->addSelectColumn($alias . '.SLUG_CL');
            $criteria->addSelectColumn($alias . '.LOCALE');
            $criteria->addSelectColumn($alias . '.DEFAULT_LIST');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(MailjetContactListTableMap::DATABASE_NAME)->getTable(MailjetContactListTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(MailjetContactListTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(MailjetContactListTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new MailjetContactListTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a MailjetContactList or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MailjetContactList object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetContactListTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Mailjet\Model\MailjetContactList) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MailjetContactListTableMap::DATABASE_NAME);
            $criteria->add(MailjetContactListTableMap::ID_CL, (array) $values, Criteria::IN);
        }

        $query = MailjetContactListQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { MailjetContactListTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { MailjetContactListTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the mailjet_contact_list table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MailjetContactListQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MailjetContactList or Criteria object.
     *
     * @param mixed               $criteria Criteria or MailjetContactList object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailjetContactListTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MailjetContactList object
        }

        if ($criteria->containsKey(MailjetContactListTableMap::ID_CL) && $criteria->keyContainsValue(MailjetContactListTableMap::ID_CL) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MailjetContactListTableMap::ID_CL.')');
        }


        // Set the correct dbName
        $query = MailjetContactListQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // MailjetContactListTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MailjetContactListTableMap::buildTableMap();
