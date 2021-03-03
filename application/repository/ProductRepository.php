<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Product Repository
 * ============================================================================
 * Author: Jasper   
 */

namespace app\repository;

use think\Db;
use app\repository\ProductAttributeRepository;
use app\repository\ProductSpecificationRepository;
use app\repository\ImageRepository;

class ProductRepository
{
    public function getProducts($params = [], $limit = 0)
    {
        $select = 'product.*';
        $query = Db::name('product')->alias('product');
        $query->leftJoin('product_sku', 'product_sku.product_id = product.id')->group('product_sku.product_id');
        $query->field($select);
        $this->setGetProductsParams($query, $params);
        $query->where('product.status', 1);
        $query->order('product.sort', 'desc')->order('product.create_time', 'desc');
        if ($limit > 0) $query->limit($limit);
        $products = $query->select();
        return $products;
    }

    public function getProductsPaginate($params = [], $page_size = 15)
    {
        $select = 'product.*';
        $query = Db::name('product')->alias('product');
        $query->leftJoin('product_sku', 'product_sku.product_id = product.id')->group('product_sku.product_id');
        $query->field($select);
        $this->setGetProductsParams($query, $params);
        $query->where('product.status', 1);
        $query->order('product.sort', 'desc')->order('product.create_time', 'desc');
        $products = $query->paginate($page_size);
        return $products;
    }

    private function setGetProductsParams($query, $params = [])
    {
        if (isset($params['product_ids'])) $query->whereIn('product.id', $params['product_ids']);
        if (isset($params['category_id'])) $query->where('product.category_id', $params['category_id']);
        if (isset($params['category_ids'])) $query->whereIn('product.category_id', $params['category_ids']);
        if (isset($params['k'])) $query->where('product.name', 'like', '%'.$params['k'].'%');
        if (isset($params['sort'])) {
            $order = isset($params['order']) && $params['order'] == 2 ? 'desc' : 'asc';
            switch ($params['sort']) {
                case 'sale':
                    $query->order('product_sku.sale '.$order.'');
                    break;
                case 'new':
                    $query->order('product.create_time '.$order.'');
                    break;
                case 'price':
                    $query->order('product_sku.sale_price '.$order.'');
                    break;
                case 'rand':
                    $query->orderRand();
                    break; 
                default:
                    $query->order('product.sort '.$order.'');
                    break;
            }
        }
    }

    /**
     * get products format 1
     * 获取产品数据 格式 1
     * @param
     * @return array
     * @return 产品+多个关联sku
     */
    public function getProducts_format1($params = [], $limit = 0)
    {
        $products = $this->getProducts($params, $limit);
        if (empty($products)) return $products;
        $product_ids = array_column($products, 'id');
        $product_skus = $this->getSkus(['product_ids' => $product_ids]);
        foreach ($products as $key => $value) {
            $products[$key]['skus'] = isset($product_skus[$value['id']]) ? $product_skus[$value['id']] : [];
        }
        return $products;
    }

    /**
     * get products paginate format 1
     * 获取产品数据 格式 1
     * @param
     * @return array
     * @return 产品+多个关联sku
     */
    public function getProductsPaginate_format1($params = [], $page_size = 15)
    {
        $products = $this->getProductsPaginate($params, $page_size);
        if ($products->total() == 0) return $products;
        $product_ids = array_column($products->items(), 'id');
        $product_skus = $this->getSkus(['product_ids' => $product_ids]);
        $products->each(function($value, $key) use ($product_skus) {
            $value['skus'] = isset($product_skus[$value['id']]) ? $product_skus[$value['id']] : [];
            return $value;
        });
        return $products;
    }

    /**
     * get products format 2
     * 获取产品数据 格式 2
     * @param
     * @return array
     * @return 产品+第一个有效sku的整合数据
     */
    public function getProducts_format2($params = [], $limit = 0)
    {
        $products = $this->getProducts($params, $limit);
        $product_ids = array_column($products, 'id');
        $first_skus = $this->getProductsFirstSku($product_ids);
        foreach ($products as $key => $value) {
            $products[$key] = array_merge($products[$key], $first_skus[$value['id']]);
        }
        return $products;
    }

