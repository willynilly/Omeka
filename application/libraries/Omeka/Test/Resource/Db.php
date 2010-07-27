<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2009-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

/**
 * Get the install form to get current default values.
 */
require_once APP_DIR . '/forms/Install.php';

/**
 * Set up the database test environment by wiping and resetting the database to
 * a recently-installed state.
 *
 * @package Omeka
 * @copyright Center for History and New Media, 2007-2010
 */
class Omeka_Test_Resource_Db extends Zend_Application_Resource_Db
{
    const SUPER_USERNAME = 'foobar123';
    const SUPER_PASSWORD = 'foobar123';
    const SUPER_EMAIL    = 'foobar@example.com';
    
    const DEFAULT_USER_ID  = 1;
    
    const DEFAULT_SITE_TITLE    = 'Automated Test Installation';
    const DEFAULT_AUTHOR        = 'CHNM';
    const DEFAULT_COPYRIGHT     = '2010';
    const DEFAULT_DESCRIPTION   = 'This database will be reset after every test run.  DO NOT USE WITH PRODUCTION SITES';
    
    private $_runInstaller = true;
        
    /**
     * Load and initialize the database.
     *
     * @return Omeka_Db
     */
    public function init()
    {   
        $omekaDb = $this->getDb();
        if ($this->_runInstaller) {
            $this->_dropTables($this->getDbAdapter());
            $installer = new Installer_Test($omekaDb);
            $installer->install();
        }
        return $omekaDb;
    }
    
    /**
     * @return Omeka_Db
     */
    public function getDb()
    {
        $this->getBootstrap()->bootstrap('Config');
        $this->useTestConfig();
        return $this->_getOmekaDb();
    }
        
    public function useTestConfig()
    {
        $this->setAdapter('Mysqli');
        $this->setParams(Zend_Registry::get('test_config')->db->toArray());
    }
        
    /**
     * Set the flag that indicates whether or not to run the installer during 
     * init().
     * 
     * @param boolean $flag
     */
    public function setInstall($flag)
    {
        $this->_runInstaller = (boolean)$flag;
    }
    
    /**
     * Create a DB instance with the omeka_ prefix.
     *
     * @return Omeka_Db
     */
    private function _getOmekaDb()
    {
        $omekaDb = new Omeka_Db($this->getDbAdapter(), 'omeka_');
        return $omekaDb;
    }
        
    /**
     * Drop all the tables in the test database.
     *
     * @param Zend_Db_Adapter_Abstract $dbAdapter
     * @return void
     */
    private function _dropTables(Zend_Db_Adapter_Abstract $dbAdapter)
    {
        $dbHelper = new Omeka_Test_DbHelper($dbAdapter);
        $dbHelper->dropTables();
    }    
}