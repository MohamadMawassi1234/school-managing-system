<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseFormType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourseApiController extends AbstractApiController
{
    /**
     * @Route("/api/v1/courses", name="api_get_all_courses", methods={"GET"})
     */
    public function listCourses()
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->respond($courses, 200, ['groups' => 'course'], 5);
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_get_single_course", methods={"GET"})
     */
    public function getSingleCourse(Request $request, $id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        return $this->respond($course, 200, ['groups' => 'course']);
    }


    /**
     * @Route("/api/v1/courses", name="api_add_course", methods={"POST"})
     */
    public function newCourse(Request $request): Response
    {
        $form = $this->buildForm(CourseFormType::class);
        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Course $course */
        $course = $form->getData();
        $this->getDoctrine()->getManager()->persist($course);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($course, 200, ['groups' => 'course']);
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_update_course", methods={"PUT"})
     */
    public function updateCourse(Request $request, $id)
    {

        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $form = $this->buildForm(CourseFormType::class, $course, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($this->transformJsonBody($request));
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        /** @var Course $course */
        $course = $form->getData();
        $this->getDoctrine()->getManager()->persist($course);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($course, 200, ['groups' => 'course']);
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_delete_course", methods={"DELETE"})
     */
    public function deleteCourse(Request $request, $id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($course);
        $entityManager->flush();
        return $this->json("Course deleted");
    }
}


























    // /**
    //  * @Route("/api/v1/courses/{id}", name="api_get_single_course", methods={"GET"})
    //  */
    // public function getSingleCourse(Request $request, $id) {
    //     $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
    //     $course = $this->getSerializer()->normalize($course, null, ['groups' => 'course']);
    //     return $this->json($course);
    // }






        // try {
        //     $content = $request->getContent();
        //     $courseArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $course = new Course();
        //     if(array_key_exists("name", $courseArray)) {
        //         $course->setName($courseArray['name']);
        //     } else throw new BadRequestException("'name' required");
        //     if(array_key_exists("description", $courseArray)) {
        //         $course->setDescription($courseArray['description']);
        //     }
        //     $entityManager->persist($course);
        //     $entityManager->flush();
        //     return $this->json($courseArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }



                // try {
        //     $content = $request->getContent();
        //     $courseArray = json_decode($content, true);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        //     if(array_key_exists("name", $courseArray)) {
        //         $course->setName($courseArray['name']);
        //     } else throw new BadRequestException("'name' required");
        //     if(array_key_exists("description", $courseArray)) {
        //         $course->setDescription($courseArray['description']);
        //     } else $course->setDescription(null);
        //     $entityManager->flush();
        //     return $this->json($courseArray);
        // } catch (\Exception $e) {
        //     return $this->json($e->getMessage(), 400);
        // }