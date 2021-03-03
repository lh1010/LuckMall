<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Comment Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\OrderRepository;
use app\repository\UserRepository;

class CommentRepository
{
    /**
     * Create Product Comment
     * @param int $params['product_id']
     * @param int $params['order_id']
     * @param int $params['user_id']
     * @param int $params['star']
     * @param string $params['content']
     * @param array $images
     * @return array
     */
    public function createProduct($params = [], $images = [])
    {
        $data = [];
        if (isset($params['product_id'])) $data['product_id'] = $params['product_id'];
        if (isset($params['order_id'])) $data['order_id'] = $params['order_id'];
        if (isset($params['user_id'])) $data['user_id'] = $params['user_id'];
        if (isset($params['star'])) $data['star'] = $params['star'];
        if (isset($params['content'])) $data['content'] = $params['content'];
        if (isset($params['anonymity'])) $data['anonymity'] = $params['anonymity'];
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['ip'] = Request()->ip();
        $comment_id = Db::name('product_comment')->insertGetId($data);
        if (!empty($images)) {
            $data_image = [];
            foreach ($images as $key => $value) {
                $data_image[$key]['image'] = $value;
                $data_image[$key]['comment_id'] = $comment_id;
            }
            Db::name('product_comment_image')->insertAll($data_image);
        }
        return arraySuccess();
    }

    /**
     * Create Shop Comment
     * @param int $params['shop_id']
     * @param int $params['order_id']
     * @param int $params['user_id']
     * @param int $params['star1']
     * @param int $params['star2']
     * @param int $params['star3']
     * @return array
     */
    public function createShop($params = [])
    {
        $data = [];
        if (isset($params['shop_id'])) $data['shop_id'] = $params['shop_id'];
        if (isset($params['order_id'])) $data['order_id'] = $params['order_id'];
        if (isset($params['user_id'])) $data['user_id'] = $params['user_id'];
        if (isset($params['star1'])) $data['star1'] = $params['star1'];
        if (isset($params['star2'])) $data['star2'] = $params['star2'];
        if (isset($params['star3'])) $data['star3'] = $params['star3'];
        if (isset($params['anonymity'])) $data['anonymity'] = $params['anonymity'];
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['ip'] = Request()->ip();
        Db::name('shop_comment')->insert($data);
        return arraySuccess();
    }

    /**
     * Get Shop Comment
     * @param int $params['order_id']
     * @param int $params['user_id']
     * @param int $params['shop_id']
     * @param int $params['status']
     * @return array
     */
    public function getShopComment($params = [])
    {
        $query = Db::name('shop_comment');
        if (isset($params['order_id'])) $query->where('order_id', $params['order_id']);
        if (isset($params['user_id'])) $query->where('user_id', $params['user_id']);
        if (isset($params['shop_id'])) $query->where('shop_id', $params['shop_id']);
        if (isset($params['status'])) $query->where('status', $params['status']);
        return $query->find();
    }

    /**
     * Get Product Comment
     * @param int $params['order_id']
     * @param int $params['user_id']
     * @param int $params['product_id']
     * @param int $params['status']
     * @return array
     */
    public function getProductComment($params = [])
    {
        $query = Db::name('product_comment');
        if (isset($params['order_id'])) $query->where('order_id', $params['order_id']);
        if (isset($params['user_id'])) $query->where('user_id', $params['user_id']);
        if (isset($params['product_id'])) $query->where('product_id', $params['product_id']);
        if (isset($params['status'])) $query->where('status', $params['status']);
        return $query->find();
    }

    /**
     * Check Comment End
     * @param int $order_id
     * @param int $user_id
     * @return Boolean
     */
    public function checkCommentEnd($order_id, $user_id)
    {
        $order = app(OrderRepository::class)->getOrder(['order_id' => $order_id]);
        if (empty($order)) return true;
        if ($order['is_comment'] == 1) return true;
        if (!Db::name('shop_comment')->where('shop_id', $order['shop_id'])->where('order_id', $order_id)->where('user_id', $user_id)->find()) {
            return false;
        }
        foreach ($order['snaps'] as $key => $value) {
            $product_ids[] = $value['product_id'];
        }
        $product_comment_count = Db::name('product_comment')->whereIn('product_id', $product_ids)->where('order_id', $order_id)->where('user_id', $user_id)->count();
        if ($product_comment_count < count($product_ids)) return false;
        return true;
    }

