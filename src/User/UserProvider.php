<?php

namespace App\User\Console;

class UserProvider
{
    public function getDefinitions()
    {
        return [

        ];
    }

    public function getEventHandlers()
    {
        return [
            'register-http-routes' => [$this, 'bindRoutes']
        ];
    }

    public function bindRoutes()
    {
        
    }
}
