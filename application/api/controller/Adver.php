<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Api Adver Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\api\controller;

use think\Request;
use app\repository\AdverRepository;

class Adver extends Base
{
    public function getAdver(Request $request)
    {
        $adver = app(AdverRepository::class)->getAdver($request->adver_id);
        return jsonSuccess($adver);
    }

	// public function getAdver(Request $request)
	// {
    //     $str = '';
    //     $adver = app(AdverRepository::class)->getAdver($request->adver_id);
    //     if (!empty($adver) && isset($adver['values'])) {
    //         $values = $adver['values'];
    //         if (count($values) > 1) {
    //             $str .= '<div class="swiper-container">';
    //             $str .= '<div class="swiper-wrapper">';
    //             foreach ($values as $key => $value) {
    //                 if (empty($value['link'])) {
    //                     $str .= '<div class="swiper-slide"><img src="'.$value['image'].'"></div>';
    //                 } else {
    //                     $str .= '<div class="swiper-slide"><a href="'.$value['link'].'" target="'.$value['target'].'"><img src="'.$value['image'].'"></a></div>';
    //                 }
    //             }
    //             $str .= '</div>';
    //             $str .= '<div class="swiper-pagination"></div>';
    //             $str .= '</div>';
    //         }
    //         if (count($values) == 1) {
    //             if (empty($values[0]['link'])) {
    //                 $str .= '<img src="'.$values[0]['image'].'" />';
    //             } else {
    //                 $str .= '<a href="'.$values[0]['link'].'" target="'.$values[0]['target'].'"><img src="'.$values[0]['image'].'" /></a>';
    //             }
    //         }
    //     }
    //     exit('document.write(\''.$str.'\');');
	// }
}
