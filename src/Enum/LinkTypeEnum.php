<?php

namespace App\Enum;

class LinkTypeEnum
{
    public const PRODUCT_LINK_TYPE = 'product';
    public const CATEGORY_LINK_TYPE = 'category';
    public const STATIC_PAGE_LINK_TYPE = 'static-page';
    public const CHECKOUT_LINK_TYPE = 'checkout';
    public const HOMEPAGE_LINK_TYPE = 'homepage';
    public const LINK_TYPES = [
        self::PRODUCT_LINK_TYPE,
        self::CATEGORY_LINK_TYPE,
        self::STATIC_PAGE_LINK_TYPE,
        self::CHECKOUT_LINK_TYPE,
        self::HOMEPAGE_LINK_TYPE,
    ];
}
