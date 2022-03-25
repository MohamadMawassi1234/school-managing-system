<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Classes;
use App\Entity\FinalGrade;
use App\Entity\PostFactory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class StudentApiController extends AbstractController {
    /**
     * @Route("/api/v1/students", name="api_get_all_students", methods={"GET"})
     */
    public function listStudents() {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->json($students);
    }

    /**
     * @Route("/api/v1/students/{id}", name="api_get_single_students", methods={"GET"})
     */
    public function getSingleStudent(Request $request, $id) {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        return $this->json($student);
    }

    /**
     * @Route("/api/v1/students/{id}/grades", name="api_get_student_grades", methods={"GET"})
     */
    public function getStudentGrades(Request $request, $id) {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $grades = $this->getDoctrine()->getRepository(FinalGrade::class)->findBy(["student" => $student]);
        return $this->json($grades);
    }


    /**
     * @Route("/api/v1/students", name="api_add_student", methods={"POST"})
     */
    public function newStudent(Request $request) {
        try {
            $content = $request->getContent();
            $studentArray = json_decode($content, true);
            $entityManager = $this->getDoctrine()->getManager();
            $student = new Student();
            if(array_key_exists("first_name", $studentArray) && array_key_exists("last_name")) {
                $student->setFirstName($studentArray['first_name']);
                $student->setLastName($studentArray['last_name']);
            } else throw new BadRequestException("'first name' and 'last name' required");
            if(array_key_exists("date_of_birth", $studentArray)) {
                $student->setDateofBirth($studentArray['date_of_birth']);
            }
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->json($studentArray);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
        
    }

    /**
     * @Route("/api/v1/students/{id}", name="api_update_student", methods={"PUT"})
     */
    public function updateStudent(Request $request, $id) {
        try {
            $content = $request->getContent();
            $studentArray = json_decode($content, true);
            $entityManager = $this->getDoctrine()->getManager();
            $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
            if(array_key_exists("first_name", $studentArray) && array_key_exists("last_name")) {
                $student->setFirstName($studentArray['first_name']);
                $student->setLastName($studentArray['last_name']);
            } else throw new BadRequestException("'first name' and 'last name' required");
            if(array_key_exists("date_of_birth", $studentArray)) {
                $student->setDateofBirth($studentArray['date_of_birth']);
            } else $student->setDateofBirth(null);
            $entityManager->flush();
            return $this->json($studentArray);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
        
    }

    

    /**
     * @Route("/api/v1/students/{id}", name="api_delete_student", methods={"DELETE"})
     */
    public function deleteStudent(Request $request, $id) {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($student);
        $entityManager->flush();
        return $this->json("student deleted");
    }
}