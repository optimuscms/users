<?php

namespace Optimus\Users\Models;

// use Optix\Media\HasMedia;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use /* HasMedia, */Notifiable;

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
