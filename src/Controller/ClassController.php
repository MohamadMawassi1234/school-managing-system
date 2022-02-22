<?php
namespace App\Controller;

use App\Entity\Classes;
// use App\Entity\Student;
// use App\Entity\Course;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;

class ClassController extends AbstractController {
    /**
     * @Route("/classes", name="class_list")
     * @Method({"GET"})
     */
    public function listClasses(Request $request, PaginatorInterface $paginator) {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
        $classes = $paginator->paginate(
            $classes,
            $request->query->getInt("page", 1),
            5,
        );
        return $this->render("classes/list.html.twig", array("classes" => $classes));
    }

    /**
     * @Route("/classes/filter", name="class_filter")
     */
    public function filterClasses() {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
        $searchQuery = "";
        $filteredClasses = [];

        if (isset($_POST['class_search_submit'])) {
            $searchQuery = $_POST['class_search_query'];
            for ($i=0; $i<count($classes); $i++) {
                if (str_contains(strtolower($classes[$i]->getName()), strtolower($searchQuery))) {
                    array_push($filteredClasses, $classes[$i]->getName());
                }
            }
        }
        return $this->render("classes/filter.html.twig", array("filteredClasses" => $filteredClasses));
    }

    /**
     * @Route("/class/add", name="add_class")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $class = new Classes();

        $form = $this->createFormBuilder($class)
            ->add("name", TextType::class, array('label' => "Class Name:", "attr" => array('class' => "form-control")))
            ->add("section", TextType::class, array('label' => "Section:", "attr" => array('class' => "form-control")))
            ->add('Add', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $class = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($class);
            $entityManager->flush();

            return $this->redirectToRoute('class_list');
        }

        return $this->render('classes/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/class/edit/{id}", name="edit_class")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $class = new Classes();
        $class= $this->getDoctrine()->getRepository(Classes::class)->find($id);

        $form = $this->createFormBuilder($class)
            ->add("name", TextType::class, array('label' => "Class Name:", "attr" => array('class' => "form-control")))
            ->add("section", TextType::class, array('label' => "Section:", "attr" => array('class' => "form-control")))
            ->add('Update', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('class_list');
        }

        return $this->render('classes/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/class/{id}", name="class_details")
     */
    public function details($id) {
        $class= $this->getDoctrine()->getRepository(Classes::class)->find($id);
        return $this->render("classes/details.html.twig", array("class" => $class)); 
     }

    /**
     * @Route("/class/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete($id) {
        $class= $this->getDoctrine()->getRepository(Classes::class)->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($class);
        $entityManager->flush();

        return $this->redirectToRoute('class_list');
     }
}