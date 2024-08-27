<?php

namespace Leedch\Router;

use Leedch\Router\Router;

/**
 * I'm not such a fan of controllers, to me they are a risk, because they act
 * dynamically on user inputs. Instead of making sure your controller cannot
 * do something bad, I prefer to define allowed routes.
 *
 * @author leed
 */
interface RouterInterface
{
    public function route(Router $r);
}
