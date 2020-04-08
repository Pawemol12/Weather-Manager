<?php

namespace App\Controller;

use App\Enum\AlertsEnum;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\City;
use App\Form\CityType;
use App\Enum\UserRolesEnum;
use App\Enum\PagesEnum;

class CitiesController extends AbstractController
{
    /**
     * @Route("/cities", name="cities")
     */
    public function index(CityRepository $cityRepository)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        $cities = $cityRepository->findAll();

        return $this->render('cities/index.html.twig', [
            'cities' => $cities,
            'page' => PagesEnum::CITIES_PAGE
        ]);
    }

    /**
     * @Route("/cities/add", name="cities_add")
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function addCity(Request $request, CityRepository $cityRepository, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        $newCityForm = $this->createCityForm();
        $newCityForm->handleRequest($request);

        if (!$newCityForm->isSubmitted()) {
            return $this->render('cities/cityFormModal.html.twig', [
                'form' => $newCityForm->createView(),
                'title' => $translator->trans('city.addTitle')
            ]);
        }

        if (!$newCityForm->isValid()) {
            return $this->render('cities/cityForm.html.twig', [
                'form' => $newCityForm->createView()
            ]);
        }

        /**
         * @var City
         */
        $city = $newCityForm->getData();
        $alerts = [];
        try {
            $cityRepository->save($city);
            $alerts[] = [
                'type' => AlertsEnum::ALERT_TYPE_SUCCESS,
                'message' => $translator->trans('city.saveSuccess')
            ];
        } catch (Exception $ex) {
            $alerts[] = [
                'type' => AlertsEnum::ALERT_TYPE_ERROR,
                'message' => $translator->trans('city.saveFail', [
                    '{{ message }}' => $ex->getMessage()
                ])
            ];
        }

        return $this->createCitiesTable($cityRepository, $alerts);
    }

    /**
     * @Route("/cities/edit/{cityId}", name="cities_edit", requirements={"cityId"="\d+"})
     * @param int $cityId
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function editCity(int $cityId, Request $request, CityRepository $cityRepository, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        /**
         * @var City
         */
        $city = $cityRepository->findOneById($cityId);
        if (!$city) {
            $this->json([
                'type' => 'alert',
                'alert_type' => AlertsEnum::ALERT_TYPE_ERROR,
                'message' => $translator->trans('city.notExist')
            ]);
        }

        $cityEditForm = $this->createCityForm($city, $this->generateUrl('cities_edit', [
            'cityId' => $cityId
        ]));

        $cityEditForm->handleRequest($request);

        if (!$cityEditForm->isSubmitted()) {
            return $this->render('cities/cityFormModal.html.twig', [
                'form' => $cityEditForm->createView(),
                'title' => $translator->trans('city.editTitle', [
                    '{{ name }}' => $city->getName()
                ])
            ]);
        }

        if (!$cityEditForm->isValid()) {
            return $this->render('cities/cityForm.html.twig', [
                'form' => $cityEditForm->createView()
            ]);
        }

        $city = $cityEditForm->getData();
        $alerts = [];
        try {
            $cityRepository->save($city);
            $alerts[] = [
                'type' => AlertsEnum::ALERT_TYPE_SUCCESS,
                'message' => $translator->trans('city.saveSuccess')
            ];
        } catch (Exception $ex) {
            $alerts[] = [
                'type' => AlertsEnum::ALERT_TYPE_ERROR,
                'message' => $translator->trans('city.saveFail', [
                    '{{ message }}' => $ex->getMessage()
                ])
            ];
        }

        return $this->createCitiesTable($cityRepository, $alerts);
    }

    /**
     * @Route("/cities/delete/{cityId}", name="cities_delete", requirements={"cityId"="\d+"})
     * @param int $cityId
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function deleteCity(int $cityId, Request $request, CityRepository $cityRepository, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        /**
         * @var City
         */
        $city = $cityRepository->findOneById($cityId);
        if (!$city) {
            $this->json([
                'type' => 'alert',
                'alert_type' => AlertsEnum::ALERT_TYPE_ERROR,
                'message' => $translator->trans('city.notExist')
            ]);
        }

        if ($request->query->getInt('delete') == 1) {

            $cityRepository->delete($city);
            return $this->createCitiesTable($cityRepository, [
                [
                    'type' => AlertsEnum::ALERT_TYPE_SUCCESS,
                    'message' => $translator->trans('city.successfullyDeleted')
                ]
            ]);
        }

        return $this->render('snippets/yesNoModal.html.twig', [
            'ModalId' => 'CityDeleteModal',
            'title' => $translator->trans('city.deleteTitle', ['{{ name }}' => $city->getName()]),
            'text' => $translator->trans('city.deleteQuestion'),
            'actionUrl' => $this->generateUrl('cities_delete', ['cityId' => $cityId, 'delete' => 1])
        ]);
    }

    /**
     * @param City|null $city
     * @param string $actionUrl
     * @return FormInterface
     */
    private function createCityForm(City $city = null, string $actionUrl = '')
    {
        if (empty($actionUrl)) {
            $actionUrl = $this->generateUrl('cities_add');
        }

        return $this->createForm(CityType::class, $city, [
            'action' => $actionUrl
        ]);
    }

    /**
     * @param CityRepository $cityRepository
     * @param array $alerts
     * @return Response
     */
    private function createCitiesTable(CityRepository $cityRepository, array $alerts=[])
    {
        $cities = $cityRepository->findAll();

        return $this->render('cities/citiesTableWrapper.html.twig', [
            'cities' => $cities,
            'alerts' => $alerts
        ]);
    }
}
