<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

/**
 * Interface for creating different installers for Omeka.
 *
 * @package Omeka
 * @copyright Center for History and New Media, 2007-2010
 */
interface InstallerInterface
{    
    public function __construct(Omeka_Db $db);
    public function install();
    public function isInstalled();
}
