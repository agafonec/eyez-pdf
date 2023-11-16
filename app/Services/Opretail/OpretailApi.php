<?php


namespace App\Services\Opretail;

use App\Models\Opretail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\Opretail\OpretailHelpers as OpretailHelpers;

class OpretailApi
{
    use OpretailHelpers;
    protected String $host = 'http://openapi.opretail.com/m.api';
    protected String $token = '';
    protected array $config = [
        "_sm" => 'md5',
        "_version" => 'v1',
    ];

    public function __construct(private Opretail $opretailCredentials)
    {
        $this->generateToken($opretailCredentials);
    }

    public function user()
    {
        return auth()->user() ?? request()->user() ?? null;
    }

    public function getReport($store, $dateFrom, $dateTo, $reportType)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->storeId = $store->dep_id;
        $this->reportType = $reportType;

        $this->walkInCount = $this->getWalkInCount();
        $this->getAgeGenderData();
        $this->getStoreData();

        return $this;
    }

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
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Retrieve opretail token
     */
    function generateToken($credentials)
    {
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
            return $response->json();
        } else {
            \Log::info('Login opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getWalkInCount($dateFrom = null, $dateTo = null)
    {
        $data = [
            "depId" => $this->storeId,
            "startTime" => date('Y-m-d H:i:s', strtotime($dateFrom ?? $this->dateFrom)),
            "endTime" => date('Y-m-d H:i:s', strtotime($dateTo ?? $this->dateTo))
        ];


        $params = $this->getRqParams('open.shopweb.passengerFlow.getPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
                        ->accept('application/x-www-form-urlencoded')
                        ->get($this->host . '?' . http_build_query((array)$params));


        if ($response->successful()) {

            return $response->json('data.passengerFlow');
        } else {
            \Log::info('Opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    public function getStores()
    {
        $params = $this->getRqParams('open.shopweb.departments.getDeptListByPage', [], "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {

            return $response->json('data.records');
        } else {
            \Log::info('Opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getStoreData()
    {
        $data = [
            "id" => "S_{$this->storeId}",
            "startTime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "endTime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "timeType" => 1,
        ];

        $params = $this->getRqParams('open.passengerflow.getManyShopsPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {
            $storeData = $response->json('data');
            $this->hourlyWalkIn = $this->mapHourlyWalkIn($storeData[0]['dataList'], $this->reportType);

            return $response->json();
        } else {
            \Log::info('Opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    public function getAgeGenderData()
    {
        $data = [
            "id" => "S_{$this->storeId}",
            "stime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "etime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "startHour" => 1,
            "endHour" => 23
        ];
        // alternative data
        // $params = $this->getRqParams('open.shopweb.passengerFlow.flowGroup.getFlowGroupDistribution', $data, "POST");

        $params = $this->getRqParams('open.shopweb.passengerFlow.flowGroup.getAgeGroupAndGenderDistribution', $data, "GET");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));


        if ($response->successful()) {
            \Log::info('Map gender', ['data' => $data, 'response' => $response->json()]);
            $this->genderData = $this->mapGender($response->json('data.genderDistribution'));
            $this->ageData = $this->mapAge($response->json('data.ageDistribution'));

            return $response->json();
        } else {
            \Log::info('Opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    public function salesByDay()
    {
        $data = [
            "enterpriseId" => $this->opretailCredentials->enterpriseId,
            "orgId" => $this->opretailCredentials->orgId,
            "id" => "S_{$this->storeId}",
            "stime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "etime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "startHour" => 1,
            "endHour" => 23
        ];

        $params = $this->getRqParams('open.ovopark.pos.reportSales', $data, "GET");


        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));


        if ($response->successful()) {
            \Log::info('successfull opretail response', ['opretail' => $response->json()] );
            return $response->json();
        } else {
            \Log::info('Opretail error', ['error' => $response->status()] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }


    public function sendOrder($order = []) {
        $opretail_json = [];
//        &enterpriseId={$res->enterpriseId}&orgId={$res->orgId}
//        "enterpriseId" => 1689,
//            "orgId" => 59,
        $params = $this->getRqParams('open.ovopark.pos.sendOrder', $opretail_json, 'POST');

        $response = Http::post($this->host, $params);

        // Check for a successful response and handle the result
        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }
}
