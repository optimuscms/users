<?php

namespace Optimus\Users\Models;

// use Optix\Media\HasMedia;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasApiTokens, /* HasMedia, */Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    // public function registerMediaGroups()
    // {
    //     $this->addMediaGroup('avatar')
    //          ->performConversions('avatar');
    // }
}
