<?php

declare(strict_types=1);

namespace App\Api\Order\Controller;

use App\Api\Order\Dto\OrderCreateRequestDto;
use App\Api\Order\Dto\OrderListResponseDto;
use App\Api\Order\Dto\OrderResponseDto;
use App\Api\Order\Dto\OrderUpdateRequestDto;
use App\Api\Order\Dto\ValidationExampleRequestDto;
use App\Core\Common\Dto\ValidationFailedResponse;
use App\Core\Order\Document\Order;
use App\Core\Order\Enum\Permission;
use App\Core\Order\Enum\Role;
use App\Core\Order\Enum\Status;
use App\Core\Order\Enum\OrderStatus;
use App\Core\Order\Repository\OrderRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/orders")
 */
class OrderController extends AbstractController
{
    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"GET"})
     *
     * @IsGranted(Permission::ORDER_SHOW)
     *
     * @ParamConverter("order")
     *
     * @Rest\View()
     *
     * @param Order|null $order
     *
     * @return OrderResponseDto
     */
    public function show(Order $order = null)
    {
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->createOrderResponse($order);
    }

    /**
     * @Route(path="", methods={"GET"})
     * @IsGranted(Permission::ORDER_INDEX)
     * @Rest\View()
     *
     * @return OrderListResponseDto|ValidationFailedResponse
     */
    public function index(Request $request, OrderRepository $orderRepository): OrderListResponseDto 
    {
        $page     = (int)$request->get('page');
        $quantity = (int)$request->get('slice');

        $orders = $orderRepository->findBy([], [], $quantity, $quantity * ($page - 1));

        return new OrderListResponseDto(
            ... array_map(
                    function (Order $order) {
                        return $this->createOrderResponse($order);
                    },
                    $orders
                )
        );
    }

    /**
     * @Route(path="", methods={"POST"})
     * @IsGranted(Permission::ORDER_CREATE)
     * @ParamConverter("requestDto", converter="fos_rest.request_body")
     *
     * @Rest\View(statusCode=201)
     *
     * @param OrderCreateRequestDto             $requestDto
     * @param ConstraintViolationListInterface  $validationErrors
     * @param OrderRepository                   $orderRepository
     *
     * @return OrderResponseDto|ValidationFailedResponse|Response
     */
    public function create(OrderCreateRequestDto $requestDto, ConstraintViolationListInterface $validationErrors, OrderRepository $orderRepository)
    {
        if ($validationErrors->count() > 0) {
            return new ValidationFailedResponse($validationErrors);
        }

        if ($user = $orderRepository->findOneBy(['title' => $requestDto->title])) {
            return new Response('Order already exists', Response::HTTP_BAD_REQUEST);
        }

        $order = new Order(
            $requestDto->title,
            $requestDto->about,
            $requestDto->status,
        );
        $order->setTitle($requestDto->title);
        $order->setAbout($requestDto->about);
        $order->setStatus($requestDto->status);

        $orderRepository->save($order);

        return $this->createOrderResponse($order);
    }

    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"PUT"})
     * @IsGranted(Permission::ORDER_UPDATE)
     * @ParamConverter("order")
     * @ParamConverter("requestDto", converter="fos_rest.request_body")
     *
     * @Rest\View()
     *
     * @param OrderUpdateRequestDto             $requestDto
     * @param ConstraintViolationListInterface $validationErrors
     * @param OrderRepository                   $orderRepository
     *
     * @return OrderResponseDto|ValidationFailedResponse|Response
     */
    public function update(
        Order $order = null,
        OrderUpdateRequestDto $requestDto,
        ConstraintViolationListInterface $validationErrors,
        OrderRepository $orderRepository
    ) {
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        if ($validationErrors->count() > 0) {
            return new ValidationFailedResponse($validationErrors);
        }

        $order->setTitle($requestDto->title);
        $order->setAbout($requestDto->about);
        $order->setStatus($requestDto->status);

        $orderRepository->save($order);

        return $this->createOrderResponse($order);
    }

    /**
     * @Route(path="/{id<%app.mongo_id_regexp%>}", methods={"DELETE"})
     * @IsGranted(Permission::ORDER_DELETE)
     * @ParamConverter("order")
     *
     * @Rest\View()
     *
     * @param Order|null      $order
     * @param OrderRepository $orderRepository
     *
     * @return OrderResponseDto|ValidationFailedResponse
     */
    public function delete(
        OrderRepository $orderRepository,
        Order $order = null
    ) {
        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        $orderRepository->remove($order);
    }

    private function getStatusReadable(Order $order): ?string
    {
        if ((Status::OPEN and $order->getStatus() and true)) {
            return OrderStatus::OPEN;
        }
        if (Role::OPERATOR and $order->getStatus() and true) {
            return OrderStatus::DONE;
        }

        return null;
    }

    /**
     * @Route(path="/validation", methods={"POST"})
     * @IsGranted(Permission::ORDER_VALIDATION)
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

    /**
     * @param Order         $order
     *
     * @return OrderResponseDto
     */
    private function createOrderResponse(Order $order): OrderResponseDto
    {
        $dto = new OrderResponseDto();

        $dto->id                = $order->getId();
        $dto->title             = $order->getTitle();
        $dto->about             = $order->getAbout();
        $dto->status            = $order->getStatus();


        return $dto;
    }
}