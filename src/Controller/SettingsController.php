<?php

namespace App\Controller;

use App\Entity\ApiSettings;
use App\Enum\AlertsEnum;
use App\Enum\ApiSettingsEnum;
use App\Enum\UserRolesEnum;
use App\Form\SettingsForm;
use App\Repository\ApiSettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Enum\PagesEnum;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     * @param ApiSettingsRepository $apiSettingsRepository
     * @return Response
     */
    public function index(ApiSettingsRepository $apiSettingsRepository)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        $settingsForm = $this->createSettingsForm($apiSettingsRepository);

        return $this->render('settings/index.html.twig', [
            'settingsForm' => $settingsForm->createView(),
            'page' => PagesEnum::SETTINGS_PAGE
        ]);
    }

    /**
     * @Route("/settings/save", name="settings_save")
     * @param Request $request
     * @param ApiSettingsRepository $apiSettingsRepository
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function saveSettings(Request $request, ApiSettingsRepository $apiSettingsRepository, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted(UserRolesEnum::USER_ROLE_MOD);

        $settingsForm = $this->createSettingsForm($apiSettingsRepository);
        $settingsForm->handleRequest($request);

        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {

            try {
                $this->saveSettingsForm($settingsForm->getData(), $apiSettingsRepository);
                $this->addFlash(AlertsEnum::ALERT_FLASH_SUCCESS, $translator->trans('settings.saveSuccess'));
            } catch (\Exception $ex) {
                $this->addFlash(AlertsEnum::ALERT_FLASH_ERROR, $translator->trans('settings.saveError', [
                    '{{ error }}' => $ex->getMessage()
                ]));
            }

            return $this->redirectToRoute('settings');
        }

        return $this->render('settings/index.html.twig', [
            'settingsForm' => $settingsForm->createView(),
        ]);
    }

    /**
     * @param ApiSettingsRepository $apiSettingsRepository
     * @return FormInterface
     */
    private function createSettingsForm(ApiSettingsRepository $apiSettingsRepository)
    {
        $apiKey = $apiSettingsRepository->findOneByName(ApiSettingsEnum::API_KEY);
        $weatherInfoByCityAndStateLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_AND_STATE_LINK);
        $weatherInfoByCityIdLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_ID_LINK);
        $weatherInfoByCityLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_LINK);

        return $this->createForm(SettingsForm::class, [], [
            'action' => $this->generateUrl('settings_save'),
            'data' => [
                'apiKey' => $apiKey ? $apiKey->getValue() : '',
                'weatherInfoByCityAndStateLink' => $weatherInfoByCityAndStateLink ? $weatherInfoByCityAndStateLink->getValue() : '',
                'weatherInfoByCityIdLink' => $weatherInfoByCityIdLink ? $weatherInfoByCityIdLink->getValue() : '',
                'weatherInfoByCityLink' => $weatherInfoByCityLink ? $weatherInfoByCityLink->getValue() : ''
            ]
        ]);
    }

    /**
     * @param array $formData
     * @param ApiSettingsRepository $apiSettingsRepository
     */
    private function saveSettingsForm(array $formData, ApiSettingsRepository $apiSettingsRepository)
    {
        $apiKey = $apiSettingsRepository->findOneByName(ApiSettingsEnum::API_KEY);
        if (!$apiKey) {
            $apiKey = new ApiSettings();
            $apiKey->setName(ApiSettingsEnum::API_KEY);
        }

        $weatherInfoByCityAndStateLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_AND_STATE_LINK);
        if (!$weatherInfoByCityAndStateLink) {
            $weatherInfoByCityAndStateLink = new ApiSettings();
            $weatherInfoByCityAndStateLink->setName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_AND_STATE_LINK);
        }

        $weatherInfoByCityIdLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_ID_LINK);
        if (!$weatherInfoByCityIdLink) {
            $weatherInfoByCityIdLink = new ApiSettings();
            $weatherInfoByCityIdLink->setName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_ID_LINK);
        }

        $weatherInfoByCityLink = $apiSettingsRepository->findOneByName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_LINK);
        if (!$weatherInfoByCityLink) {
            $weatherInfoByCityLink = new ApiSettings();
            $weatherInfoByCityLink->setName(ApiSettingsEnum::WEATHER_INFO_BY_CITY_LINK);
        }

        $apiKey->setValue($formData['apiKey']);
        $weatherInfoByCityAndStateLink->setValue($formData['weatherInfoByCityAndStateLink']);
        $weatherInfoByCityIdLink->setValue($formData['weatherInfoByCityIdLink']);
        $weatherInfoByCityLink->setValue($formData['weatherInfoByCityLink']);

        $apiSettingsRepository->saveList([
            $apiKey, $weatherInfoByCityAndStateLink, $weatherInfoByCityIdLink, $weatherInfoByCityLink
        ]);
    }
}
