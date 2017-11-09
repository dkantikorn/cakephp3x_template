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
 * @var     \<%= $entityClass %> $<%= $singularVar %>
 * @since   <%= date('Y-m-d H:i:s') %>
 * @license Pakgon.Ltd
 */
?>
<%
use Cake\Utility\Inflector;

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['decimal', 'biginteger', 'integer', 'float', 'smallinteger', 'tinyinteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
    })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<div class="container">
    <nav class="large-3 medium-4 columns" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?php echo __('Actions'); ?></li>
            <li><?php echo $this->Html->link(__('Edit <%= $singularHumanName %>'), ['action' => 'edit', <%= $pk %>]); ?> </li>
            <li><?php echo $this->Form->postLink(__('Delete <%= $singularHumanName %>'), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]); ?> </li>
            <li><?php echo $this->Html->link(__('List <%= $pluralHumanName %>'), ['action' => 'index']); ?> </li>
            <li><?php echo $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add']); ?> </li>
    <%
        $done = [];
        foreach ($associations as $type => $data) {
            foreach ($data as $alias => $details) {
                if ($details['controller'] !== $this->name && !in_array($details['controller'], $done)) {
    %>
            <li><?php echo $this->Html->link(__('List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index']); ?> </li>
            <li><?php echo $this->Html->link(__('New <%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add']); ?> </li>
    <%
                    $done[] = $details['controller'];
                }
            }
        }
    %>
        </ul>
    </nav>
</div>
<div class="container">
    <div class="<%= $pluralVar %> view large-9 medium-8 columns content">
        <h3><?php echo h($<%= $singularVar %>-><%= $displayField %>); ?></h3>
        <table class="vertical-table table">
    <% if ($groupedFields['string']) : %>
    <% foreach ($groupedFields['string'] as $field) : %>
    <% if (isset($associationFields[$field])) :
                $details = $associationFields[$field];
    %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize($details['property']) %>'); ?></th>
                <td><?php echo $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : ''; ?></td>
            </tr>
    <% else : %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize($field) %>'); ?></th>
                <td><?php echo h($<%= $singularVar %>-><%= $field %>); ?></td>
            </tr>
    <% endif; %>
    <% endforeach; %>
    <% endif; %>
    <% if ($associations['HasOne']) : %>
        <%- foreach ($associations['HasOne'] as $alias => $details) : %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>'); ?></th>
                <td><?php echo $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : ''; ?></td>
            </tr>
        <%- endforeach; %>
    <% endif; %>
    <% if ($groupedFields['number']) : %>
    <% foreach ($groupedFields['number'] as $field) : %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize($field) %>'); ?></th>
                <td><?php echo $this->Number->format($<%= $singularVar %>-><%= $field %>); ?></td>
            </tr>
    <% endforeach; %>
    <% endif; %>
    <% if ($groupedFields['date']) : %>
    <% foreach ($groupedFields['date'] as $field) : %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize($field) %>'); ?></th>
                <td><?php echo h($<%= $singularVar %>-><%= $field %>); ?></td>
            </tr>
    <% endforeach; %>
    <% endif; %>
    <% if ($groupedFields['boolean']) : %>
    <% foreach ($groupedFields['boolean'] as $field) : %>
            <tr>
                <th scope="row"><?php echo __('<%= Inflector::humanize($field) %>'); ?></th>
                <td><?php echo $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No'); ?></td>
            </tr>
    <% endforeach; %>
    <% endif; %>
        </table>
    <% if ($groupedFields['text']) : %>
    <% foreach ($groupedFields['text'] as $field) : %>
        <div class="row">
            <h4><?php echo __('<%= Inflector::humanize($field) %>'); ?></h4>
            <?php echo $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?>
        </div>
    <% endforeach; %>
    <% endif; %>
    <%
    $relations = $associations['HasMany'] + $associations['BelongsToMany'];
    foreach ($relations as $alias => $details):
        $otherSingularVar = Inflector::variable($alias);
        $otherPluralHumanName = Inflector::humanize(Inflector::underscore($details['controller']));
        %>
        <div class="related">
            <h4><?php echo __('Related <%= $otherPluralHumanName %>'); ?></h4>
            <?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
            <table cellpadding="0" cellspacing="0">
                <tr>
    <% foreach ($details['fields'] as $field): %>
                    <th scope="col"><?php echo __('<%= Inflector::humanize($field) %>'); ?></th>
    <% endforeach; %>
                    <th scope="col" class="actions"><?php echo __('Actions'); ?></th>
                </tr>
                <?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
                <tr>
                <%- foreach ($details['fields'] as $field): %>
                    <td><?php echo h($<%= $otherSingularVar %>-><%= $field %>); ?></td>
                <%- endforeach; %>
                <%- $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), ['controller' => '<%= $details['controller'] %>', 'action' => 'view', <%= $otherPk %>]); ?>
                        <?php echo $this->Html->link(__('Edit'), ['controller' => '<%= $details['controller'] %>', 'action' => 'edit', <%= $otherPk %>]); ?>
                        <?php echo $this->Form->postLink(__('Delete'), ['controller' => '<%= $details['controller'] %>', 'action' => 'delete', <%= $otherPk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $otherPk %>)]); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    <% endforeach; %>
    </div>
</div>
