<?php

namespace App\Controller;

use App\DTO\ItemDTO;
use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api', name: 'api_')]
#[OA\Tag(name: 'Товары')]
class ItemController extends AbstractController
{
    private ItemService $itemService;
    private SerializerInterface $serializer;

    public function __construct(ItemService $itemService, SerializerInterface $serializer)
    {
        $this->itemService = $itemService;
        $this->serializer = $serializer;
    }

    #[Route('/items', name: 'get_items', methods: ['GET'])]
    #[OA\Get(
        path: '/api/items',
        summary: 'Получение списка всех товаров',
        description: 'Возвращает список всех товаров, отсортированных по дате и времени',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Item'))
            )
        ]
    )]
    public function getItems(): JsonResponse
    {
        $result = $this->itemService->getAllItems();
        
        return new JsonResponse(
            $this->serializer->serialize($result['items'], 'json', ['groups' => 'item:read']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/items', name: 'create_item', methods: ['POST'])]
    #[OA\Post(
        path: '/api/items',
        summary: 'Добавление нового товара',
        description: 'Добавляет новый товар в систему с валидацией данных',
        requestBody: new OA\RequestBody(
            description: 'Данные товара',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Headphones', description: 'Наименование товара'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 10.00, description: 'Цена товара в долларах'),
                    new OA\Property(property: 'dateTime', type: 'string', example: '11.01.2021 10:00:00', description: 'Дата и время в формате дд.мм.гггг чч:мм:сс')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Товар успешно добавлен',
                content: new OA\JsonContent(ref: '#/components/schemas/Item')
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function createItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $itemDTO = new ItemDTO();
        $itemDTO->setName($data['name'] ?? null);
        
        // Handle price validation
        $price = $data['price'] ?? null;
        if ($price !== null) {
            $itemDTO->setPrice((float) $price);
        }
        
        // Handle date validation
        $dateTime = $data['dateTime'] ?? null;
        if ($dateTime !== null) {
            $itemDTO->setRawDateTime($dateTime);
            
            // Try to parse the date
            try {
                $date = \DateTime::createFromFormat('d.m.Y H:i:s', $dateTime);
                if ($date) {
                    $itemDTO->setDateTime($date);
                }
            } catch (\Exception $e) {
                // Date parsing will be handled by the validator
            }
        }
        
        $result = $this->itemService->createItem($itemDTO);
        
        if (!$result['success']) {
            return new JsonResponse([
                'success' => false,
                'errors' => $result['errors']
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        return new JsonResponse([
            'success' => true,
            'item' => json_decode($this->serializer->serialize($result['item'], 'json', ['groups' => 'item:read']))
        ], JsonResponse::HTTP_CREATED);
    }
}
