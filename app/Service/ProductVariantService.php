<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\ProductVariantRepositoryInterface;
use App\Traits\Helpers;
//use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
//use Maestroerror\HeicToJpg;
use Symfony\Component\Mime\MimeTypes;

class ProductVariantService
{
    use Helpers;

    public function __construct(private readonly ProductVariantRepositoryInterface $productVariantRepository)
    {
    }

    public function getAllProductVariants(int $paginator, array $request)
    {
        return $this->productVariantRepository->getAllProductVariants($paginator, $request);
    }


    /**
     * @throws ErrorPageException
     */
    private function addAttachment($attachment): string
    {
        $file_path = public_path('storage/products');

        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->guessMimeType($attachment);
        $clientOriginalExtension = $mimeTypes->getExtensions($mimeType)[0];

        $fileName = "p_" . uniqid();

        //['jpg', 'png', 'gif', 'tif', 'bmp', 'ico', 'psd', 'webp', 'jpeg']
        $compressibleFileTypes = ['jpg', 'png', 'gif', 'tif', 'bmp', 'ico', 'webp', 'jpeg'];
        $nonCompressibleFileTypes = ['pdf', 'pptx', 'ppt', 'doc', 'docx', 'xls', 'xlsx'];

        if (in_array($clientOriginalExtension, $compressibleFileTypes)) {
            try {
                Image::make($attachment)
                    ->interlace()
                    ->orientate()
                    ->resize(100, 100, fn ($constraint) => $constraint->aspectRatio())
                    ->encode($clientOriginalExtension, 66.7)
                    ->save("$file_path/$fileName.$clientOriginalExtension");
                $fileName = "$fileName.$clientOriginalExtension";
            } catch (\Exception $ex) {
                Log::alert($ex->getMessage());
                throw new ErrorPageException('Cannot Save Image', 500);
            }
        } elseif (in_array($clientOriginalExtension, $nonCompressibleFileTypes)) {
            $fileName = "$fileName.$clientOriginalExtension";
            $attachment->move($file_path, $fileName);
        } /*elseif ($clientOriginalExtension === 'heic') {
            $tempFileName = 'temp_'.uniqid();
            HeicToJpg::convert($attachment)->saveAs(public_path("/storage/temp/$tempFileName.jpg"));;
            $fileName = $this->addAttachment($this->getFile(public_path("/storage/temp/$tempFileName.jpg")));
            unlink(public_path("/storage/temp/$tempFileName.jpg"));
            unlink(public_path("/storage/temp/tmp-files/$tempFileName.jpg"));
        }*/ else throw new ErrorPageException('Invalid File', 500);

        return $fileName;
    }

//    private function getFile($url): UploadedFile
//    {
//        //get name file by url and save in object-file
//        $path_parts = pathinfo($url);
//        //get image info (mime, size in pixel, size in bits)
//        $newPath = $path_parts['dirname'] . '/tmp-files/';
//        if (!is_dir($newPath)) {
//            mkdir($newPath, 0777);
//        }
//        $newUrl = $newPath . $path_parts['basename'];
//        copy($url, $newUrl);
//        $imgInfo = getimagesize($newUrl);
//        return new UploadedFile(
//            $newUrl,
//            $path_parts['basename'],
//            $imgInfo['mime'],
//            filesize($url),
//            TRUE,
//        );
//    }

    /**
     * @throws ErrorPageException
     */
    public function productVariantStore(array|\Illuminate\Support\ValidatedInput $request)
    {
        if (!$this->isValidFile($request['attachment']))
            return false;

        $fileName = $this->addAttachment($request['attachment']);

        $data = [
            'image_url' => Storage::url("products/$fileName"),
            'product_id' => $request['product_id'],
            'name' => $request['name'],
            'quantity' => $request['quantity'],
            'rate' => $request['rate'],
        ];

        if ($this->productVariantRepository->create($data))
            return true;

        throw new ErrorPageException('Cannot Create new Product Variant', 500);
    }


    /**
     * @throws ErrorPageException
     */
    public function productVariantUpdate(array|\Illuminate\Support\ValidatedInput $request): void
    {
        $data = [
            'product_id' => $request['product_id'],
            'name' => $request['name'],
            'quantity' => $request['quantity'],
            'rate' => $request['rate'],
        ];

        if (isset($request['attachment'])) {
            if ($this->isValidFile($request['attachment'])) {
                $product = $this->productVariantRepository->findByID($request['id']);
                unlink(public_path($product->image_url));
                $fileName = $this->addAttachment($request['attachment']);
                $data = array_merge($data, ['image_url' => Storage::url("products/$fileName")]);
            }
        }

        if ($this->productVariantRepository->update($request['id'], $data))
            return;

        throw new ErrorPageException('Cannot Update new Product Variant', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteProductVariant($product_variant_id): void
    {
        if ($this->productVariantRepository->destroy($product_variant_id))
            return;

        throw new ErrorPageException('Cannot Remove Product Variant', 500);
    }

    public function getProductVariantRate($product_id)
    {
        return $this->productVariantRepository->getProductVariantRate($product_id);
    }

    /**
     * @throws ErrorPageException
     */
    public function getAllProductVariantName(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productVariantRepository->all(['id', 'name']);
    }

    /**
     * @throws ErrorPageException
     */
    public function deductStockAddSales($request, $orderItemService, $productService, $salesService)
    {
        $orderItems = $orderItemService->getAllOrderItems($request['order_id']);

        $orderedItems = $orderItems->map(function ($item, $key) {
            return [
                'product_variant_id' => $item->product_variant_id,
                'product_id' => $item->product_variant->product_id,
                'per_unit_price' => $item->product_variant->product->per_unit_price,
                'order_quantity' => $item->quantity,
                'rate' => $item->rate,
                'product_quantity' => $item->product_variant->quantity
            ];
        })->toArray();

        if($salesService->recordDailySales($orderedItems) and $productService->stockUpdate($orderedItems))
            return true;

        throw new ErrorPageException('Cannot create payment', 500);
    }

}
