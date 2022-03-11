<?php
namespace App\Controller;

use App\Entity\Student;
use App\CustomEvents\ImageResizerEvent;
use App\Entity\Classes;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Knp\Component\Pager\PaginatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class StudentController extends AbstractController {
    /**
     * @Route("/students", name="student_list")
     * @Method({"GET"})
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function listStudents(Request $request, PaginatorInterface $paginator) {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $students = $paginator->paginate(
            $students,
            $request->query->getInt("page", 1),
            5,
        );
        return $this->render("students/list.html.twig", array("students" => $students));
    }

    /**
     * @Route("/students/filterbyname", name="student_filter")
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function filterStudents() {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $searchQuery = "";
        $filteredStudents = [];

        if (isset($_POST['student_search_submit'])) {
            $searchQuery = $_POST['student_search_query'];
            for ($i=0; $i<count($students); $i++) {
                if (str_contains(strtolower($students[$i]->getFirstName()." ".$students[$i]->getLastName()), strtolower($searchQuery))) {
                    array_push($filteredStudents, $students[$i]->getFirstName()." ".$students[$i]->getLastName());
                }
            }
        }
        return $this->render("students/filterByName.html.twig", array("filteredStudents" => $filteredStudents));
    }

    /**
     * @Route("/student/filterbyclass", name="filter_by_class")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function filterByClass() {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
        $studentsInClass = [];
        $getClassId = "";
        $getClassName = "";

        if (isset($_POST['submit'])) {
            $getClassId = $_POST['class'];
            for ($i=0; $i<count($students); $i++) {
                if ($students[$i]->getClass()) {
                    if ($students[$i]->getClass()->getId() == $getClassId) {
                        array_push($studentsInClass, $students[$i]->getFirstName()." ".$students[$i]->getLastName()); 
                    }
                }
                
            }
            $getClassName = $this->getDoctrine()->getRepository(Classes::class)->find($getClassId)->getName();
        }

        return $this->render("students/filterByClass.html.twig", array("studentsInClass" => $studentsInClass, "classes" => $classes, "classSelected" => $getClassName));
        
    }

    // /**
    //  * @Route("/student/new", name="new_student")
    //  * @Method({"GET", "POST"})
    //  */
    // public function new(Request $request, EventDispatcherInterface $eventDispatcher) {
    //     $student = new Student();

    //     $form = $this->createFormBuilder($student)
    //         ->add("first_name", TextType::class, array('label' => "First Name:", "attr" => array('class' => "form-control")))
    //         ->add("last_name", TextType::class, array('label' => "Last Name:", "attr" => array('class' => "form-control")))
    //         ->add("date_of_birth", TextType::class, array('label' => "Date of Birth:", 'required' => false, "attr" => array('class' => "form-control")))
    //         ->add("imageFile", VichImageType::class, array('label' => "Image Link:", 'required' => false, "attr" => array('class' => "form-control")))
    //         ->add('class', EntityType::class, [
    //             "attr" => array('class' => "form-select"),
    //             'label' => "Class: ",
    //             'class' => Classes::class,
    //             'choice_label' => 'name',
    //         ])
    //         ->add('Create', SubmitType::class, array(
    //             'attr' => array('class' => 'btn btn-primary mt-3')
    //         ))
    //         ->getForm();

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()) {
    //         $student = $form->getData();

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($student);
    //         $imageResizerEvent = new ImageResizerEvent($student, 200, 200, 'resized_');
    //         $eventDispatcher->dispatch($imageResizerEvent, ImageResizerEvent::NAME);
    //         $studentThumbnail = new ImageResizerEvent($student, 50, 50, 'thumbnail_');
    //         $eventDispatcher->dispatch($studentThumbnail, ImageResizerEvent::NAME);
    //         $originalImage = $form->getData()->getImage();
    //         $form->getData()->setImage('resized_'.$originalImage);
    //         $form->getData()->setThumbnail('thumbnail_'.$originalImage);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('student_list');
    //     }

    //     return $this->render('students/create.html.twig', array('form' => $form->createView()));
    // }

    /**
     * @Route("/student/edit/{id}", name="edit_student")
     * @Method({"GET", "POST"})
     * @IsGranted("EDIT", subject="student")
     */
    public function edit(Request $request, EventDispatcherInterface $eventDispatcher, Student $student) {
        // $student = new Student();
        // $student= $this->getDoctrine()->getRepository(Student::class)->find($id);

        $form = $this->createFormBuilder($student)
            ->add("first_name", TextType::class, array('label' => "First Name:", "attr" => array('class' => "form-control")))
            ->add("last_name", TextType::class, array('label' => "Last Name:", "attr" => array('class' => "form-control")))
            ->add("date_of_birth", TextType::class, array('label' => "Date of Birth:", 'required' => false, "attr" => array('class' => "form-control")))
            ->add("imageFile", VichImageType::class, array('label' => "Image Link:", 'required' => false, "attr" => array('class' => "form-control")))
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
            $imageResizerEvent = new ImageResizerEvent($student, 200, 200, 'resized_');
            $eventDispatcher->dispatch($imageResizerEvent, ImageResizerEvent::NAME);
            $studentThumbnail = new ImageResizerEvent($student, 50, 50, 'thumbnail_');
            $eventDispatcher->dispatch($studentThumbnail, ImageResizerEvent::NAME);
            $originalImage = $form->getData()->getImage();
            $form->getData()->setImage('resized_'.$originalImage);
            $form->getData()->setThumbnail('thumbnail_'.$originalImage);
            $entityManager->flush();

            return $this->redirectToRoute('student_list');
        }

        return $this->render('students/edit.html.twig', array('form' => $form->createView(), 'student' => $student));
    }

    /**
     * @Route("/student/{id}", name="student_details")
     * @IsGranted("SHOW", subject="student")
     */
    public function details(Student $student) {
        // $student= $this->getDoctrine()->getRepository(Student::class)->find($id);
        return $this->render("students/details.html.twig", array("student" => $student)); 
     }

     /**
     * @Route("/student/delete/{id}", name="delete_student")
     * @Method({"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id) {
        $student= $this->getDoctrine()->getRepository(Student::class)->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->redirectToRoute('student_list');
     }
}