<?php

namespace Moveon\Setting\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Moveon\Customer\Models\Product;
use Moveon\Setting\Models\Event;

class EventImport implements ToCollection, WithChunkReading, WithHeadingRow
{
    private string $type;
    private $originId;

    public function __construct($type)
    {
        $this->type     = $type;
        $this->originId = Auth::user()->origin->id;
    }

    /**
     * @param Collection $collections
     * @throws ValidationException
     */
    public function collection(Collection $collections): void
    {
        $validator = Validator::make($collections->toArray(), [
            '*.customer_id'     => 'required',
            '*.created_at'      => 'required',
            '*.sub_total_price' => ($this->type === Event::TYPE_ORDERS) ? 'required' : '',
            '*.total_tax'       => ($this->type === Event::TYPE_ORDERS) ? 'required' : '',
            '*.total_discount'  => ($this->type === Event::TYPE_ORDERS) ? 'required' : '',
            '*.total_price'     => ($this->type === Event::TYPE_ORDERS) ? 'required' : '',
            '*.fully_paid'      => ($this->type === Event::TYPE_ORDERS) ? 'required' : '',
            '*.item_ids'        => ($this->type === Event::TYPE_CARTS || $this->type === Event::TYPE_WISH_LIST) ? 'required' : '',
            '*.product_id'      => ($this->type === Event::TYPE_PRODUCTS) ? 'required' : '',
            '*.min_price'       => ($this->type === Event::TYPE_PRODUCTS) ? 'required' : '',
            '*.max_price'       => ($this->type === Event::TYPE_PRODUCTS) ? 'required' : '',
            '*.image_url'       => ($this->type === Event::TYPE_PRODUCTS) ? 'required' : '',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        foreach ($collections as $collection) {
            switch ($this->type) {
                case $this->type === Event::TYPE_ORDERS:
                    Event::create([
                        'user_id'          => $this->originId,
                        'customer_id'      => $collection['customer_id'],
                        'type'             => Event::TYPE_ORDERS,
                        'sub_total_price'  => $collection['sub_total_price'],
                        'total_tax'        => $collection['total_tax'],
                        'total_discount'   => $collection['total_discount'],
                        'total_price'      => $collection['total_price'],
                        'fully_paid'       => $collection['fully_paid'],
                        'event_created_at' => $collection['created_at'],
                    ]);
                    break;

                case $this->type === Event::TYPE_CARTS:
                    Event::create([
                        'user_id'          => $this->originId,
                        'customer_id'      => $collection['customer_id'],
                        'type'             => Event::TYPE_CARTS,
                        'item_ids'         => explode(',', $collection['item_ids']),
                        'event_created_at' => $collection['created_at'],
                    ]);
                    break;

                case $this->type === Event::TYPE_WISH_LIST:
                    Event::create([
                        'user_id'          => $this->originId,
                        'customer_id'      => $collection['customer_id'],
                        'type'             => Event::TYPE_WISH_LIST,
                        'item_ids'         => explode(',', $collection['item_ids']),
                        'event_created_at' => $collection['created_at'],
                    ]);
                    break;

                case $this->type === Event::TYPE_PRODUCTS:
                    Product::create([
                        'user_id'          => $this->originId,
                        'type'             => Event::TYPE_PRODUCTS,
                        'product_id'       => $collection['product_id'],
                        'title'            => $collection['title'],
                        'price_range'      => ['min' => $collection['min_price'], 'max' => $collection['max_price']],
                        'image_url'        => $collection['image_url'],
                        'event_created_at' => $collection['created_at'],
                    ]);
                    break;

                default:
                    Event::create([
                        'user_id'          => $this->originId,
                        'customer_id'      => $collection['customer_id'],
                        'type'             => $this->type,
                        'event_created_at' => $collection['created_at'],
                    ]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 300;
    }
}
