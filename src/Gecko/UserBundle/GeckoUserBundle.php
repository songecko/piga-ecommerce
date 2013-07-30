<?php

namespace Gecko\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeckoUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
