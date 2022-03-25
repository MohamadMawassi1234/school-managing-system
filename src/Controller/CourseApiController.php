<?php
namespace App\Controller;

use App\Entity\Course;
use App\Entity\Classes;
use App\Entity\PostFactory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CourseApiController extends AbstractController {
    /**
     * @Route("/api/v1/courses", name="api_get_all_courses", methods={"GET"})
     */
    public function listCourses() {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->json($courses);
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_get_single_course", methods={"GET"})
     */
    public function getSingleCourse(Request $request, $id) {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        return $this->json($course);
    }


    /**
     * @Route("/api/v1/courses", name="api_add_course", methods={"POST"})
     */
    public function newCourse(Request $request) {
        try {
            $content = $request->getContent();
            $courseArray = json_decode($content, true);
            $entityManager = $this->getDoctrine()->getManager();
            $course = new Course();
            if(array_key_exists("name", $courseArray)) {
                $course->setName($courseArray['name']);
            } else throw new BadRequestException("'name' required");
            if(array_key_exists("description", $courseArray)) {
                $course->setDescription($courseArray['description']);
            }
            $entityManager->persist($course);
            $entityManager->flush();
            return $this->json($courseArray);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
        
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_update_course", methods={"PUT"})
     */
    public function updateCourse(Request $request, $id) {
        try {
            $content = $request->getContent();
            $courseArray = json_decode($content, true);
            $entityManager = $this->getDoctrine()->getManager();
            $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
            if(array_key_exists("name", $courseArray)) {
                $course->setName($courseArray['name']);
            } else throw new BadRequestException("'name' required");
            if(array_key_exists("description", $courseArray)) {
                $course->setDescription($courseArray['description']);
            } else $course->setDescription(null);
            $entityManager->flush();
            return $this->json($courseArray);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 400);
        }
        
    }

    /**
     * @Route("/api/v1/courses/{id}", name="api_delete_course", methods={"DELETE"})
     */
    public function deleteCourse(Request $request, $id) {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($course);
        $entityManager->flush();
        return $this->json("Course deleted");
    }
}