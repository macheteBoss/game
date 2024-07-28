<?php

namespace App\Controller;

use App\DependencyInjection\Traits\ProcessGameInjectionTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    use ProcessGameInjectionTrait;

    /**
     * @Route("/")
     */
    public function index(Request $request): Response
    {
        $result = [];

        if ($request->query->has('process')) {
            $result = $this->getProcessGameService()->execute();
        }

        return $this->render('Main/index.html.twig', [
            'result' => $result,
        ]);
    }
}