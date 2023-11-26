<?php

namespace Moltaqa\Wasl;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

    public static function registerDriverAndVehicle($driverData = [], $vehicleData = [])
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

            # If Failed
            if ($response->getStatusCode() != 200) {
                throw new WaslRegistrationFailedException('Error: Unexpected status code - ', $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception  $e) {
            Log::critical($e->getMessage());
            throw new Exception('Failed to register driver and vehicle WASL API');
        }
    }

    public static function waslCheckEligibility(mixed $identityNumbers)
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

            switch ($response->getStatusCode()) {
                case 400:
                    throw new WaslRegistrationFailedException('Error: Unexpected status code - ', $response->getStatusCode());
                    break;
                case 500:
                    throw new WaslRegistrationFailedException('Error: Unexpected status code - ', $response->getStatusCode());
                    break;
                default:
                    break;
            }

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception  $e) {
            Log::critical($e->getMessage());
            throw new Exception('Failed to check driver eligibility WASL API');
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
}
