<?php


namespace App\Api;

use App\Product;
use Torann\RemoteModel\Model as RemoteModel;

class Model extends RemoteModel
{

    // the remote model package is npot immediatly compatible with this version of Laravel 8(

    protected function fireModelEvent($event, $halt = true)
    {
        // We will append the names of the class to the event to distinguish it from
        // other model events that are fired, allowing us to listen on each model
        // event set individually instead of catching event for all the models.

        // make compatible with Laravel 5.8 eventing - use dispatch instead of fire

        $event = "eloquent.{$event}: " . get_class($this);

        $method = $halt ? 'until' : 'dispatch';

        return app('events')->$method($event, $this);
    }



}