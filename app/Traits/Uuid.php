<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Boot function dari model trait.
     */
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Menetapkan tipe primary key ke string.
     *
     * @return void
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Menetapkan bahwa primary key tidak auto increment.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }
}
