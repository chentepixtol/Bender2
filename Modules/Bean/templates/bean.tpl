{% include 'header.tpl' %}

class {{ table.getObject().toUpperCamelCase() }}
{
	const TABLENAME = '{{ table.getName() }}';
}