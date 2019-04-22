<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Link as LinkService;

class Url extends BaseController
{

	public function jump($url='')
	{
		return (new LinkService())->jump($url);
	}
}