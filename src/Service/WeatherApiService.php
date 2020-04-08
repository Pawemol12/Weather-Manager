<?php


namespace App\Service;
use App\Entity\City;
use App\Enum\ApiErrorCodesEnum;
use App\Enum\ApiSettingsEnum;
use App\Exception\ApiCurlRequestException;
use App\Exception\ApiNotConfiguredException;
use App\Exception\ApiResponseException;
use App\Repository\ApiSettingsRepository;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class WeatherApiService
{

    /**
     * @var ApiSettingsRepository
     */
    private $apiSettingsRepository;

    /**
     * WeatherApiService constructor.
     * @param ApiSettingsRepository $apiSettingsRepository
     */
    public function __construct(ApiSettingsRepository $apiSettingsRepository)
    {
        $this->apiSettingsRepository = $apiSettingsRepository;
    }

    /**
     * @param City $city
     * @return mixed
     * @throws ApiCurlRequestException
     * @throws ApiNotConfiguredException
     * @throws ApiResponseException
     */
    public function getWeatherData(City $city)
    {
        $apiKey = $this->apiSettingsRepository->findOneByName(ApiSettingsEnum::API_KEY);
        if (!$apiKey) {
            throw new ApiNotConfiguredException('API KEY is not configured in database!');
        }


        $cityId = $city->getApiCityId();
        $cityName = $city->getName();
        $cityState = $city->getState();

        if ($cityId) {
            $url = $this->apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_ID_LINK);
            if (!$url) {
                throw new ApiNotConfiguredException('WEATHER INFO BY CITY ID LINK is not configured in database!');
            }

            $urlValue = str_replace('{city id}', $cityId, $url->getValue());
        } else if ($cityName && $cityState) {
            $url = $this->apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_AND_STATE_LINK);
            if (!$url) {
                throw new ApiNotConfiguredException('WEATHER INFO BY CITY NAME AND STATE LINK is not configured in database!');
            }

            $urlValue = str_replace(['{city name}', '{state}'], [$cityName, $cityState], $url->getValue());
        } else {
            $url = $this->apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_LINK);
            if (!$url) {
                throw new ApiNotConfiguredException('WEATHER INFO BY CITY NAME LINK is not configured in database!');
            }
            $urlValue = str_replace('{city name}', $cityName, $url->getValue());
        }

        $urlValue = str_replace('{api Key}', $apiKey->getValue(), $urlValue);

        $ch = curl_init($urlValue);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        if($curlError) {
            curl_close($ch);
            throw new ApiCurlRequestException($curlError);
        }

        $decodedResponse = json_decode($response, true);

        if (!empty($decodedResponse['cod']) && $decodedResponse['cod'] != ApiErrorCodesEnum::RESPONSE_OK) {
            curl_close($ch);
            throw new ApiResponseException($decodedResponse['message'], $decodedResponse['cod']);
        }

        curl_close($ch);

        return $decodedResponse;
    }

}