<?php


namespace App\Services\Opretail;

use Illuminate\Support\Facades\Http;
use App\Services\Opretail\OpretailHelpers as OpretailHelpers;

class OpretailApi
{
    use OpretailHelpers;
    protected String $host = 'http://openapi.opretail.com/m.api';
    protected String $username = 'Eyez-app';
    protected String $password = 'Re040491!!';
    protected String $token = '';
    protected String $secret = '47K0vzVtNArvOIRBFoYhyeUubYn3xcln';
    protected array $config = [
        "_sm" => 'md5',
        "_version" => 'v1',
    ];

    public function __construct(
        public string $dateFrom,
        public string $dateTo,
        public int $storeId,
        public string $reportType,
        $summary = false)
    {
        \Log::info('Date range',['dateFrom' => $this->dateFrom, 'dateTo' => $this->dateTo]);

        $this->getToken();

        if (!$summary) {
            $this->getWalkInCount();
            $this->getAgeGenderData();
            $this->getStoreData();
        }
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

        return (object)$this->_sig($this->secret, $ret);
    }

    /**
     * @param $methodName
     * @param array $params
     * @return object
     */
    function getRqParams($methodName, $params = [], $requestMode = 'POST')
    {
        $new_params = [
            "_aid" => 'S107',
            "_akey" => 'S107-00000301',
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
    function getToken()
    {
        $rqParams = $this->getRqParams('open.shopweb.security.mobileLogin', [
            'userName' => $this->username,
            'password' => $this->password,
        ], 'POST');

        $url = $this->host . '?' . http_build_query((array)$rqParams);

        $response = Http::accept('application/x-www-form-urlencoded')
            ->get($url);

        if ($response->successful()) {
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
    public function getWalkInCount()
    {
        $data = [
            "depId" => $this->storeId,
            "startTime" => $this->dateFrom,
            "endTime" => $this->dateTo
        ];

        $params = $this->getRqParams('open.shopweb.passengerFlow.getPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
                        ->accept('application/x-www-form-urlencoded')
                        ->get($this->host . '?' . http_build_query((array)$params));


        if ($response->successful()) {
            $this->walkInCount = $response->json('data.passengerFlow');

            return $response->json();
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
            "startTime" => $this->dateFrom,
            "endTime" => $this->dateTo,
            "timeType" => 1,
        ];

        $params = $this->getRqParams('open.passengerflow.getManyShopsPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {
            $storeData = $response->json('data');
            $this->hourlyWalkIn = $this->mapHourlyWalkIn($storeData[0]['dataList'], $this->reportType);
            \Log::info('Store Data', ['response' => $this->hourlyWalkIn]);

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
            "stime" => $this->dateFrom,
            "etime" => $this->dateTo,
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
            "enterpriseId" => 1689,
            "orgId" => 59,
            "id" => "S_{$this->storeId}",
            "stime" => $this->dateFrom,
            "etime" => $this->dateTo,
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
