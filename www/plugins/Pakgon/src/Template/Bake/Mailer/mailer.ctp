<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
%>
<?php
namespace <%= $namespace %>\Mailer;

use Cake\Mailer\Mailer;

/**
 *
 * <%= $name %> mailer.
 * @author  pakgon.Ltd
 * @since   <%= date('Y-m-d H:i:s') %>
 * @license Pakgon.Ltd
 */
class <%= $name %>Mailer extends Mailer {

    /**
     *
     * Mailer's name.
     * @author  pakgon.Ltd
     * @var string
     */
    static public $name = '<%= $name %>';
}
