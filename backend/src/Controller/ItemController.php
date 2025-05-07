<?php

namespace App\Controller;

use App\DTO\ItemDTO;
use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
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
