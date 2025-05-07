<?php

namespace App\Service;

use App\DTO\ItemDTO;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Validator\ItemValidator;
use Symfony\Component\Serializer\SerializerInterface;

class ItemService
{
    private ItemRepository $itemRepository;
    private ItemValidator $itemValidator;
    private SerializerInterface $serializer;

    public function __construct(
        ItemRepository $itemRepository,
        ItemValidator $itemValidator,
        SerializerInterface $serializer
    ) {
        $this->itemRepository = $itemRepository;
        $this->itemValidator = $itemValidator;
        $this->serializer = $serializer;
    }

    public function createItem(ItemDTO $itemDTO): array
    {
        // Validate the item
        $errors = $this->itemValidator->validate($itemDTO);
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }
        
        // Create and save the item
        $item = new Item();
        $item->setName($itemDTO->getName());
        $item->setPrice($itemDTO->getPrice());
        $item->setDateTime($itemDTO->getDateTime());
        
        $this->itemRepository->save($item);
        
        return [
            'success' => true,
            'item' => $item
        ];
    }

    public function getAllItems(): array
    {
        $items = $this->itemRepository->findAllSortedByDateTime();
        
        return [
            'success' => true,
            'items' => $items
        ];
    }

    public function serializeItems(array $items): string
    {
        return $this->serializer->serialize($items, 'json', ['groups' => 'item:read']);
    }
}
