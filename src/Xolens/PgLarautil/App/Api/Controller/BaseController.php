<?php

namespace Xolens\PgLarautil\App\Api\Controller;

use Illuminate\Http\Request;
use Xolens\PgLarautil\App\Util\RepositoryResponse;

use Xolens\PgLarauser\App\Repository\UserRepository;
use Xolens\PgLarauser\App\Repository\ProfileRepository;
use Xolens\PgLarauser\App\Repository\ProfileAccessViewRepository;
use Xolens\PgLarauser\App\Repository\ProfileAccessRepository;
use Xolens\PgLarauser\App\Repository\PasswordResetRepository;
use Xolens\PgLarauser\App\Repository\LoginHistoryRepository;
use Xolens\PgLarauser\App\Repository\GroupRepository;
use Xolens\PgLarauser\App\Repository\AccessRepository;

use Xolens\PgLarautil\App\Util\Model\Filterer;
use Xolens\PgLarautil\App\Util\Model\Sorter;
use Xolens\PgLarautil\App\Repository\AbstractReadableRepository;
use Carbon\Carbon;


use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const TAG_ACTION="ACTION";
    protected const TAG_SUCCESS="success";
    protected const TAG_ERRORS="errors";
    protected const TAG_RESPONSE="response";
    protected const TAG_REPOSITORY="repository";
    protected const TAG_SUBROUTE="subroute";

    protected const ACTION_PAGINATE="PAGINATE";
    protected const ACTION_GET="GET";
    protected const ACTION_STORE="STORE";
    protected const ACTION_UPDATE="UPDATE";
    protected const ACTION_DELETE="DELETE";
    
    protected const MSG_NOTFOUND="Route not found";
   
    protected static function datetimeString(){
        return Carbon::now()->toDateTimeString();
    }

    protected static function now(){
        return Carbon::now();
    }

    protected static function inflateSorter(Request $request){
        $sortArray = json_decode($request->input('sort'), true);
        if($sortArray==null){
            $sortArray = [['property'=>'id','direction'=>'DESC']];
        }
        $sorter = new Sorter($sortArray);
        return $sorter;
    }
    
    protected static function inflateFilterer(Request $request){
        $filterArray = json_decode($request->input('filter'), true);
        if($filterArray==null){
            $filterArray = [];
        }
        $filterer = new Filterer($filterArray);
        return $filterer;
    }

    public function repository($map, $subroute){
        return $map[$subroute][self::TAG_REPOSITORY];
    }

    public function notFound($subroute){
        return response([self::TAG_SUCCESS => false, self::TAG_ERRORS=> self::MSG_NOTFOUND, self::TAG_SUBROUTE=> $subroute], 404);
    }
    
    protected function hasAction($map, $subroute, $action){
        if(array_key_exists($subroute, $map)){
            if(array_key_exists(self::TAG_ACTION, $map[$subroute])){
                return in_array($action, $map[$subroute][self::TAG_ACTION]);
            }
        }
        return false;
    }

    public static function getDataResponse($response){
        $resp = new RepositoryResponse();
        $resp->setSuccess(true);
        $resp->setResponse(['data'=>$response]);
        return $resp;
    }

    public static function successResponse($response=null){
        $resp = new RepositoryResponse();
        $resp->setSuccess(true);
        if($response!=null){
            $resp->setResponse(['data'=>$response]);
        }
        return $resp;
    }

    public static function errorResponse($errors){
        $resp = new RepositoryResponse();
        $resp->setSuccess(false);
        if(is_array($errors)){
            $resp->setErrors($errors);
        }else{
            $resp->setErrors([$errors]);
        }
        return $resp;
    }

    public function jsonResponse($val){
        return response()->json([
            self::TAG_SUCCESS => $val->success(),
            self::TAG_ERRORS => $val->errors(),
            self::TAG_RESPONSE => $val->response(),
        ], 200);
    }

    public function jsonError($errors, $response = null){
        return response()->json([
            self::TAG_SUCCESS => false,
            self::TAG_ERRORS => $errors,
            self::TAG_RESPONSE => $response,
        ], 200);
    }

    public function jsonSuccess($response=null){
        return response()->json([
            self::TAG_SUCCESS => true,
            self::TAG_ERRORS => [],
            self::TAG_RESPONSE => $response,
        ], 200);
    }

    public static function escapeData(array $data){
        $eschaped = [];
        foreach ($data as $key => $value) {
            if($value==null|| $value==''){
                $eschaped[$key] = null;
            }else{
                $eschaped[$key] = htmlspecialchars($value);
            }
        }
        return $eschaped;
    }

    public static function validationMessages(array $data, $subroute = null){
        return [];
    }
}
