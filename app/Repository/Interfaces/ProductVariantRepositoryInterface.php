<?php

namespace App\Repository\Interfaces;

interface ProductVariantRepositoryInterface
{
    public function getAllProductVariants(int $paginator, array $request);

    public function getProductVariantRate($product_variant_id);
}
