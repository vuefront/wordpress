<?php

class VFA_ResolverBlogPost extends VFA_Resolver
{
    public function get($args)
    {
        $this->load->model('blog/post');
        $post = $this->model_blog_post->getPost($args['id']);

        if ($post->image_id) {
            $thumb     = wp_get_attachment_url($post->image_id, 'full');
            $thumbLazy = wp_get_attachment_url($post->image_id, array(10, 10));
        } else {
            $thumb = '';
            $thumbLazy = '';
        }

        $keyword = str_replace(get_site_url(), '', get_permalink($post->ID));
        $keyword = trim($keyword, '/?');
        $keyword = trim($keyword, '/');

        $date_format = '%A %d %B %Y';

        return array(
            'id'               => $post->ID,
            'name'             => $post->title,
            'title'            => $post->title,
            'shortDescription' => $post->shortDescription,
            'description'      => $post->description,
            'datePublished'    => iconv(mb_detect_encoding(strftime($date_format, strtotime($post->dateAdded))), "utf-8//IGNORE", strftime($date_format, strtotime($post->dateAdded))),
            'keyword'          => $keyword,
            'image'            => $thumb,
            'imageLazy'        => $thumbLazy,
            'meta'           => array(
                'title' => $post->title,
                'description' => $post->shortDescription,
                'keyword' => ''
            ),
            'url' => function ($root, $args) {
                return $this->load->resolver('blog/post/url', array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'prev' => function ($root, $args) {
                return $this->load->resolver('blog/post/prev', array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'next' => function ($root, $args) {
                return $this->load->resolver('blog/post/next', array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'reviews' => function ($root, $args) {
                return $this->load->resolver('blog/review/get', array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'categories' => function ($root, $args) {
                return $this->load->resolver('blog/post/categories', array(
                    'parent' => $root,
                    'args' => $args
                ));
            }
        );
    }

    public function getList($args)
    {
        $this->load->model('blog/post');
        $filter_data = array(
            'limit' => $args['size'],
            'start'         => ($args['page'] - 1) * $args['size'],
            'sort'        => $args['sort'],
            'order'          => $args['order']
        );

        if ($args['category_id'] != 0 && $args['category_id'] != '') {
            $filter_data['filter_category_id'] = $args['category_id'];
        }

        $results = $this->model_blog_post->getPosts($filter_data);

        $product_total = $this->model_blog_post->getTotalPosts($filter_data);

        $posts = array();

        foreach ($results as $post) {
            $posts[] = $this->get(array('id' => $post->ID));
        }

        return array(
            'content'          => $posts,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($product_total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($posts),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil($product_total / $args['size']),
            'totalElements'    => (int) $product_total,
        );
    }

    public function categories($args)
    {
        $post_info = $args['parent'];
        $this->load->model('blog/post');

        $result = $this->model_blog_post->getCategoriesByPost($post_info['id']);
        $categories = array();

        foreach ($result as $category) {
            $categories[] = $this->load->resolver('blog/category/get', array('id'=> $category->term_id));
        }

        return $categories;
    }
    public function prev($args)
    {
        $post_info = $args['parent'];
        global $post;
        $post = get_post($post_info['id']);
        $previous_post = get_previous_post(false);

        if (!$previous_post) {
            return null;
        }

        return $this->get(array('id' => $previous_post->ID));
    }

    public function next($args)
    {
        $post_info = $args['parent'];
        global $post;
        $post = get_post($post_info['id']);
        $next_post = get_next_post(false);

        if (!$next_post) {
            return null;
        }

        return $this->get(array('id' => $next_post->ID));
    }

    public function url($data) {
        $post_info = $data['parent'];
        $result = $data['args']['url'];

        $result = str_replace('_id', $post_info['id'], $result);
        $result = str_replace('_name', $post_info['name'], $result);

        if ($post_info['keyword']) {
            $result = '/'.$post_info['keyword'];
            $this->load->model('common/seo');
            $this->model_common_seo->addUrl($result, 'blog-post', $post_info['id']);
        }

        return $result;
    }

}
