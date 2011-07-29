{% include 'header.tpl' %}

namespace Application\Base;

/**
 *
 * {{ classes.get('Collectable').getName() }}
 * @author chente
 *
 */
interface {{ classes.get('Collectable').getName() }}
{

	/**
	 *
	 * @return int
	 */
	public function getIndex();

}
