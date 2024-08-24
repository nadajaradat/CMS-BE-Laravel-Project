<?php

namespace App\Actions;

use App\Constants\ResponseCode;
use App\Models\DeviceKey;
use App\Models\Driver;
use App\Models\User;
use Barryvdh\TranslationManager\Controller;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiActions
{

    public static function generateResponse($data=null, $message_key="Results",$code=ResponseCode::OK,$total_records=0,$has_more=false)
    {

        $result=[
            'status'     => $code == 200,
            'statusCode' => $code,
            'response_message' => $message_key,
            'total_records'=>(int)$total_records,
            'has_more'=>(boolean)$has_more,
        ];
        if ( $code == 200) {
            if (is_array($data)) {
                $result = array_merge($result, $data);
            } else {
                $result['data'] = $data;
            }
        }else{
            $result['errors'] = $data;
        }
        return response()->json($result, $code,[],JSON_UNESCAPED_SLASHES);
    }


}
