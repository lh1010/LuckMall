<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Image Repository Admin
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository\admin;

class ImageRepository
{
	/**
	 * 设置产品相关图片
	 * @param string $path
	 * @param int $product_id
	 * @return array
	 */
	public function setProductImage($path = '', $product_id = 0)
	{
		try {
			$array = explode('/', $path);
			$basename = $array[count($array) - 1];
			$array = explode('/images/', $path);
			$path = ('./images/' . $array[count($array) - 1]);
			$new_dir_path = './' . Config('image.imgextra_dir') . '/' . str_replace($basename, '', $array[count($array) - 1]);
			$array = explode('.', $basename);
			$suffix = '.' . $array[count($array) - 1];
			$new_file_path = $new_dir_path . $basename . '_430x430' . $suffix;
			// 430x430
			if (!file_exists($new_file_path) && file_exists($path)) {
				$image = \think\Image::open($path);
				$width = $image->width(); $height = $image->height();
				$new_height = bcmul(bcdiv(430, $width, 4), $height);
				if ($width > 430) {
					if (!file_exists($new_dir_path)) @mkdir($new_dir_path, 0777, true);
					$image->thumb(430, $new_height, \think\Image::THUMB_SCALING)->save($new_file_path);
				}
			}
			return arraySuccess();
		} catch (\Throwable $th) {
			return arrayFailed('处理图片失败');
		}
	}
}