    /**
     * get products paginate format 2
     * 获取产品数据 格式 2
     * @param
     * @return array
     * @return 产品+第一个有效sku的整合数据
     */
    public function getProductsPaginate_format2($params = [], $page_size = 15)
    {
        $products = $this->getProductsPaginate($params, $page_size);
        $product_ids = array_column($products->items(), 'id');
        $first_skus = $this->getProductsFirstSku($product_ids);
        $products->each(function($value, $key) use ($first_skus) {
            $value = array_merge($value, $first_skus[$value['id']]);
            return $value;
        });
        return $products;    
    }

    /**
     * 获取多个产品的第一个有效sku
     * @param array $product_ids
     * @return array
     */
    private function getProductsFirstSku($product_ids)
    {
        $filed = 'product_sku.product_id, product_sku.sku, product_sku.stock, product_sku.sale_price, product_sku.sort, product_image.image';
        $product_skus = Db::name('product_sku')->alias('product_sku')
                ->field($filed)
                ->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1'])
                ->whereIn('product_sku.product_id', $product_ids)
                ->where('product_sku.status', 1)
                ->order('product_sku.sort asc')
                ->select();          
        $first_skus = [];               
        foreach ($product_skus as $key => $value) {
            $first_skus[$value['product_id']] = $value;
        }
        $column_skus = array_column($first_skus, 'sku');
        $product_to_specifications = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['skus' => $column_skus]);
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['sku']][] = $value;
        }
        foreach ($product_skus as $key => $value) {
            $product_skus[$key]['image'] = app(ImageRepository::class)->setProductImage($value['image']);
            $product_skus[$key]['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
        }
        $first_skus = [];
        foreach ($product_skus as $key => $value) {
            $first_skus[$value['product_id']] = $value;
        }
        return $first_skus;
    }

    /**
     * 获取单个产品数据
     * @param string $params['sku']
     */
    public function getProduct($params = [])
    {
        $filed = 'product.*, product_sku.sku, product_sku.original_price, product_sku.sale_price,
                    product_sku.stock, product_freight.freight';
        $query = Db::name('product_sku')->alias('product_sku')->field($filed);
        $query->join('product', 'product.id = product_sku.product_id');
        $query->leftJoin('product_freight', 'product_freight.product_id = product.id');
        $query->where('product.status', 1)->where('product_sku.status', 1);
        if (isset($params['sku'])) $query->where('product_sku.sku', $params['sku']);
        $product = $query->find();
        if (empty($product)) return $product;
        $product = $this->setGetProductAddition($product, $params);
        return $product;
    }

    /**
     * 处理单个产品的附加数据
     * 处理产品图片
     * 处理产品图片集
     * 处理产品属性
     * 处理产品销售规格
     * 处理产品当前销售规格
     */
    private function setGetProductAddition($product, $params)
    {
        $default_sku = $this->getDefaultSku($product['id']);
        // get product image
        $product_image = Db::name('product_image')->where('sku', $product['sku'])->where('default', 1)->value('image');
        $product['image'] = app(ImageRepository::class)->setProductImage($product_image, $thumbnail = 0);
        $product['image_430x430'] = app(ImageRepository::class)->setProductImage($product_image);
        // get product images
        $product_images = Db::name('product_image')->where('sku', $product['sku'])->where('default', 0)->select();
        foreach ($product_images as $key => $value) {
            $value['image'] = app(ImageRepository::class)->setProductImage($value['image'], $thumbnail = 0);
            $value['image_430x430'] = app(ImageRepository::class)->setProductImage($value['image']);
            $product['images'][] = $value;
        }
        // set product content
        $product['content'] = app(ImageRepository::class)->setProductContentImage($product['content']);
        // get product attributes
        $product['attributes'] = app(ProductAttributeRepository::class)->getProductToAttributes($product['id']);
        // get product current specifications
        $product['specifications'] = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['sku' => $product['sku']]);
        // get product specifications
        $product['product_specifications'] = app(ProductSpecificationRepository::class)->getProductToSpecifications($product['sku'], $product['id']);
        return $product;
    }

    /**
     * 获取默认sku
     * @param int $product_id
     * @return string 默认sku值
     */
    private function getDefaultSku($product_id)
    {
        return Db::name('product_sku')->where('product_id', $product_id)->where('default', 1)->value('sku');
    }

    /**
     * 获取默认sku集
     * @param array $product_ids
     * @return array 默认sku集
     */
    public function getDefaultProductSkus($product_ids)
    {
        return Db::name('product_sku')->whereIn('product_id', $product_ids)->where('default', 1)->column('sku');
    }

    /**
     * 获取产品列表 
     * 含分页数据
     * 以 SKU 为单位
     * @return array
     */
    public function getProductSkusPaginate($params = [], $page_size = 15)
    {
        $select = 'product.name, product_sku.product_id, product_sku.sku, product_sku.original_price,
                   product_sku.sale_price, product_sku.stock, product_image.image';
        $query = Db::name('product_sku')->alias('product_sku');
        $query->field($select);
        $query->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1']);
        $query->leftJoin('product', 'product.id = product_sku.product_id');
        $this->setGetProductSkusParams($query, $params);
        $query->where('product_sku.status', 1)->where('product.status', 1);
        $product_skus = $query->paginate($page_size);
        if ($product_skus->total() == 0) return $product_skus;
        // 当前sku无封面图时，调取默认sku封面图
        $empty_image_product_ids = [];
        foreach ($product_skus->items() as $key => $value) {
            if (empty($value['image'])) $empty_image_product_ids[] = $value['product_id'];
        }
        $empty_image_product_ids = array_unique($empty_image_product_ids);
        $default_product_skus = app(ProductRepository::class)->getDefaultProductSkus($empty_image_product_ids);
        $default_product_sku_images = Db::name('product_image')->whereIn('sku', $default_product_skus)->where('default', 1)->select();
        $array = [];
        foreach ($default_product_sku_images as $key => $value) {
            $array[$value['product_id']] = $value['image'];
        }
        $product_skus->each(function($value, $key) use ($array) {
            if (empty($value['image'])) {
                $value['image'] = isset($array[$value['product_id']]) ? $array[$value['product_id']] : '';
            }
            $value['image'] = app(ImageRepository::class)->setProductImage($value['image'], $value['product_id']);
            return $value;
        });
        // 调取销售规格
        $skus = array_column($product_skus->items(), 'sku');
        $product_to_specifications = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['skus' => $skus]);
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['sku']][] = $value;
        }
        $product_skus->each(function($value, $key) use ($array) {
            $value['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
            return $value;
        });
        return $product_skus;
    }

    /**
     * 获取产品列表
     * 以 SKU 为单位
     * @return array
     */
    public function getProductSkus($params = [])
    {
        $select = 'product.*, product_sku.product_id, product_sku.sku, product_sku.original_price,
                   product_sku.sale_price, product_sku.stock, product_image.image';
        $query = Db::name('product_sku')->alias('product_sku');
        $query->field($select);
        $query->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1']);
        $query->leftJoin('product', 'product.id = product_sku.product_id');
        $this->setGetProductSkusParams($query, $params);
        $query->where('product_sku.status', 1);
        $product_skus = $query->select();
        if (empty($product_skus)) return $product_skus;
        // 当前sku无封面图时，调取默认sku封面图
        $product_ids = [];
        foreach ($product_skus as $key => $value) {
            if (empty($value['image'])) $product_ids[] = $value['id'];
        }
        $product_ids = array_unique($product_ids);
        $default_product_skus = app(ProductRepository::class)->getDefaultProductSkus($product_ids);
        $default_product_sku_images = Db::name('product_image')->whereIn('sku', $default_product_skus)->where('default', 1)->select();
        $array = [];
        foreach ($default_product_sku_images as $key => $value) {
            $array[$value['product_id']] = $value['image'];
        }
        foreach ($product_skus as $key => $value) {
            if (empty($value['image'])) {
                $product_skus[$key]['image'] = isset($array[$value['id']]) ? $array[$value['id']] : '';
            }
        }
        // 调取销售规格
        $skus = array_column($product_skus, 'sku');
        $product_to_specifications = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['skus' => $skus]);
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['sku']][] = $value;
        }
        foreach ($product_skus as $key => $value) {
            $product_skus[$key]['image'] = app(ImageRepository::class)->setProductImage($value['image'], $value['product_id']);
            $product_skus[$key]['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
        }
        return $product_skus;
    }

    private function setGetProductSkusParams($query, $params)
    {
        if (isset($params['skus'])) $query->whereIn('product_sku.sku', $params['skus']);
        if (isset($params['category_id'])) $query->where('product.category_id', $params['category_id']);
        if (isset($params['category_ids'])) $query->whereIn('product.category_id', $params['category_ids']);
    }

    /**
     * 获取SKU列表
     * @return array
     */
    public function getSkus($params = [])
    {
        $select = 'product_sku.*, product_image.image';
        $query = Db::name('product_sku')->alias('product_sku');
        $query->field($select);
        $query->leftJoin('product_image', ['product_image.sku = product_sku.sku', 'product_image.default = 1']);
        $this->setGetSkusParams($query, $params);
        $query->where('product_sku.status', 1);
        $query->order('product_sku.sort desc');
        $product_skus = $query->select();
        if (empty($product_skus)) return $product_skus;
        // 调取销售规格
        $skus = array_column($product_skus, 'sku');
        $product_to_specifications = app(ProductSpecificationRepository::class)->getCurrentProductToSpecifications(['skus' => $skus]);
        $array = [];
        foreach ($product_to_specifications as $key => $value) {
            $array[$value['sku']][] = $value;
        }
        foreach ($product_skus as $key => $value) {
            $product_skus[$key]['image'] = app(ImageRepository::class)->setProductImage($value['image']);
            $product_skus[$key]['specifications'] = isset($array[$value['sku']]) ? $array[$value['sku']] : [];
        }
        $array = [];
        foreach ($product_skus as $key => $value) {
            $array[$value['product_id']][] = $value;
        }
        return $array;
    }

    private function setGetSkusParams($query, $params)
    {
        if (isset($params['product_id'])) $query->where('product_sku.product_id', $params['product_id']);
        if (isset($params['product_ids'])) $query->whereIn('product_sku.product_id', $params['product_ids']);
    }

    public function getCollectProducts($user_id)
    {
        $user_collect_product_skus = Db::name('user_collect_product')->where('user_id', $user_id)->column('sku');
        $skus = $this->getProductSkus(['skus' => $user_collect_product_skus]);
        return $skus;
    }

    public function getCollectProductsPaginate($user_id)
    {
        $user_collect_product_skus = Db::name('user_collect_product')->where('user_id', $user_id)->column('sku');
        $skus = $this->getProductSkusPaginate(['skus' => $user_collect_product_skus]);
        return $skus;
    }

    public function is_collect($sku, $user_id)
    {
        $is_collect = 0;
        if (empty($user_id)) return $is_collect;
        $id = Db::name('user_collect_product')->where('user_id', $user_id)->where('sku', $sku)->value('id');
        if (!empty($id)) $is_collect = 1;
        return $is_collect; 
    }

    public function collect($sku, $user_id)
    {
        $user_collect_product = Db::name('user_collect_product')->where('user_id', $user_id)->where('sku', $sku)->find();
        if (!empty($user_collect_product)) {
            Db::name('user_collect_product')->where('user_id', $user_id)->where('sku', $sku)->delete();
        } else {
            Db::name('user_collect_product')->insert(['sku' => $sku, 'user_id' => $user_id]);
        }
        return arraySuccess();
    }

    public function removeCollectProducts($skus, $user_id)
    {
        $query = Db::name('user_collect_product');
        $query->whereIn('sku', $skus)->where('user_id', $user_id);
        $query->delete();
        return arraySuccess();
    }

    /**
     * 获取分类推荐产品
     */
    public function getCategoryRecommendProducts()
    {
        $categorys = Db::name('product_category')
                    ->alias('product_category')
                    ->field('product_category.*')
                    ->where('product_category.status', 1)
                    ->where('product_category.parent_id', 0)
                    ->order('product_category.sort desc')
                    ->select();          
        if (!$categorys) return [];
        foreach ($categorys as $key => $value) {
            $categorys[$key]['products'] = $this->getProducts_format2(['category_ids' => $value['child_ids']], $limit = 10);
        }
        return $categorys;
    }

    public function setNav($category_id)
    {
        $nav = '<a href="'.Config('app.app_host').'">首页</a>';
        $category_parent_ids = app(ProductCategoryRepository::class)->getCategoryParentIds($category_id);
        $categorys = Db::name('product_category')->field('id, name')->whereIn('id', $category_parent_ids)->select();
        $category_names = array_column($categorys, 'name');
        foreach ($categorys as $key => $value) {
            $nav .= ' > <a href="'.url('product/products', ['category_id' => $value['id']]).'">'.$value['name'].'</a>';
        }
        return $nav;
    }
}