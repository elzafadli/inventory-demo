<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogApi extends Model
{
    protected $table = 'log_api';

    public function insertLog($request, $api)
    {

        $model  = new LogApi();

        $model->api = $api;
        $model->url = url()->current();
        $model->params = json_encode($request->all());
        $model->user_created = 'admin';

        $model->save();
    }
}
