<?php

declare(strict_types=1);

namespace App\Api\User\Controller;

use App\Api\User\Dto\UserCreateRequestDto;
use App\Api\User\Dto\UserListResponseDto;
use App\Api\User\Dto\UserResponseDto;
use App\Api\User\Dto\UserUpdateRequestDto;
use App\Api\User\Dto\ValidationExampleRequestDto;
use App\Api\User\Factory\ResponseFactory;
use App\Core\Common\Dto\ValidationFailedResponse;
use App\Core\Common\Factory\HTTPResponseFactory;
use App\Core\User\Document\Contact;
use App\Core\User\Document\User;
use App\Core\User\Enum\Permission;
use App\Core\User\Repository\ContactRepository;
use App\Core\User\Repository\UserRepository;
use App\Core\User\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"GET"})
     *
     * @IsGranted(Permission::USER_SHOW)
     *
     * @ParamConverter("user")
     *
     * @Rest\View()
     *
     * @param User|null         $user
     * @param ContactRepository $contactRepository
     * @param ResponseFactory   $responseFactory
     *
     * @return UserResponseDto
     */
    public function show(User $user = null, ContactRepository $contactRepository, ResponseFactory $responseFactory)
    {
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $contact = $contactRepository->findOneBy(['user' => $user]);

        return $responseFactory->createUserResponse($user, $contact);
    }

    /**
     * @Route(path="", methods={"GET"})
     * @IsGranted(Permission::USER_INDEX)
     * @Rest\View()
     *
     * @param Request         $request
     * @param UserRepository  $userRepository
     * @param ResponseFactory $responseFactory
     *
     * @return UserListResponseDto|ValidationFailedResponse
     */
    public function index(
        Request $request,
        UserRepository $userRepository,
        ResponseFactory $responseFactory
    ): UserListResponseDto {
        $page     = (int)$request->get('page');
        $quantity = (int)$request->get('slice');

        $users = $userRepository->findBy([], [], $quantity, $quantity * ($page - 1));

        return new UserListResponseDto(
            ... array_map(
                    function (User $user) use ($responseFactory) {
                        return $responseFactory->createUserResponse($user);
                    },
                    $users
                )
        );
    }

    /**
     * @Route(path="", methods={"POST"})
     * @IsGranted(Permission::USER_CREATE)
     * @ParamConverter("requestDto", converter="fos_rest.request_body")
     *
     * @Rest\View(statusCode=201)
     *
     * @param UserCreateRequestDto             $requestDto
     * @param ConstraintViolationListInterface $validationErrors
     * @param UserService                      $service
     * @param ResponseFactory                  $responseFactory
     * @param HTTPResponseFactory              $HTTPResponseFactory
     *
     * @return UserResponseDto|ValidationFailedResponse|Response
     */
    public function create(
        UserCreateRequestDto $requestDto,
        ConstraintViolationListInterface $validationErrors,
        UserService $service,
        ResponseFactory $responseFactory,
        HTTPResponseFactory $HTTPResponseFactory
    ) {
        if ($validationErrors->count() > 0) {
            return $HTTPResponseFactory->createValidationFailedResponse($validationErrors);
        }

        return $responseFactory->createUserResponse($service->createUser($requestDto));
    }

    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}/contact", methods={"POST"})
     * @IsGranted(Permission::USER_CONTACT_CREATE)
     * @ParamConverter("user")
     *
     * @Rest\View(statusCode=201)
     *
     * @param Request                          $request
     * @param User|null                        $user
     * @param ContactRepository                $contactRepository
     * @param ResponseFactory                  $responseFactory
     * @param ConstraintViolationListInterface $validationErrors
     * @param HTTPResponseFactory              $HTTPResponseFactory
     *
     * @return UserResponseDto|ValidationFailedResponse|Response
     */
    public function createContact(
        Request $request,
        User $user = null,
        ContactRepository $contactRepository,
        ResponseFactory $responseFactory,
        ConstraintViolationListInterface $validationErrors,
        HTTPResponseFactory $HTTPResponseFactory
    ) {
        // todo проверки на валидацию всего всего и дто ...
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($validationErrors->count() > 0) {
            return $HTTPResponseFactory->createValidationFailedResponse($validationErrors);
        }

        $contact = new Contact($request->get('phone', ''), $request->get('name', ''), $user);
        $contactRepository->save($contact);

        return $responseFactory->createUserResponse($user, $contact);
    }

    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"PUT"})
     * @IsGranted(Permission::USER_UPDATE)
     * @ParamConverter("user")
     * @ParamConverter("requestDto", converter="fos_rest.request_body")
     *
     * @Rest\View()
     *
     * @param User|null                        $user
     * @param UserUpdateRequestDto             $requestDto
     * @param ConstraintViolationListInterface $validationErrors
     * @param UserRepository                   $userRepository
     * @param ResponseFactory                  $responseFactory
     * @param UserService                      $service
     * @var id
     * 
     * @return UserResponseDto|ValidationFailedResponse|Response
     */
    public function update(
        User $user = null,
        UserService $service,
        UserUpdateRequestDto $requestDto,
        ConstraintViolationListInterface $validationErrors,
        UserRepository $userRepository,
        ResponseFactory $responseFactory
    ) {
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($validationErrors->count() > 0) {
            return new ValidationFailedResponse($validationErrors);
        }
        $id = $user->getId();

        return $responseFactory->createUserResponse($service->updateUser($id, $requestDto));
    }

    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"DELETE"})
     * @IsGranted(Permission::USER_DELETE)
     * @ParamConverter("user")
     *
     * @Rest\View()
     *
     * @param User|null      $user
     * @param UserRepository $userRepository
     *
     * @return UserResponseDto|ValidationFailedResponse
     */
    public function delete(
        UserRepository $userRepository,
        User $user = null
    ) {
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userRepository->remove($user);
    }

    /**
     * @Route(path="/validation", methods={"POST"})
     * @IsGranted(Permission::USER_VALIDATION)
     * @ParamConverter("requestDto", converter="fos_rest.request_body")
     *
     * @Rest\View()
     *
     * @return ValidationExampleRequestDto|ValidationFailedResponse
     */
    public function validation(
        ValidationExampleRequestDto $requestDto,
        ConstraintViolationListInterface $validationErrors
    ) {
        if ($validationErrors->count() > 0) {
            return new ValidationFailedResponse($validationErrors);
        }

        return $requestDto;
    }
}
