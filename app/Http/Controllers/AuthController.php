<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use App\Models\UsersSessions;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function user_auth(Request $request)
    {
        $params =
            [
                'id' => $request['id'],
                'last_name' => $request['last_name'],
                'first_name' => $request['first_name'],
                'access_token' => $request['access_token'],
                'country' => $request['country'],
                'city' => $request['city']
            ];

        ksort($params);

        $paramsToString = '';

        foreach ($params as $key => $param) {
            $paramsToString .= $key . '=' . $param;
        }

        $paramsToString .= env('SECRET_KEY');
        $paramsToString = mb_strtolower(md5($paramsToString), 'UTF-8');

        if ($paramsToString === $request['sig']) {
            $user = Users::find($request['id']);

            if (empty($user))
                $user = new Users();

            $user->last_name = $request['last_name'];
            $user->first_name = $request['first_name'];
            $user->country = $request['country'];
            $user->city = $request['city'];
            $user->save();

            UsersSessions::updateOrCreate(['user_id' => $params['id']], ['access_token' => $params['access_token']]);

            $json =
                [
                    'access_token' => $params['access_token'],
                    'user_info' =>
                        [
                            'id' => $params['id'],
                            'last_name' => $params['last_name'],
                            'first_name' => $params['first_name'],
                            'city' => $params['city'],
                            'country' => $params['country']
                        ],
                    'error' => '',
                    'error_key' => ''
                ];


        } else {
            $json =
                [
                    'error' => "Ошибка авторизации в приложении",
                    'error_key' => 'signature error'
                ];
        }

        return json_encode($json);
    }
}
