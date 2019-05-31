<?php

namespace App\Common\Auth;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\ValidationData;

/**
 * 单例 一次请求中所有出现使用jwt的地方都是一个用户
 * Class JwtAuth
 * @package App\Common\Auth
 */
class JwtAuth
{
    private static $instance = null;
    private $token;
    private $iss = 'homestead.app';
    private $aud = 'imooc_server_app';
    private $uid;
    private $secret = '*#06#asdxzhj';
    private $decodeToken;

    private function __construct()
    {

    }
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self;
        }
        return self::$instance;
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    public function getToken(){
        return (string)$this->token;
    }
    public function setToken($token){
        $this->token = $token;
        return $this;
    }
    public function encode()
    {
        $this->token = (new Builder())->withHeader('alg', 'HS256')
        ->issuedBy($this->iss)
        ->permittedFor($this->aud)
        ->issuedAt(time())
        ->expiresAt(time() + 3600)
        ->withClaim('uid', $this->uid)
        ->getToken(new Sha256(), new Key($this->secret));
        return $this;
    }
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }
    public function getUid(){
        return $this->uid;
    }
    public function decode(){
        if(!$this->decodeToken){
            $this->decodeToken = (new Parser())->parse((string)$this->token);
            $this->uid = $this->decodeToken->getClaim('uid');
        }
        return $this->decodeToken;
    }
    public function validate()
    {
        $data = new ValidationData();
        $data->setIssuer($this->iss);
        $data->setAudience($this->aud);
        return $this->decode()->validate($data);
    }
    public function verify()
    {
        $result = $this->decode()->verify(new Sha256(), $this->secret);
        return $result;
    }
}