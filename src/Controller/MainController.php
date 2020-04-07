<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * Głowny kontroller, strona główna
 *
 * @author Pawemol
 */
class MainController extends AbstractController {
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, AuthorizationCheckerInterface $authorizationChecker) {

        return $this->render('index.html.twig');
    }

}