    /**
     * 获取店铺评分
     * 描述 服务 发货
     * @param int $shop_id
     * @return array
     */
    public function getShopScore($shop_id)
    {
        $data = [];
        $query = Db::name('shop_comment');
        $query->where('shop_id', $shop_id);
        $shop_comments = $query->select();
        $data['star1_score'] = Config('shop.full_score');
        $data['star2_score'] = Config('shop.full_score');
        $data['star3_score'] = Config('shop.full_score');
        if (!empty($shop_comments)) {
            $count = count($shop_comments);
            $star1 = 0;
            $star2 = 0;
            $star3 = 0;
            foreach ($shop_comments as $key => $value) {
                $star1 = bcadd($star1, $value['star1'], 2);
                $star2 = bcadd($star2, $value['star2'], 2);
                $star3 = bcadd($star3, $value['star3'], 2);
            }
            $data['star1_score'] = bcdiv($star1, $count, 2);
            $data['star2_score'] = bcdiv($star2, $count, 2);
            $data['star3_score'] = bcdiv($star3, $count, 2);
        }
        // 综合评分
        $data['synthesize_score'] = bcdiv($data['star1_score'] + $data['star2_score'] + $data['star3_score'], 3, 2);;
        return $data;
    }

    /**
     * 获取产品评分
     * @param int $product_id
     * @return array
     * @return $data['star_percentage'] 百分比
     */
    public function getProductScore($product_id)
    {
        $data = [];
        $data['star_percentage'] = 100;
        $query = Db::name('product_comment');
        $query->where('product_id', $product_id);
        $product_comments = $query->select();
        if (!empty($product_comments)) {
            $star_all = 0;
            $star_percentage = 0;
            foreach ($product_comments as $key => $value) {
                $star_all = bcadd($star_all, 100);
                $star_percentage = bcadd($star_percentage, bcmul($value['star'], 20));
            }
            $data['star_percentage'] = bcmul(bcdiv($star_percentage, $star_all, 2), 100);
        }
        return $data;
    }

    /**
     * Get Comment Products
     * @param int $user_id
     * @param int $status 0|1
     * @return array
     */
    public function getCommentProducts($user_id, $status = '')
    {
        $order_ids = Db::name('order')->where('user_id', $user_id)->where('status', 40)->column('id');
        $query = Db::name('order_snap')->alias('order_snap');
        $query->field('order_snap.*, product_comment.id as product_comment_id, product_comment.create_time as product_comment_create_time');
        if ($status == 1) {
            $query->join('product_comment', 'product_comment.order_id = order_snap.order_id and product_comment.product_id = order_snap.product_id');
        } else {
            $query->leftJoin('product_comment', 'product_comment.order_id = order_snap.order_id and product_comment.product_id = order_snap.product_id');
        }
        $query->whereIn('order_snap.order_id', $order_ids);
        $query->order('order_snap.id desc');
        $products = $query->paginate(15, false, ['query' => ['status' => $status]]);
        return $products;
    }

    /**
     * Get Comment Product Counts
     * @param int $user_id
     * @param string $type
     * @return array
     */
    public function getCommentProductCount($user_id, $type = 'all')
    {
        $order_ids = Db::name('order')->where('user_id', $user_id)->where('status', 40)->column('id');
        $count = 0;
        if ($type == 'all') {
            $query = Db::name('order_snap');
            $query->whereIn('order_id', $order_ids);
            $count = $query->count();
        }
        return $count;
    }

    /**
     * Get Product Comment Count
     * @param int $product_id
     * @return array
     */
    public function getProductCommentCount($product_id)
    {
        $data = [];
        $data['all'] = 0;
        $data['haoping'] = 0;
        $data['zhongping'] = 0;
        $data['chaping'] = 0;
        $data['youtu'] = 0;
        $subsql = Db::name('product_comment_image')->field('comment_id, count(id) count')->group('comment_id')->buildSql();
        $product_comments = Db::name('product_comment')->alias('product_comment')
                        ->field('product_comment.*, product_comment_image.count as product_comment_image_count')
                        ->leftJoin([$subsql => 'product_comment_image'], 'product_comment_image.comment_id = product_comment.id')
                        ->where('product_comment.product_id', $product_id)
                        ->select();                               
        if (!empty($product_comments)) {
            $data['all'] = count($product_comments);
            foreach ($product_comments as $key => $value) {
                if ($value['star'] > 3) $data['haoping'] += 1;
                if ($value['star'] == 3) $data['zhongping'] += 1;
                if ($value['star'] < 3) $data['chaping'] += 1;
                if (!empty($value['product_comment_image_count'])) $data['youtu'] += 1;
            }
        }
        return $data;
    }

