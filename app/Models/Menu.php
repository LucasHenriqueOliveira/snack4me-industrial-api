<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'event_id',
    ];

    public function event() {
        return $this->hasOne(Event::class);
    }

    public function store($data) {
        try {
            $self = self::firstOrNew(['title' => strtolower($data['title']), 'event_id' => $data['event_id']]);
            if (!empty($self->id)) {
                throw new \Exception('menu exists to event');
            }
            $fields = [
                'title'      => $data['title'],
                'event_id'   => $data['event_id'],
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
                throw new \Exception('menu not found');
            }
            $fields = [
                'id'        => $data['id'],
                'title'     => $data['title'],
                'event_id'  => $data['event_id'],
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
                throw new \Exception('menu not found');
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