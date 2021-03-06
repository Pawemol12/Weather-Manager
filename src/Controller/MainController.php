<?php

namespace App\Controller;

use App\Enum\AlertsEnum;
use App\Exception\ApiCurlRequestException;
use App\Exception\ApiNotConfiguredException;
use App\Exception\ApiResponseException;
use DateTime;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CityRepository;
use App\Service\WeatherApiService;
use App\Form\WeatherShowForm;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Głowny kontroller, strona główna
 *
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class MainController extends AbstractController {

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        $weatherShowForm = $this->createWeatherShowForm();

        return $this->render('index.html.twig', [
            'weatherShowForm' => $weatherShowForm->createView()
        ]);
    }

    /**
     * @Route("/weather/show", name="show_weather")
     * @param Request $request
     * @param WeatherApiService $weatherApiService
     * @param CityRepository $cityRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws Exception
     */
    public function showWeather(Request $request, WeatherApiService $weatherApiService, CityRepository $cityRepository, TranslatorInterface $translator) {
        $weatherShowForm = $this->createWeatherShowForm();
        $weatherShowForm->handleRequest($request);

        $alerts = [];
        $cityWeatherData = [];

        if ($weatherShowForm->isSubmitted() && $weatherShowForm->isValid()) {
            $city = $weatherShowForm->get('city')->getData();

            $error = false;
            try {
                $cityWeatherData = $weatherApiService->getWeatherData($city);
                $city->setApiCityId($cityWeatherData['id']);
                $city->setLastWeatherData($cityWeatherData);

                $cityWeatherData['date'] = new DateTime();

                $city->setLastWeatherDataUpdate($cityWeatherData['date']);

                $cityRepository->save($city);
            } catch (ApiCurlRequestException $apiCurlRequestException) {
                $alerts[] = [
                    'type' => AlertsEnum::ALERT_TYPE_ERROR,
                    'message' => $translator->trans('errors.apiUrlRequestError')
                ];
                $error = true;
            } catch (ApiNotConfiguredException $apiNotConfiguredException) {
                $alerts[] = [
                    'type' => AlertsEnum::ALERT_TYPE_ERROR,
                    'message' => $translator->trans('errors.apiKeyNotConfigured')
                ];
                $error = true;
            } catch (ApiResponseException $apiResponseException) {
                $alerts[] = [
                    'type' => AlertsEnum::ALERT_TYPE_ERROR,
                    'message' => $translator->trans('errors.apiResponseError', [
                        '{{ errorCode }}' => $apiResponseException->getCode(),
                        '{{ message }}' => $apiResponseException->getMessage()
                    ])
                ];
                $error = true;
            }

            if ($error) {
                $cityWeatherData = $city->getLastWeatherData();
                if ($cityWeatherData) {
                    $cityWeatherData['date'] = $city->getLastWeatherDataUpdate();
                }
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('main/weatherShowPanel.html.twig', [
                'cityWeatherData' => $cityWeatherData,
                'alerts' => $alerts,
                'ajax' => true
            ]);
        }

        return $this->render('index.html.twig', [
            'weatherShowForm' => $weatherShowForm->createView(),
            'cityWeatherData' => $cityWeatherData,
            'alerts' => $alerts
        ]);
    }

    /**
     * @return FormInterface
     */
    private function createWeatherShowForm()
    {
        return $this->createForm(WeatherShowForm::class, [], [
            'action' => $this->generateUrl('show_weather')
        ]);
    }

}
