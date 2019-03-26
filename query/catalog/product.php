<?php


use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\BooleanType;

require_once __DIR__ . '/../../helpers/pagination.php';

class QueryCatalogProduct
{
    public function getMutations()
    {
        return array(
            'addToCart' => array(
                'type'    => $this->getCartType(),
                'args'    => array(
                    'id'       => array(
                        'type' => new IntType(),
                    ),
                    'quantity' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->addToCart($args);
                }
            ),
            'updateCart' => array(
                'type' => $this->getCartType(),
                'args' => array(
                    'key' => array(
                        'type' => new StringType()
                    ),
                    'quantity' => array(
                        'type' => new IntType(),
                        'defaultValue' => 1
                    )
                    ),
                    'resolve' => function ($store, $args) {
                        return $this->updateCart($args);
                    }
            )
        );
    }

    public function getQuery()
    {
        return array(
            'cart'         => array(
                'type'    => $this->getCartType(),
                'resolve' => function ($store, $args) {
                    return $this->getCart($args);
                }
            ),
            'product'      => array(
                'type'    => $this->getProductType(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getProduct($args);
                }
            ),
            'productsList' => array(
                'type'    => getPagination($this->getProductType()),
                'args'    => array(
                    'page'        => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    ),
                    'size'        => array(
                        'type'         => new IntType(),
                        'defaultValue' => 10
                    ),
                    'filter'      => array(
                        'type'         => new StringType(),
                        'defaultValue' => ''
                    ),
                    'category_id' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 0
                    ),
                    'sort'        => array(
                        'type'         => new StringType(),
                        'defaultValue' => "sort_order"
                    ),
                    'order'       => array(
                        'type'         => new StringType(),
                        'defaultValue' => 'ASC'
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getProductList($args);
                }
            )
        );
    }


    public function get_woocommerce_currency_symbol($currency = '')
    {
        if (! $currency) {
            $currency = get_woocommerce_currency();
        }

        $symbols = apply_filters('woocommerce_currency_symbols', array(
            'AED' => 'د.إ',
            'AFN' => '؋',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => 'ƒ',
            'AOA' => 'Kz',
            'ARS' => '$',
            'AUD' => '$',
            'AWG' => 'ƒ',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '$',
            'BDT' => '৳ ',
            'BGN' => 'лв.',
            'BHD' => '.د.ب',
            'BIF' => 'Fr',
            'BMD' => '$',
            'BND' => '$',
            'BOB' => 'Bs.',
            'BRL' => 'R$',
            'BSD' => '$',
            'BTC' => '฿',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BZD' => '$',
            'CAD' => '$',
            'CDF' => 'Fr',
            'CHF' => 'CHF',
            'CLP' => '$',
            'CNY' => '¥',
            'COP' => '$',
            'CRC' => '₡',
            'CUC' => '$',
            'CUP' => '$',
            'CVE' => '$',
            'CZK' => 'Kč',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD$',
            'DZD' => 'د.ج',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '€',
            'FJD' => '$',
            'FKP' => '£',
            'GBP' => '£',
            'GEL' => 'ლ',
            'GGP' => '£',
            'GHS' => '₵',
            'GIP' => '£',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '$',
            'HKD' => '$',
            'HNL' => 'L',
            'HRK' => 'Kn',
            'HTG' => 'G',
            'HUF' => 'Ft',
            'IDR' => 'Rp',
            'ILS' => '₪',
            'IMP' => '£',
            'INR' => '₹',
            'IQD' => 'ع.د',
            'IRR' => '﷼',
            'IRT' => 'تومان',
            'ISK' => 'kr.',
            'JEP' => '£',
            'JMD' => '$',
            'JOD' => 'د.ا',
            'JPY' => '¥',
            'KES' => 'KSh',
            'KGS' => 'сом',
            'KHR' => '៛',
            'KMF' => 'Fr',
            'KPW' => '₩',
            'KRW' => '₩',
            'KWD' => 'د.ك',
            'KYD' => '$',
            'KZT' => 'KZT',
            'LAK' => '₭',
            'LBP' => 'ل.ل',
            'LKR' => 'රු',
            'LRD' => '$',
            'LSL' => 'L',
            'LYD' => 'ل.د',
            'MAD' => 'د.م.',
            'MDL' => 'MDL',
            'MGA' => 'Ar',
            'MKD' => 'ден',
            'MMK' => 'Ks',
            'MNT' => '₮',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '₨',
            'MVR' => '.ރ',
            'MWK' => 'MK',
            'MXN' => '$',
            'MYR' => 'RM',
            'MZN' => 'MT',
            'NAD' => '$',
            'NGN' => '₦',
            'NIO' => 'C$',
            'NOK' => 'kr',
            'NPR' => '₨',
            'NZD' => '$',
            'OMR' => 'ر.ع.',
            'PAB' => 'B/.',
            'PEN' => 'S/.',
            'PGK' => 'K',
            'PHP' => '₱',
            'PKR' => '₨',
            'PLN' => 'zł',
            'PRB' => 'р.',
            'PYG' => '₲',
            'QAR' => 'ر.ق',
            'RMB' => '¥',
            'RON' => 'lei',
            'RSD' => 'дин.',
            'RUB' => '₽',
            'RWF' => 'Fr',
            'SAR' => 'ر.س',
            'SBD' => '$',
            'SCR' => '₨',
            'SDG' => 'ج.س.',
            'SEK' => 'kr',
            'SGD' => '$',
            'SHP' => '£',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '$',
            'SSP' => '£',
            'STD' => 'Db',
            'SYP' => 'ل.س',
            'SZL' => 'L',
            'THB' => '฿',
            'TJS' => 'ЅМ',
            'TMT' => 'm',
            'TND' => 'د.ت',
            'TOP' => 'T$',
            'TRY' => '₺',
            'TTD' => '$',
            'TWD' => 'NT$',
            'TZS' => 'Sh',
            'UAH' => '₴',
            'UGX' => 'UGX',
            'USD' => '$',
            'UYU' => '$',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VND' => '₫',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'Fr',
            'XCD' => '$',
            'XOF' => 'Fr',
            'XPF' => 'Fr',
            'YER' => '﷼',
            'ZAR' => 'R',
            'ZMW' => 'ZK',
        ));

        $currency_symbol = isset($symbols[ $currency ]) ? $symbols[ $currency ] : '';

        return apply_filters('woocommerce_currency_symbol', $currency_symbol, $currency);
    }

    public function getCart($args)
    {
        $cart = array();

        $cart['products'] = array();
        foreach (WC()->cart->get_cart() as $product) {
            $cart['products'][] = array(
                'key'      => $product['key'],
                'product'  => $this->getProduct(array( 'id' => $product['product_id'] )),
                'quantity' => $product['quantity'],
                'total'    => $product['line_total']. ' ' . $this->get_woocommerce_currency_symbol()
            );
        }
        return $cart;
    }

    public function addToCart($args)
    {
        WC()->cart->add_to_cart($args['id'], $args['quantity']);

        return $this->getCart($args);
    }

    public function updateCart($args)
    {
         WC()->cart->set_quantity($args['key'], $args['quantity']);

         return $this->getCart($args);
    }

    public function getProduct($args)
    {
        $product = wc_get_product($args['id']);

        $product_image      = wp_get_attachment_image_src($product->get_image_id(), 'full');
        $thumb              = $product_image[0];
        $product_lazy_image = wp_get_attachment_image_src($product->get_image_id(), array( 10, 10 ));
        $thumbLazy          = $product_lazy_image[0];

        return array(
            'id'               => $product->get_id(),
            'name'             => $product->get_name(),
            'description'      => $product->get_description(),
            'shortDescription' => $product->get_short_description(),
            'price'            => $product->get_price() . ' ' . $this->get_woocommerce_currency_symbol(),
            'special'          => $product->get_sale_price() . ' ' . $this->get_woocommerce_currency_symbol(),
            'model'            => $product->get_sku(),
            'image'            => $thumb,
            'imageLazy'        => $thumbLazy,
            'stock'            => $product->get_stock_status() === 'instock',
            'rating'           => (float) $product->get_average_rating()
        );
    }

    public function getProductList($args)
    {
        $filter_data = array(
            'page'     => $args['page'],
            'limit'    => $args['size'],
            'paginate' => true
        );

        if ($args['category_id'] !== 0) {
            $category                = get_term($args['category_id']);
            $filter_data['category'] = array( $category->slug );
        }

        $results = wc_get_products($filter_data);

        $products = [];

        foreach ($results->products as $product) {
            $products[] = $this->getProduct(array( 'id' => $product->get_id() ));
        }

        return array(
            'content'          => $products,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($results->total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($products),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) $results->max_num_pages,
            'totalElements'    => (int) $results->total,
        );
    }

    public function getProductAttributes($product, $args)
    {
        $product = wc_get_product($product['id']);

        $attributes = array();

        foreach ($product->get_attributes() as $attribute) {
            $attributes[] = array(
                'name'    => $attribute->get_name(),
                'options' => $attribute->get_options()
            );
        }

        return $attributes;
    }

    public function getProductImages($product, $args)
    {
        $product   = wc_get_product($product['id']);
        $image_ids = $product->get_gallery_image_ids();

        $image_ids = array_slice($image_ids, 0, $args['limit']);

        $images = array();

        foreach ($image_ids as $image_id) {
            $product_image      = wp_get_attachment_image_src($image_id, 'full');
            $thumb              = $product_image[0];
            $product_lazy_image = wp_get_attachment_image_src($image_id, array( 10, 10 ));
            $thumbLazy          = $product_lazy_image[0];
            $images[]           = array(
                'image'     => $thumb,
                'imageLazy' => $thumbLazy
            );
        }

        return $images;
    }

    public function getRelatedProducts($product, $args)
    {
        $product = wc_get_product($product['id']);

        $upsell_ids = $product->get_upsell_ids();

        $upsell_ids = array_slice($upsell_ids, 0, $args['limit']);

        $products = array();

        foreach ($upsell_ids as $product_id) {
            $products[] = $this->getProduct(array( 'id' => $product_id ));
        }


        return $products;
    }

    private function getCartType()
    {
        return new ObjectType(
            array(
                'name'        => 'Cart',
                'description' => 'Cart',
                'fields'      => array(
                    'products' => new ListType($this->getCartProductType())
                )
            )
        );
    }

    private function getCartProductType()
    {
        return new ObjectType(
            array(
                'name'        => 'CartProduct',
                'description' => 'CartProduct',
                'fields'      => array(
                    'key'      => new StringType(),
                    'product'  => $this->getProductType(),
                    'quantity' => new IntType(),
                    'total' => new StringType()
                )
            )
        );
    }

    private function getProductType($simple = false)
    {
        $fields = array();

        if (! $simple) {
            $fields = array(
                'products' => array(
                    'type'    => new ListType($this->getProductType(true)),
                    'args'    => array(
                        'limit' => array(
                            'type'         => new IntType(),
                            'defaultValue' => 3
                        )
                    ),
                    'resolve' => function ($parent, $args) {
                        return $this->getRelatedProducts($parent, $args);
                    }
                )
            );
        }

        return new ObjectType(
            array(
                'name'        => 'Product',
                'description' => 'Product',
                'fields'      => array_merge(
                    $fields,
                    array(
                        'id'               => new IdType(),
                        'image'            => new StringType(),
                        'imageLazy'        => new StringType(),
                        'name'             => new StringType(),
                        'shortDescription' => new StringType(),
                        'description'      => new StringType(),
                        'model'            => new StringType(),
                        'price'            => new StringType(),
                        'special'          => new StringType(),
                        'tax'              => new StringType(),
                        'minimum'          => new IntType(),
                        'stock'            => new BooleanType(),
                        'rating'           => new FloatType(),

                        'attributes' => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productAttribute',
                                        'fields' => array(
                                            'name'    => new StringType(),
                                            'options' => new ListType(new StringType())
                                        )
                                    )
                                )
                            ),
                            'resolve' => function ($parent, $args) {
                                return $this->getProductAttributes($parent, $args);
                            }
                        ),
                        'images'     => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productImage',
                                        'fields' => array(
                                            'image'     => new StringType(),
                                            'imageLazy' => new StringType()
                                        )
                                    )
                                )
                            ),
                            'args'    => array(
                                'limit' => array(
                                    'type'         => new IntType(),
                                    'defaultValue' => 3
                                )
                            ),
                            'resolve' => function ($parent, $args) {
                                return $this->getProductImages($parent, $args);
                            }
                        )
                    )
                )
            )
        );
    }
}
