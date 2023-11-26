<?php

namespace Moltaqa\Wasl;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;

class Wasl
{
    private static Client $client;
    protected static $instance;

    public function __construct()
    {
        $configFilePath = config_path('wasl.php');
        if (!File::exists($configFilePath)) {
            throw new WaslMissingConfigException('Publish the package config file');
        }
        self::$client = new Client([
            'base_uri' => config('wasl.WASL_BASE_URL'),
            'timeout' => 2.0,
            'verify' => true,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'client-id' => config('wasl.WASL_CLIENT_ID'),
                'app-id' => config('wasl.WASL_APP_ID'),
                'app-key' => config('wasl.WASL_APP_KEY'),
            ],
            'curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2], # Set TLS version to 1.2
        ]);
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param array $driverData driver personal data
     * @param array $vehicleData driver vehicle data
     * @return JsonResponse
     * @throws WaslMissingDataException
     * @throws GuzzleException
     */
    public static function registerDriverAndVehicle(array $driverData = [], array $vehicleData = []): JsonResponse
    {
        try {
            $data = [];

            if (empty($driverData) && empty($vehicleData)) {
                throw new WaslMissingDataException('please provide driver or vehicle data');
            }

            if (empty($driverData) && !empty($vehicleData)) {
                $data = $vehicleData;
            }

            if (empty($vehicleData) && !empty($driverData)) {
                $data = $driverData;
            }

            if ($driverData && $vehicleData) {
                $data = array_merge($driverData, $vehicleData);
            }

            # Format Driver Birthdate
            if (isset($data['driver']['dateOfBirthGregorian'])) {
                $dateOfBirthGregorian = $data['driver']['dateOfBirthGregorian'];
                $data['driver']['dateOfBirthGregorian'] = date('Y-m-d', strtotime($dateOfBirthGregorian));
            }

            $response = self::$client->post(config('wasl.WASL_REGISTER_DRIVER_AND_VEHICLE_ENDPOINT'), [
                'json' => $data,
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return response()->json($responseData);
            }
            else{
                $statusCode = $response->getStatusCode();
                return response()->json(['error' => "Received a non-200 status code: $statusCode"], $statusCode);
            }
        } catch (RequestException  $e) {
            return self::handelException($e);
        }
    }

    /**
     * @param mixed $identityNumbers maybe 1 id for single drive ror array of ids as values
     * @return JsonResponse
     * @throws GuzzleException
     */
    public static function driverCheckEligibility(mixed $identityNumbers): JsonResponse
    {
        try {
            $asPost = false;
            if (is_array($identityNumbers))
                $asPost = true;

            if ($asPost) {
                $driverIds = array_map(function ($id) {
                    return ['id' => $id];
                }, $identityNumbers);

                $response = self::$client->post(config('wasl.WASL_CHECK_DRIVER_ELIGIBLIITY_ENDPOINT'), [
                    'json' => $driverIds,
                ]);
            } else {
                $response = self::$client->get(config('wasl.WASL_CHECK_DRIVER_ELIGIBLIITY_ENDPOINT') . '/' . urlencode($identityNumbers));
            }

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return response()->json($responseData);
            }
            else{
                $statusCode = $response->getStatusCode();
                return response()->json(['error' => "Received a non-200 status code: $statusCode"], $statusCode);
            }
        } catch (RequestException  $e) {
            return self::handelException($e);
        }
    }

    public function getVehiclePlateLetters(): array{
        return [
            'ا',
            'ب',
            'ح',
            'د',
            'ر',
            'س',
            'ص',
            'ط',
            'ع',
            'ق',
            'ك',
            'ل',
            'م',
            'ن',
            'هـ',
            'و',
            'ى',
        ];
    }

    /**
     * @param RequestException|Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public static function handelException(RequestException|Exception $e): \Illuminate\Http\JsonResponse
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $reasonPhrase = $response->getReasonPhrase();
            $body = $response->getBody();
            return response()->json(['error' => "Request failed with status code $statusCode: $reasonPhrase", 'body' => $body], $statusCode);
        } else {
            return response()->json(['error' => 'Request failed without a response.' . $e->getMessage()], 500);
        }
    }
}
