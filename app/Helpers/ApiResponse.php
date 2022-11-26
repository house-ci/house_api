<?php


namespace App\Helpers;


class ApiResponse
{
    const SUCCESS = [
        "code" => 200,
        "message" => "Success"
    ];

    const UPDATESUCCESS = [
        "code" => 200,
        "message" => "Successfully update"
    ];
    const DELETESUCCESS = [
        "code" => 200,
        "message" => "Successfully delete"
    ];

    const PASSWORDCHANGE = [
        "code" => 200,
        "message" => 'Password successfully change'
    ];

    const SERVERERROR = [
        "code" => 500,
        "message" => "An Error Occur",
    ];

    const NOTFOUND = [
        "code" => 404,
        "message" => "Resource not found"
    ];

    const UNAUTHENTICATED = [
        "code" => 401,
        "message" => "Must be login to access resource"
    ];

    const UNAUTHORIZED = [
        "code" => 403,
        "message" => "Permission denied"
    ];

   const FORBIDDEN = [
        "code" => 403,
        "message" => "Action forbidden"
    ];

   const CANTEDITSUPERADMIN = [
        "code" => 403,
        "message" => "Can't edit superadmin"
    ];

    const UNPROCESSABLE = [
        "code" => 422,
        "message" => "Validation Error"
    ];

    const EMPTYREQUEST = [
        "code" => 400,
        "message" => "Request can't be empty"
    ];

    const BADREQUEST = [
        "code" => 400,
        "message" => "Bad request, refer to manual"
    ];

    const RESOURCEEXISTE = [
        "code" => 400,
        "message" => "Same request already processed"
    ];

    public static function getRessourceSuccess(int $code, $resource)
    {
        return $response = [
            "code" => $code,
            "status" => "SUCCESS",
            "message" => "Success",
            "data" => $resource,
        ];
    }

    public static function loginSuccess($user, $token)
    {
        return $response = [
            "code" => 200,
            "status" => "SUCCESS",
            "message" => "Authentication success",
            "data" => ["user" => $user],
            "token" => $token
        ];
    }

    public static function error(int $code, $error)
    {
        return $response = [
            "code" => $code,
            "status" => "ERROR",
            "message" => $error,
            "data" => [],
        ];
    }
}
