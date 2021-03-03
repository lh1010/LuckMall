<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Image Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;

class ImageRepository
{
    /**
     * 设置产品图片
     * 设置默认图、返回缩略图
     * @param string $image
     * @param int $thumbnail if return thumbnail image
     */
    public function setProductImage($image, $thumbnail = 1)
    {
        if (empty($image)) return $this->imageView(Config('image.product.image_default'));
        if ($thumbnail != 1) return $this->imageView($image);
        $array = explode('/images/', $image);
        $path = ('./images/' . $array[count($array) - 1]);
        $basename = basename($path);
        $new_dir_path = '/' . Config('image.imgextra_dir') . '/' . str_replace($basename, '', $array[count($array) - 1]);
        $array = explode('.', $basename);
        $suffix = '.' . $array[count($array) - 1];
        $new_file_path =  $new_dir_path . $basename . '_430x430' . $suffix;
        if (file_exists('.' . $new_file_path)) $image = Config('system.app_url') . $new_file_path;
        $image = $this->imageView($image);
        return $image;
    }

    public function setProductContentImage($content)
    {
        $preg = "/<img(.*?)src=\"(.*?)\"(.*?)>/is";
        if (preg_match_all($preg, $content, $matches)) {
            $content = preg_replace($preg, '<img src="' . Config('app.app_url') . '$2" />', $content);
        }
        return $content;
    }

    /**
     * 处理图片路径
     * @param string $image
     */
    public function imageView($image)
    {
        if (strpos($image, 'http') !== false || empty($image)) return $image;
        return Config('app.app_url') . $image;
    }
}