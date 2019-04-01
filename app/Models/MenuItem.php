<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes;

    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'picture',
        'menu_id',
        'price',
        'stock',
    ];

    public function menu() {
        return $this->hasOne(Menu::class);
    }

    public function store($data) {
        try {
            $self = self::firstOrNew(['name' => strtolower($data['name']), 'menu_id' => $data['menu_id']]);
            if (!empty($self->id)) {
                throw new \Exception('menu item exists to menu selected');
            }
            $fields = [
                'name'          => $data['name'],
                'description'   => $data['description'],
                'picture'       => $data['picture'],
                'menu_id'       => $data['menu_id'],
                'price'         => $data['price'],
                'stock'         => $data['stock'],
            ];
            $self = self::create($fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $self;
    }

    public function put($data)
    {
        try {
            $self = self::find($data['id']);
            if (empty($self->id)) {
                throw new \Exception('menu item not found');
            }
            $fields = [
                'id'            => $data['id'],
                'name'          => $data['name'],
                'description'   => $data['description'],
                'picture'       => $data['picture'],
                'menu_id'       => $data['menu_id'],
                'price'         => $data['price'],
                'stock'         => $data['stock'],
            ];
            $self->update($fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $self;
    }

    public function del($id) {
        try {
            $self = self::find($id);
            if (empty($self->id)) {
                throw new \Exception('menu item not found');
            }
            $self->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $self;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}