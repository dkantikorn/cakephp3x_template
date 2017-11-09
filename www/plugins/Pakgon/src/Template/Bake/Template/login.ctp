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
/**
 * 
 * The template for <%= $action . ' page of ' . $pluralVar . "Controller." %>
 * @author  pakgon.Ltd
 * @var     \<%= $namespace %>\View\AppView $this
 * @since   <%= date('Y-m-d H:i:s') %>
 * @license Pakgon.Ltd
 */
?>
<div class="container">
    <div class="<%= $pluralVar %> form">
    <?php echo $this->Flash->render('auth'); ?>
        <?php echo $this->Form->create(); ?>
        <fieldset>
            <legend><?php echo __('Please enter your username and password'); ?></legend>
            <?php echo $this->Form->control('username'); ?>
            <?php echo $this->Form->control('password'); ?>
        </fieldset>
        <?php echo $this->Form->button(__('Login')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
