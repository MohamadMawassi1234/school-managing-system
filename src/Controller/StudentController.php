<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Classes;
use App\Entity\FinalGrade;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Knp\Component\Pager\PaginatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StudentController extends AbstractController
{
    // public function __construct(Request $request, PaginatorInterface $paginator) {
    //     $this->request = $request;
    //     $this->paginator = $paginator;
    // }

    /**
     * @Route("/students", name="student_list")
     * @Method({"GET"})
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function listStudents(Request $request, PaginatorInterface $paginator)
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $students = $paginator->paginate(
            $students,
            $request->query->getInt("page", 1),
            5,
        );
        return $this->render("students/list.html.twig", array("students" => $students));
    }

    /**
     * @Route("/student/addgrade/{sid}/{cid}", name="add_grade")
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function addGrade($sid, $cid)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($sid);
        $class = $this->getDoctrine()->getRepository(Classes::class)->find($cid);

        if (isset($_POST['grade_submit'])) {
            $grade = new FinalGrade();
            $entityManager = $this->getDoctrine()->getManager();
            $grade
                ->setGrade($_POST['grade'])
                ->setStudent($student)
                ->setClass($class);

            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirectToRoute('student_details', [
                'id' => $sid
            ]);
        }
        return $this->render("students/addGrade.html.twig");
    }

    /**
     * @Route("/students/filterbyname", name="student_filter")
     * @IsGranted("ROLE_COORDINATOR")
     */
    public function filterStudents()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $searchQuery = "";
        $filteredStudents = [];

        if (isset($_POST['student_search_submit'])) {
            $searchQuery = $_POST['student_search_query'];
            for ($i = 0; $i < count($students); $i++) {
                if (str_contains(strtolower($students[$i]->getFirstName() . " " . $students[$i]->getLastName()), strtolower($searchQuery))) {
                    array_push($filteredStudents, $students[$i]->getFirstName() . " " . $students[$i]->getLastName());
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
    public function filterByClass()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
        $studentsInClass = [];
        $getClassId = "";
        $getClass = "";

        if (isset($_POST['submit'])) {
            $getClassId = $_POST['class'];
            for ($i = 0; $i < count($students); $i++) {
                if ($students[$i]->getClass()) {
                    $classIdArray = [];
                    for ($j = 0; $j < count($students[$i]->getClass()->getValues()); $j++) {
                        array_push($classIdArray, $students[$i]->getClass()->getValues()[$j]->getId());
                    }

                    if (in_array($getClassId, $classIdArray)) {
                        array_push($studentsInClass, $students[$i]->getFirstName() . " " . $students[$i]->getLastName());
                    }
                }
            }
            $getClass = $this->getDoctrine()->getRepository(Classes::class)->find($getClassId)->getCourse()->getName() . ' - ' . $this->getDoctrine()->getRepository(Classes::class)->find($getClassId)->getTime();
        }

        return $this->render("students/filterByClass.html.twig", array("studentsInClass" => $studentsInClass, "classes" => $classes, "classSelected" => $getClass));
    }

    /**
     * @Route("/student/addclass/{id}", name="register_class")
     * @Method({"GET", "POST"})
     * @IsGranted("EDIT", subject="student")
     */
    public function addClass($id, Request $request, Student $student)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        $form = $this->createFormBuilder($student)
            ->add("class", EntityType::class, array(
                "attr" => array('class' => 'form-control', 'style' => "display: flex; flex-direction: column"),
                'class' => Classes::class,
                'choice_label' => function (Classes $class) {
                    return $class->getCourse()->getName() . ' - ' . $class->getTime();
                },
                'multiple' => true,
                'expanded' => true
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Create",
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            // $classes = $student->getClass()->getValues();
            $entityManager = $this->getDoctrine()->getManager();
            // for ($i=0; $i<count($classes); $i++) {
            //     $student->removeClass($classes[$i]);
            //     $student->addClass($classes[$i]);
            // }
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_details', ['id' => $id]);
        }

        return $this->render('students/addClass.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/student/new", name="new_student")
     * @Method({"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $student = new Student();

        $form = $this->createFormBuilder($student)
            ->add('email', TextType::class, array("attr" => array('class' => "form-control")))
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => "form-control"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("first_name", TextType::class, array('label' => "First Name:", "attr" => array('class' => "form-control")))
            ->add("last_name", TextType::class, array('label' => "Last Name:", "attr" => array('class' => "form-control")))
            ->add("date_of_birth", TextType::class, array('label' => "Date of Birth:", 'required' => false, "attr" => array('class' => "form-control")))
            ->add("imageFile", VichImageType::class, array('required' => false, "attr" => array('class' => "form-control")))
            ->add('Create', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            $student->setPassword(
                $userPasswordHasher->hashPassword(
                    $student,
                    $form->get('plainPassword')->getData()
                )
            );

            $student->setRoles(['ROLE_STUDENT']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_list');
        }

        return $this->render('students/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/student/edit/{id}", name="edit_student")
     * @Method({"GET", "POST"})
     * @IsGranted("EDIT", subject="student")
     */
    public function edit(Request $request, $id, Student $student)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        $form = $this->createFormBuilder($student)
            ->add("first_name", TextType::class, array('label' => "First Name:", "attr" => array('class' => "form-control")))
            ->add("last_name", TextType::class, array('label' => "Last Name:", "attr" => array('class' => "form-control")))
            ->add("date_of_birth", TextType::class, array('label' => "Date of Birth:", 'required' => false, "attr" => array('class' => "form-control")))
            ->add("imageFile", VichImageType::class, array('label' => "Image Link:", 'required' => false, "attr" => array('class' => "form-control")))

            ->add('Update', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // return $this->redirectToRoute('student_list');
            return $this->redirectToRoute('student_details', ['id' => $id]);
        }

        return $this->render('students/edit.html.twig', array('form' => $form->createView(), 'student' => $student));
    }

    /**
     * @Route("/student/{id}", name="student_details")
     * @IsGranted("SHOW", subject="student")
     */
    public function details(Student $student)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $newGrades = [];
        // $student= $this->getDoctrine()->getRepository(Student::class)->find($id);
        $classes = $student->getClass()->getValues();


        $grades = $this->getDoctrine()->getRepository(FinalGrade::class)->findBy(["student" => $student]);

        for ($j = 0; $j < count($classes); $j++) {
            for ($i = 0; $i < count($grades); $i++) {
                if ($grades[$i]->getClass() == $classes[$j]) {

                    $newGrades[$j] = $grades[$i];
                }
            }
        }

        for ($i = 0; $i < count($grades); $i++) {
            if (!in_array($grades[$i], $newGrades)) {
                $entityManager->remove($grades[$i]);
            }
        }
        $entityManager->flush();
        $grades = $this->getDoctrine()->getRepository(FinalGrade::class)->findBy(["student" => $student]);
        return $this->render("students/details.html.twig", array("student" => $student, "classes" => $classes, "grades" => $grades));
    }

    /**
     * @Route("/student/delete/{id}", name="delete_student")
     * @Method({"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->redirectToRoute('student_list');
    }

    /**
     * @Route("/student/deleteclass/{sid}/{cid}", name="drop_class")
     * @Method({"DELETE"})
     * @IsGranted("EDIT", subject="student")
     * @ParamConverter("student", class="App\Entity\Student", options={"id" = "sid"})
     */
    public function dropClass($sid, $cid, Student $student)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($sid);
        $class = $this->getDoctrine()->getRepository(Classes::class)->find($cid);
        $student->removeClass($class);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('student_details', ['id' => $sid]);
    }
}
