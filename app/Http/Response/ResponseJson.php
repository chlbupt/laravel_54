<?php
namespace App\Http\Response;

trait ResponseJson
{
    public  function jsonResponse($code, $message, $data){
        $content = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($content);
    }
    public function jsonSuccessData($data = []){
        return $this->jsonResponse(0, 'Success', $data);
    }
    public function jsonData($code, $message, $data = []){
        return $this->jsonResponse($code, $message, $data);
    }
}