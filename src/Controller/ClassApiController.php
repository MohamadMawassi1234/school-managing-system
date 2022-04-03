<?php

namespace App\Controller;


use App\Entity\Classes;
use App\Entity\Student;

use App\Form\ClassFormType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassApiController extends AbstractApiController
{

    /**
     * @Route("/api/v1/classes", name="api_get_all_classes", methods={"GET"})
     */
    public function listClasses()
    {
        $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
        return $this->respond($classes, 200, ['groups' => 'class'], 5);
    }

    /**
     * @Route("/api/v1/classes/{student_id}", name="api_get_classes_by_student_id", methods={"GET"})
     */
    public function getClassesByStudentId(Request $request, $student_id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($student_id);
        $classes = $student->getClass()->getValues();
        return $this->respond($classes, 200, ['groups' => 'class'], 3);
    }


    /**
     * @Route("/api/v1/classes", name="api_add_class", methods={"POST"})
     */
    public function newClass(Request $request): Response
    {
        $form = $this->buildForm(ClassFormType::class);
        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Classes $class */
        $class = $form->getData();
        $this->getDoctrine()->getManager()->persist($class);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($class, 200, ['groups' => 'class']);
    }

    /**
     * @Route("/api/v1/classes/{id}", name="api_update_class", methods={"PUT"})
     */
    public function updateClass(Request $request, $id)
    {
        $class = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        $form = $this->buildForm(ClassFormType::class, $class, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Classes $class */
        $class = $form->getData();
        $this->getDoctrine()->getManager()->persist($class);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($class, 200, ['groups' => 'class']);
    }

    // /**
    //  * @Route("/api/v1/classes/{id}", name="api_get_single_class", methods={"GET"})
    //  */
    // public function getSingleClass(Request $request, $id) {
    //     $class = $this->getDoctrine()->getRepository(Classes::class)->find($id);
    //     return $this->json($class);
    // }

    /**
     * @Route("/api/v1/classes/{id}", name="api_delete_class", methods={"DELETE"})
     */
    public function deleteClass(Request $request, $id)
    {
        $class = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($class);
        $entityManager->flush();
        return $this->json("Class deleted");
    }
}











































    // /**
    //  * @Route("/api/v1/classes", name="api_get_all_classes", methods={"GET"})
    //  */
    // public function listClasses() {
    //     $classes = $this->getDoctrine()->getRepository(Classes::class)->findAll();
    //     $classes = $this->getSerializer()->normalize($classes, null, ['groups' => 'class']);
    //     return $this->json($classes);
    // }

    // /**
    //  * @Route("/api/v1/classes/{student_id}", name="api_get_classes_by_student_id", methods={"GET"})
    //  */
    // public function getClassesByStudentId(Request $request, $student_id) {
    //     $student = $this->getDoctrine()->getRepository(Student::class)->find($student_id);
    //     $classes = $student->getClass()->getValues();
    //     $classes = $this->getSerializer()->normalize($classes, null, ['groups' => 'class']);
    //     return $this->json($classes);
    // }














        // try {
        //     $content = $request->getContent();
        //     $classArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $class = new Classes();
        //     if(array_key_exists("time", $classArray) && array_key_exists("course_id", $classArray)) {
        //         $class->setTime($classArray['time']);
        //         $class->setCourse($this->getDoctrine()->getRepository(Course::class)->find($classArray['course_id']));
        //     } else throw new BadRequestException("'course_id' and 'time' required");
        //     if(array_key_exists("section", $classArray)) {
        //         $class->setSection($classArray['section']);
        //     }
        //     $entityManager->persist($class);
        //     $entityManager->flush();
        //     return $this->json($classArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }



        // try {
        //     $content = $request->getContent();
        //     $classArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $class = $this->getDoctrine()->getRepository(Classes::class)->find($id);
        //     if(array_key_exists("time", $classArray) && array_key_exists("course_id", $classArray)) {
        //         $class->setTime($classArray['time']);
        //         $class->setCourse($this->getDoctrine()->getRepository(Course::class)->find($classArray['course_id']));
        //     } else throw new BadRequestException("'course_id' and 'time' required");
        //     if(array_key_exists("section", $classArray)) {
        //         $class->setSection($classArray['section']);
        //     } else $class->setSection(null);
        //     $entityManager->flush();
        //     return $this->json($classArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }