<?php
/**
 * Noovias_Extensions_Model_Config_ExecuteExtensionss
 *  *
 * NOTICE OF LICENSE
 *
 * Noovias_Extensions is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Extensions is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Extensions. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Extensions to newer
 * versions in the future. If you wish to customize Noovias_Extensions for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link           http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author         noovias.com - Core Team <info@noovias.com>
 *
 * @todo this class and parents should be refactored to not use Varien_Object as parent
 * @todo the Config should already contain all necessary Information e.g. all e-mail addresses the mail has to be send to
 * @todo Variable to and copy_to should be an array of recipients. each recipient is an array containing name and email address
 * @info the intent of this config object should be to contain all needed information to send an email.
 * @info it's OK to load the templates html within the EmailSendService
 */
class Noovias_Extensions_Model_Config_Email_Abstract
    extends Noovias_Extensions_Model_Config_Abstract
    implements Noovias_Extensions_Model_Config_Email_Interface
{
    const CONFIG_KEY_ENABLED  = 'enabled';
    const CONFIG_KEY_TO       = 'to';
    const CONFIG_KEY_COPY_TO  = 'copy_to';
    const CONFIG_KEY_FROM     = 'from';
    const CONFIG_KEY_TEMPLATE = 'template';
    const CONFIG_KEY_LOGFILENAME = 'logfilename';

    const LOGFILE_NAME_DEFAULT = 'noovias_extensions_email.log';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->getData(self::CONFIG_KEY_ENABLED) != 0)
        {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->getData(self::CONFIG_KEY_TO);
    }

    /**
     * @return string
     */
    public function getCopyTo()
    {
        $copyTo = $this->getData(self::CONFIG_KEY_COPY_TO);
        if($copyTo === null)
            $copyTo = '';
        return $copyTo;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->getData(self::CONFIG_KEY_FROM);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->getData(self::CONFIG_KEY_TEMPLATE);
    }

    /**
     * Log file name, e.g. 'mail.log'
     *
     * @return string
     */
    public function getLogFileName()
    {
        return $this->getData(self::CONFIG_KEY_LOGFILENAME);
    }


}