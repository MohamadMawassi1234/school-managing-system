<?php
namespace App\Controller;

use App\Entity\Course;
use App\Entity\Classes;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;

class CourseController extends AbstractController {
    /**
     * @Route("/courses", name="course_list")
     * @Method({"GET"})
     */
    public function listCourses(Request $request, PaginatorInterface $paginator) {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        $courses = $paginator->paginate(
            $courses,
            $request->query->getInt("page", 1),
            5,
        );
        return $this->render("courses/list.html.twig", array("courses" => $courses, "student" => ""));
    }

    /**
     * @Route("/courses/filter", name="course_filter")
     */
    public function filterCourses() {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        $searchQuery = "";
        $filteredCourses = [];

        if (isset($_POST['course_search_submit'])) {
            $searchQuery = $_POST['course_search_query'];
            for ($i=0; $i<count($courses); $i++) {
                if (str_contains(strtolower($courses[$i]->getName()), strtolower($searchQuery))) {
                    array_push($filteredCourses, $courses[$i]->getName());
                }
            }
        }
        return $this->render("courses/filter.html.twig", array("filteredCourses" => $filteredCourses, "student" => ""));
    }

    /**
     * @Route("/course/add", name="add_course")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function new(Request $request) {
        $course = new Course();

        $form = $this->createFormBuilder($course)
            ->add("name", TextType::class, array('label' => "Course Name:", "attr" => array('class' => "form-control")))
            ->add("description", TextareaType::class, array('label' => "Description:", "attr" => array('class' => "form-control")))
            ->add('class', EntityType::class, [
                "attr" => array('class' => "form-select"),
                'label' => "Class: ",
                'class' => Classes::class,
                'choice_label' => 'name',
            ])
            ->add('Add', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $course = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('course_list');
        }

        return $this->render('courses/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/course/edit/{id}", name="edit_course")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function edit(Request $request, $id) {
        $course = new Course();
        $course= $this->getDoctrine()->getRepository(Course::class)->find($id);

        $form = $this->createFormBuilder($course)
            ->add("name", TextType::class, array('label' => "Course Name:", "attr" => array('class' => "form-control")))
            ->add("description", TextareaType::class, array('label' => "Description:", "attr" => array('class' => "form-control")))
            ->add('class', EntityType::class, [
                "attr" => array('class' => "form-select"),
                'label' => "Class: ",
                'class' => Classes::class,
                'choice_label' => 'name',
            ])
            ->add('Update', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('course_list');
        }

        return $this->render('courses/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/course/{id}", name="course_details")
     */
    public function details($id) {
        $course= $this->getDoctrine()->getRepository(Course::class)->find($id);
        return $this->render("courses/details.html.twig", array("course" => $course, "student" => "")); 
     }

     /**
     * @Route("/course/delete/{id}", name="delete_course")
     * @Method({"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id) {
        $course= $this->getDoctrine()->getRepository(Course::class)->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->redirectToRoute('course_list');
     }
}