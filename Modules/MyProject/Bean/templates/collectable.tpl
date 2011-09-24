{% include 'header.tpl' %}

{% set Collectable = classes.get('Collectable') %}

{{ Collectable.printNamespace() }}

/**
 *
 * {{ Collectable }}
 * @author chente
 *
 */
interface {{ Collectable }}
{

	/**
	 *
	 * @return int
	 */
	public function getIndex();

	/**
	 * Convert to array
	 * @return array
	 */
	public function toArray();

}
