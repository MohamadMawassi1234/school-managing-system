<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Knp\Component\Pager\PaginatorInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(PaginatorInterface $paginator, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->paginator = $paginator;
    }

    protected function buildForm(string $type, $data = null, array $options = []): FormInterface
    {
        $options = array_merge($options, [
            'csrf_protection' => false,
        ]);

        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    protected function respond($data, int $statusCode = Response::HTTP_OK, array $groups = [], int $pageRange = null): Response
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer([new ObjectNormalizer($classMetadataFactory)]);
        if ($groups) {
            $data = $serializer->normalize($data, null, $groups);
        }
        if ($pageRange) {
            $data = $this->paginator->paginate(
                $data,
                $this->request->query->getInt("page", 1),
                $pageRange,
            );
        }
        return $this->json($data, $statusCode);
    }
}
