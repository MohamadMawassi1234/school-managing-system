<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\FinalGrade;
use App\Form\StudentFormType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StudentApiController extends AbstractApiController
{

    /**
     * @Route("/api/v1/students", name="api_get_all_students", methods={"GET"})
     */
    public function listStudents()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->respond($students, 200, ['groups' => 'student'], 5);
    }

    /**
     * @Route("/api/v1/students/{id}", name="api_get_single_students", methods={"GET"})
     */
    public function getSingleStudent(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        return $this->respond($student, 200, ['groups' => 'student']);
    }

    /**
     * @Route("/api/v1/students/{id}/grades", name="api_get_student_grades", methods={"GET"})
     */
    public function getStudentGrades(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $grades = $this->getDoctrine()->getRepository(FinalGrade::class)->findBy(["student" => $student]);
        return $this->respond($grades, 200, ['groups' => 'grade'], 3);
    }


    /**
     * @Route("/api/v1/students", name="api_add_student", methods={"POST"})
     */
    public function newStudent(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $form = $this->buildForm(StudentFormType::class, null, [
            'show_auth_info' => true
        ]);
        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Student $student */
        $student = $form->getData();
        // dd($form->getData());
        $student->setPassword(
            $userPasswordHasher->hashPassword(
                $student,
                $form->get('plainPassword')->getData()
            )
        );
        $student->setRoles(['ROLE_STUDENT']);
        $this->getDoctrine()->getManager()->persist($student);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($student, 200, ['groups' => ['student', 'user']]);
    }

    /**
     * @Route("/api/v1/students/{id}", name="api_update_student", methods={"PUT"})
     */
    public function updateStudent(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $form = $this->buildForm(StudentFormType::class, $student, [
            'method' => $request->getMethod(),
            'show_auth_info' => false
        ]);
        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Student $student */
        $student = $form->getData();

        $this->getDoctrine()->getManager()->persist($student);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($student, 200, ['groups' => 'student']);
    }



    /**
     * @Route("/api/v1/students/{id}", name="api_delete_student", methods={"DELETE"})
     */
    public function deleteStudent(Request $request, $id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($student);
        $entityManager->flush();
        return $this->json("student deleted");
    }
}

























    // /**
    //  * @Route("/api/v1/students", name="api_get_all_students", methods={"GET"})
    //  */
    // public function listStudents() {
    //     $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
    //     $students = $this->getSerializer()->normalize($students, null, ['groups' => 'student']);
    //     return $this->json($students);
    // }

    // /**
    //  * @Route("/api/v1/students/{id}", name="api_get_single_students", methods={"GET"})
    //  */
    // public function getSingleStudent(Request $request, $id) {
    //     $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
    //     $student = $this->getSerializer()->normalize($student, null, ['groups' => 'student']);
    //     return $this->json($student);
    // }

    // /**
    //  * @Route("/api/v1/students/{id}/grades", name="api_get_student_grades", methods={"GET"})
    //  */
    // public function getStudentGrades(Request $request, $id) {
    //     $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
    //     $grades = $this->getDoctrine()->getRepository(FinalGrade::class)->findBy(["student" => $student]);
    //     $grades = $this->getSerializer()->normalize($grades, null, ['groups' => 'grade']);
    //     return $this->json($grades);
    // }


    // $student = $this->getSerializer()->normalize($student, null, ['groups' => 'student']);
    // return $this->json($student);

    // $student = $this->getSerializer()->normalize($student, null, ['groups' => ['student', 'user']]);
    // return $this->json($student);























// try {
        //     $content = $request->getContent();
        //     $studentArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $student = new Student();
        //     if(array_key_exists("first_name", $studentArray) && array_key_exists("last_name")) {
        //         $student->setFirstName($studentArray['first_name']);
        //         $student->setLastName($studentArray['last_name']);
        //     } else throw new BadRequestException("'first name' and 'last name' required");
        //     if(array_key_exists("date_of_birth", $studentArray)) {
        //         $student->setDateofBirth($studentArray['date_of_birth']);
        //     }
        //     $entityManager->persist($student);
        //     $entityManager->flush();
        //     return $this->json($studentArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }



// try {
        //     $content = $request->getContent();
        //     $studentArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        //     if(array_key_exists("first_name", $studentArray) && array_key_exists("last_name")) {
        //         $student->setFirstName($studentArray['first_name']);
        //         $student->setLastName($studentArray['last_name']);
        //     } else throw new BadRequestException("'first name' and 'last name' required");
        //     if(array_key_exists("date_of_birth", $studentArray)) {
        //         $student->setDateofBirth($studentArray['date_of_birth']);
        //     } else $student->setDateofBirth(null);
        //     $entityManager->flush();
        //     return $this->json($studentArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }



        // dd($student->getClass()->getValues());
        // $classes = $student->getClass()->getValues();
        // for ($i=0; $i<count($classes); $i++) {
        //     $student->addClass($classes[$i]);
        // }