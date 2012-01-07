{% set slug = Controller.getName().toSlug('newString').replace('-controller','') %}
{block name=main}
    <table class="zebra-striped bordered-table">
        <caption><h3>{{ Bean }}</h3></caption>
        <thead>
            <tr>
{% for field in fullFields %}
                <th>{{ field.getName().toUpperCamelCase() }}</th>
{% endfor %}
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {foreach ${{ collection }} as ${{ bean }}}
                <tr>
{% for field in fullFields %}
                    <td>{${{ bean}}->{{ field.getter() }}()}</td>
{% endfor %}
                    {assign var=params value=['controller' => '{{slug}}', 'action' => 'edit', 'id' => ${{ bean }}->{{table.getPrimaryKey().getter()}}()]}
                    <td><a href="{$this->url('crud', $params)}" class="btn">Editar</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/block}