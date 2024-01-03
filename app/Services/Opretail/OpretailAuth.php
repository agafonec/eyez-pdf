<?php


namespace App\Services\Opretail;


use Illuminate\Support\Facades\Http;

trait OpretailAuth
{
    protected String $host = 'http://openapi.opretail.com/m.api';
    protected String $token = '';
    protected array $config = [
        "_sm" => 'md5',
        "_version" => 'v1',
    ];

    /**
     * @param $secret
     * @param $ret
     * @return mixed
     */
    function _sig($secret, $ret)
    {
        $signValue = '';
        $keyArr = array_keys($ret);
        sort($keyArr);

        foreach ($keyArr as $item) {
            $signValue .= $item . $ret[$item];
        }

        $signValue = $secret . $signValue . $secret;
        $ret['_sig'] = strtoupper(md5($signValue));

        return $ret;
    }

    /**
     * @param $methodName
     * @param array $params
     * @param string $requestMode
     * @return object
     */
    public function paramsExtend($methodName, $params = [], $requestMode = 'get')
    {
        // TODO here will be the date and time of the order.
        // $params['iposJson']['ticketTimeStr']
        $curDate = date("YmdHis");
        $extendParams = [
            "_mt" => $methodName,
            "_timestamp" => $curDate,
            "_requestMode" => $requestMode,
        ];

        $ret = array_merge($this->config, $extendParams, $params);

        return (object)$this->_sig($this->opretailCredentials->secret_key, $ret);
    }

    /**
     * @param $methodName
     * @param array $params
     * @return object
     */
    function getRqParams($methodName, $params = [], $requestMode = 'POST')
    {
        $new_params = [
            "_aid" => $this->opretailCredentials->_aid,
            "_akey" => $this->opretailCredentials->_akey,
            "_format" => 'json',
            "_requestMode" => $requestMode,
        ];

        if ( isset($params['iposJson']) ) {
            $new_params['iposJson'] = json_encode($params['iposJson']);
        } else {
            $new_params = array_merge($new_params, $params);
        }

        $res = $this->paramsExtend($methodName, $new_params, 'get');

        $res->_mt = $methodName;

        return $res;
    }

    /**
     * Retrieve opretail token
     */
    function generateToken($credentials)
    {
        if ($token = $this->opretailCredentials->cached('token')) {
            return $this->token = $token;
        }

        $rqParams = $this->getRqParams('open.shopweb.security.mobileLogin', [
            'userName' => $credentials->username,
            'password' => $credentials->password,
        ], 'POST');

        $url = $this->host . '?' . http_build_query((array)$rqParams);

        $response = Http::accept('application/x-www-form-urlencoded')->get($url);

        if ($response->successful()) {
            if( empty($response->json('data'))) {
                $status = $response->json('stat');
                \Log::info('opretail.stores:: ERROR THROWN', ['error' => $status]);

                $this->token = 'error';
                return ['error' => "Empty data returned. err name {$status['codename']}"];
            }

            $this->token = $response->json('data.token');
            $this->opretailCredentials->cache('token', $this->token, 60);
            return $this->token;
        } else {
            \Log::info('Login opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }
}
