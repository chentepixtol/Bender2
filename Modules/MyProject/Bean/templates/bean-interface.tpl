{% include 'header.tpl' %}

namespace {{ classes.get('Collectable').getNamespace() }};

use {{ classes.get('Collectable').getNamespacedName() }};

/**
 *
 * Bean
 * @author chente
 *
 */
interface Bean extends {{ classes.get('Collectable').getName() }}
{

}
