<?php

/**
 * @author Ricardo Paes
 * @since 04/07/2020 às 14:14
 */

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid as GenerateUuid;

trait Uuid {

    public static function boot() {
        parent::boot();

        static::creating(function($obj) {
            $obj->id = GenerateUuid::uuid4();
        });
    }
}
