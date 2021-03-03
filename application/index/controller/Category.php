<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Category Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\index\controller;

class Category extends Base
{
	public function index()
	{
		return $this->fetch();
	}
}