    /**
     * Get Product Comments
     * 产品详情页使用
     * @param int $params['product_id']
     * @param string $params['type']
     * @return array
     * @return int $data['lastPage'] 总页码
     * @return string $data['page'] 客户端使用分页样式
     * @return array $data['comments']
     */
    public function getProductComments_page($params = [])
    {
        $data = [];
        $subsql_image = Db::name('product_comment_image')->field('comment_id, count(id) count')->group('comment_id')->buildSql();
        $select = 'product_comment.*, user.username, user.face, product_comment_image.count as product_comment_image_count';
        if (isset($params['user_id'])) $select .= ', product_comment_zan.id as zan_id';
        $query = Db::name('product_comment')->alias('product_comment');
        $query->field($select);
        $query->leftJoin([$subsql_image => 'product_comment_image'], 'product_comment_image.comment_id = product_comment.id');
        if (isset($params['user_id'])) $query->leftJoin('product_comment_zan', 'product_comment_zan.comment_id = product_comment.id and product_comment_zan.user_id = '.$params['user_id']);
        $query->leftJoin('user', 'user.id = product_comment.user_id');
        $query->where('product_comment.product_id', $params['product_id']);
        $type = '';
        if (isset($params['type']) && !empty($params['type'])) {
            $type = $params['type'];
            switch ($params['type']) {
                case 'youtu':
                    $query->where('product_comment_image.count', '>', 0);
                    break;
                case 'haoping':
                    $query->where('product_comment.star', '>', 3);
                    break;
                case 'zhongping':
                    $query->where('product_comment.star', 3);
                    break; 
                case 'chaping':
                    $query->where('product_comment.star', '<', 3);
                    break;        
            }
        }
        $query->order('product_comment.id desc');
        $comments = $query->paginate();
        $comments = app(UserRepository::class)->setFace($comments);        
        $bothnum = 2;
        $page = '<div class="page">';
        $page .= '<ul class="pagination">';
        // 上一页
        if ($comments->currentPage() == 1) {
            $page .= '<li class="disabled"><span>«</span></li>';
        } else {
            $page .= '<li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', '.( $comments->currentPage() - 1 ).', \''.$type.'\')">«</a></li>';
        }
        // 首页
        if ($comments->currentPage() > ($bothnum + 1)) {
            $page .= '<li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', 1, \''.$type.'\')">1</a></li><li class="disabled"><span>...</span></li>';
        }
        // 数字目录
        for ($i = $bothnum; $i >= 1; $i--) {
			$_page = $comments->currentPage() - $i;
			if ($_page < 1) continue;
			$page .= '<li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', '.$_page.', \''.$type.'\')">'.$_page.'</a></li>';
        }
        $page .= '<li class="active"><span>'.$comments->currentPage().'</span></li>';
        for ($i = 1; $i <= $bothnum; $i++) {
			$_page = $comments->currentPage() + $i;
			if ($_page > $comments->lastPage()) break;
			$page .= '<li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', '.$_page.', \''.$type.'\')">'.$_page.'</a></li>';
        }
        // 尾页
        if ($comments->lastPage() - $comments->currentPage() > $bothnum) {
            $page .= '<li class="disabled"><span>...</span></li><li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', '.$comments->lastPage().', \''.$type.'\')">'.$comments->lastPage().'</a></li>';
        }
        // 下一页
        if ($comments->currentPage() == $comments->lastPage()) {
            $page .= '<li class="disabled"><span>»</span></li>';
        } else {
            $page .= '<li><a href="javascript:void(0);" onclick="getComment('.$params['product_id'].', '.( $comments->currentPage() + 1 ).', \''.$type.'\')">»</a></li>';
        }
        $page .= '</ul>';
        $page .= '</div>';
        $data['lastPage'] = $comments->lastPage();
        $data['page'] = $page;
        $items = $comments->items();
        if (!empty($items)) {
            $comment_ids = array_column($items, 'id');
            $comment_images = Db::name('product_comment_image')->whereIn('comment_id', $comment_ids)->select();
            $array = [];
            foreach ($comment_images as $key => $value) {
                $array[$value['comment_id']][] = $value;
            }
            foreach ($items as $key => $value) {
                $items[$key]['images'] = isset($array[$value['id']]) ? $array[$value['id']] : [];
            }
        }
        $data['comments'] = $items;
        return $data;
    }

    /**
     * 点赞产品评价
     * @param int $comment_id
     * @param int $user_id
     * @return array
     */
    public function zanProduct($comment_id, $user_id)
    {
        if (!Db::name('product_comment')->where('id', $comment_id)->where('status', 1)->find()) return arrayFailed('评价不存在');
        $zan = Db::name('product_comment_zan')->where('comment_id', $comment_id)->where('user_id', $user_id)->find();
        if (!empty($zan)) return arrayFailed('只能点赞一次呦');
        Db::startTrans();
        try {
            Db::name('product_comment_zan')->insert(['comment_id' => $comment_id, 'user_id' => $user_id, 'ip' => Request()->ip(), 'create_time' => date('Y-m-d H:i:s')]);
            Db::name('product_comment')->where('id', $comment_id)->setInc('zan');
            Db::commit();
            return arraySuccess();
        } catch (\Throwable $th) {
            Db::rollback();
            return arrayFailed();
        }
    }
}