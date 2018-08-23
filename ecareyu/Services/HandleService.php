<?php
/**
 * Created by PhpStorm.
 * User: EcareYu
 * Date: 2017/9/27
 * Time: 16:51
 */

namespace EcareYu\Services;

use EcareYu\Exceptions\ApiException;
use EcareYu\Services\UtilService as Util;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HandleService
{

    /**
     * 处理表单验证错误输出
     *
     * @param Validator $validator
     * @throws ApiException
     */
    public static function validatorErrors(Validator $validator)
    {
        if (!$validator->errors()->isEmpty()) {
            foreach ($validator->errors()->messages() as $error) {
                list($msg, $code) = explode('|', $error[0]);
                throw new ApiException($msg, $code);
            }
        }
    }

    /**
     * 处理异常
     *
     * @param Exception $exception
     * @return $this
     */
    public static function exception($exception)
    {
        $errorTrace = [];
        if (!\App::environment('production') && true === config('app.debug')) {
            $errorTrace = [
                'line' => $exception->getLine(),
                'errors' => $exception->getMessage(),
                'file' => $exception->getFile()
            ];
        }

        if (!($exception instanceof ApiException)) {
            if ($exception instanceof ModelNotFoundException) {
                $message = 'data not found';
                $errorCode = 404;
            } elseif ($exception instanceof NotFoundHttpException) {
                $message = '404 not found';
                $errorCode = 404;
            } elseif ($exception instanceof AuthenticationException) {
                $message = 'unauthorized.';
                $errorCode = 401;
            } elseif ($exception instanceof UnauthorizedHttpException) {
                $message = 'unauthorized.';
                $errorCode = 401;
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $message = 'method not allowed.';
                $errorCode = 405;
            } else {
                $message = $exception->getMessage() ? $exception->getMessage() : 'unknown error';
                $errorCode = 500;
            }
            $statusCode = $errorCode;
        } else {
            $errorCode = $exception->getCode();
            $message = $exception->getMessage();
            $statusCode = 500;
        }

        return Util::response($message, $errorTrace, $errorCode)
                ->setStatusCode($statusCode)
                ->header('Access-Control-Allow-Origin', config('cors.allowedOrigins'))
                ->header('Access-Control-Allow-Methods', config('cors.allowedMethods'))
                ->header('Access-Control-Allow-Headers', config('cors.allowedHeaders'))
                ->header('Access-Control-Max-Age', 0);
    }

}
