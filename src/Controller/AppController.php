<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController {
    /**
     * @Route("/")
     */
    public function index() {
        return $this->render("base.html.twig");
    }
}