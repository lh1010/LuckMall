<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * 签到 Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\IntegralRepository;

class QiandaoRepository
{
    /**
     * 签到
     * @param int $user_id
     */
    public function qiandao($user_id)
    {   
        try {
          $today_date = date('Y-m-d'); $last_date = date('Y-m-d', strtotime("-1 day"));
          $user_qiandao_logs = Db::name('user_qiandao_log')->where('user_id', $user_id)->whereIn('date', [$today_date, $last_date])->select();
          $continuous_count = 1;
          if (!empty($user_qiandao_logs)) {
            foreach ($user_qiandao_logs as $key => $value) {
              if ($value['date'] == $today_date) return arrayFailed('今日已签到');
              if ($value['date'] == $last_date) $continuous_count = $value['continuous_count'] + 1;
            }
          }
          Db::name('user_qiandao_log')->insert(['user_id' => $user_id, 'date' => $today_date, 'continuous_count' => $continuous_count]);
          Db::name('user')->where('id', $user_id)->setInc('integral', Config('common.qiandao_integral'));
          $params = ['user_id' => $user_id, 'integral' => Config('common.qiandao_integral'), 'description' => '签到赠送积分'];
          app(IntegralRepository::class)->createLog($params);
          Db::commit();
          return arraySuccess();
        } catch (\Throwable $th) {
          Db::rollback();
          return arrayFailed();
        }
    }

    /**
     * 获取签到数据
     * @param int $user_id
     */
    public function getQiandaoData($user_id)
    {
        $year = date('Y'); $month = date('m'); $day = date('d'); $week = date('w');
        $today_date = date('Y-m-d'); $last_date = date('Y-m-d', strtotime("-1 day"));
        // 本月天数
        $this_month_days = date('t', strtotime($year . '-' . $month . '-01'));
        // 上月天数
        $last_month = mktime(0, 0, 0, date($month) - 1, 1, date($year));
        $last_month_days = date('t', strtotime(date('Y', $last_month) . '-' . date('m', $last_month) . '-01'));
        // 本月第一天星期几
        $this_month_first_day_week = date("w", strtotime(Date("Y-m-1")));
        
        $data = [];
        $data['year_month'] = $year . '年' . $month . '月';
        // 星期
        $data['weeks'] = [
            ['value' => '日', 'on' =>false],
            ['value' => '一', 'on' => false],
            ['value' => '二', 'on' => false],
            ['value' => '三', 'on' => false],
            ['value' => '四', 'on' => false],
            ['value' => '五', 'on' => false],
            ['value' => '六', 'on' => false]
        ];
        foreach ($data['weeks'] as $key => $value) {
            if ($key == $week) $data['weeks'][$key]['on'] = true;
        }
        // 天
        $data['days'] = [];
        // 填充第一行，根据第一天的星期数，填充空白
        for ($i = 0; $i < $this_month_first_day_week; $i++) {
          $data['days'][] = ['value' => '', 'date' => '', 'on' => false];
        }
        // 依次的循环日期数
        for ($j = 1; $j <= $this_month_days; $j++) {
          if ($j == $day) {
            $data['days'][] = ['value' => $j, 'date' => $year . '-' . $month . '-' . ($j < 10 ? '0' . $j : $j), 'on' => true];
          } else {
            $data['days'][] = ['value' => $j, 'date' => $year . '-' . $month . '-' . ($j < 10 ? '0' . $j : $j), 'on' => false];
          }
          $i++;
        }
        // 填充最后一行
        while ($i % 7 != 0) {
            $i++;
            $data['days'][] = ['value' => '', 'date' => '', 'on' => false];
        }
        
        // 本月签到日期
        $user_qiandao_log_dates = Db::name('user_qiandao_log')->where('user_id', $user_id)->where('date', 'like', "%" . $year . '-' . $month . "%")->column('date');

        // 标记已签到日期
        foreach ($data['days'] as $key => $value) {
            $data['days'][$key]['already'] = in_array($value['date'], $user_qiandao_log_dates) ? true : false;
        }
        $data['today_already'] =  false; 
        if (in_array($today_date, $user_qiandao_log_dates)) {
            $data['today_already'] =  true;
            
        }
        
        // 获取连续签到次数
        $data['continuous_count'] =  0;
        $user_qiandao_logs = Db::name('user_qiandao_log')->where('user_id', $user_id)->whereIn('date', [$today_date, $last_date])->column('continuous_count', 'date');
        if (!empty($user_qiandao_logs)) {
          foreach ($user_qiandao_logs as $key => $value) {
            if ($key == $last_date) $data['continuous_count'] =  $value;
            if ($key == $today_date) {
              $data['continuous_count'] =  $value;
              break;
            } 
          }
        }

        return $data;
    }
}