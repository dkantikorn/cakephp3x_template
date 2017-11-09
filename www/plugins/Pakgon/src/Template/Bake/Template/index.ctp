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
 * The template for <%= $action . ' page of ' . $pluralVar . "Controller. This page show for short " . $pluralVar . " informations." %>
 * @author  pakgon.Ltd
 * @var     \<%= $namespace %>\View\AppView $this
 * @var     \<%= $entityClass %>[]|\Cake\Collection\CollectionInterface $<%= $pluralVar %>
 * @since   <%= date('Y-m-d H:i:s') %>
 * @license Pakgon.Ltd
 */
?>
<%
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return !in_array($schema->columnType($field), ['binary', 'text']);
    });

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}

if (!empty($indexColumns)) {
    $fields = $fields->take($indexColumns);
}

%>
<div class="container">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?php echo __('Actions'); ?></li>
            <li><?php echo $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add']); ?></li>
    <%
        $done = [];
        foreach ($associations as $type => $data):
            foreach ($data as $alias => $details):
                if (!empty($details['navLink']) && $details['controller'] !== $this->name && !in_array($details['controller'], $done)):
    %>
            <li><?php echo $this->Html->link(__('List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index']); ?></li>
            <li><?php echo $this->Html->link(__('New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add']); ?></li>
    <%
                    $done[] = $details['controller'];
                endif;
            endforeach;
        endforeach;
    %>
        </ul>
    </nav>
</div>
<div class="container">
    <div class="<%= $pluralVar %> index large-9 medium-8 columns content table-responsive">
        <h3><?php echo __('<%= $pluralHumanName %>'); ?></h3>
        <table cellpadding="0" cellspacing="0" class="table">
            <thead>
                <tr>
    <% foreach ($fields as $field): %>
                    <th scope="col"><?php echo $this->Paginator->sort('<%= $field %>'); ?></th>
    <% endforeach; %>
                    <th scope="col" class="actions"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
                <tr>
    <%        foreach ($fields as $field) {
                $isKey = false;
                if (!empty($associations['BelongsTo'])) {
                    foreach ($associations['BelongsTo'] as $alias => $details) {
                        if ($field === $details['foreignKey']) {
                            $isKey = true;
    %>
                    <td><?php echo $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
    <%
                            break;
                        }
                    }
                }
                if ($isKey !== true) {
                    if (!in_array($schema->columnType($field), ['integer', 'float', 'decimal', 'biginteger', 'smallinteger', 'tinyinteger'])) {
    %>
                    <td><?php echo h($<%= $singularVar %>-><%= $field %>); ?></td>
    <%
                    } else {
    %>
                    <td><?php echo $this->Number->format($<%= $singularVar %>-><%= $field %>); ?></td>
    <%
                    }
                }
            }

            $pk = '$' . $singularVar . '->' . $primaryKey[0];
    %>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), ['action' => 'view', <%= $pk %>]); ?>
                        <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', <%= $pk %>]); ?>
                        <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?php echo $this->Paginator->first('<< ' . __('first')); ?>
                <?php echo $this->Paginator->prev('< ' . __('previous')); ?>
                <?php echo $this->Paginator->numbers(); ?>
                <?php echo $this->Paginator->next(__('next') . ' >'); ?>
                <?php echo $this->Paginator->last(__('last') . ' >>'); ?>
            </ul>
            <p><?php echo $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]); ?></p>
        </div>
    </div>
</div>
