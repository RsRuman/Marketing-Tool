<?php

namespace Moveon\Image\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Moveon\Image\Models\Image;
use Moveon\Image\Repositories\ImageRepository;

class ImageService
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Get all image
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getImages($request): LengthAwarePaginator
    {
       return $this->imageRepository->all($request);
    }

    /**
     * @param $request
     * @return false|mixed
     */
    public function createImage($request): mixed
    {
        # Get info from image
        $image             = $request->file('image');
        $imageUniqueName   = time() . '.' . $image->getClientOriginalExtension();
        $imageOriginalName = $image->getClientOriginalName();
        $imageType         = $image->getClientMimeType();

        # Store image in storage
        $path = $image->storeAs('images', $imageUniqueName, 'public');

        # Sanitize data
        $data = [
            'user_id'       => Auth::user()->origin->id,
            'created_by_id' => Auth::user()->id,
            'name'          => $imageOriginalName,
            'type'          => $imageType,
            'link'          => $path
        ];

        try {
            DB::beginTransaction();

            $image = $this->imageRepository->create($data);

            if (!$image) {
                throw new Exception('Could not create image. Please try later');
            }

            # Attach image with category
            $categories = [Image::DEFAULT_CATEGORY];

            if ($request->input('categories')) {
                $categories = $request->input('categories');
            }

            $image->categories()->attach($categories);

            DB::commit();

            # Return fresh image
            return $image;

        } catch (Exception $ex) {
            DB::rollBack();
            Log::critical($ex->getMessage());
            return false;
        }
    }

    /**
     * Get Image
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function getImage($id): Model|Collection|Builder|array|null
    {
        return $this->imageRepository->find($id);
    }

    /**
     * Update image
     * @param $request
     * @param $id
     * @return Model|Collection|Builder|bool|array|null
     */
    public function updateImage($request, $id): Model|Collection|Builder|bool|array|null
    {
        $image = $this->getImage($id);

        if (!$image) {
            return null;
        }

        $data = $request->input('categories');

        $imageU = $this->imageRepository->update($image, $data);

        if (!$imageU) {
            return false;
        }

        return $image;
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deleteImage($id): ?bool
    {
        $image = $this->getImage($id);

        if (!$image) {
            return null;
        }

        $image = $this->imageRepository->delete($image);

        if (!$image) {
            return false;
        }

        return true;
    }
}
