<?php
namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class AppController extends AbstractController {
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="home")
     */
    public function index() {
        if (!$this->security->getUser()) {
            return $this->render("security/loginorregister.html.twig");
        } 
        if ($this->security->isGranted("ROLE_STUDENT")) {
            $student = $this->security->getUser();
            return $this->render("base.html.twig", ["student" => $student]);
        }
        return $this->render("base.html.twig");
    }

    /**
     * @Route("/accessdenied", name="access_denied")
     */
    public function accessDenied() {
        return new Response($this->renderView("security/accessdenied.html.twig"), 403);
    }
}