<?php

namespace App\Validator;

use App\DTO\ItemDTO;
use DateTime;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(ItemDTO $itemDTO): array
    {
        $errors = [];
        
        // Validate using Symfony validator
        $violations = $this->validator->validate($itemDTO);
        
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $errors[$propertyPath] = $violation->getMessage();
            }
        }
        
        // Additional validation for date format
        if ($itemDTO->getRawDateTime() !== null && !isset($errors['rawDateTime'])) {
            try {
                $dateTime = DateTime::createFromFormat('d.m.Y H:i:s', $itemDTO->getRawDateTime());
                
                if ($dateTime === false) {
                    $errors['dateTime'] = 'Invalid date and time format. Use dd.mm.yyyy hh:mm:ss';
                } else {
                    $itemDTO->setDateTime($dateTime);
                }
            } catch (Exception $e) {
                $errors['dateTime'] = 'Invalid date and time format. Use dd.mm.yyyy hh:mm:ss';
            }
        }
        
        // Additional validation for price
        if ($itemDTO->getPrice() !== null && !isset($errors['price'])) {
            if ($itemDTO->getPrice() <= 0) {
                $errors['price'] = 'Price must be greater than zero';
            }
        }
        
        return $errors;
    }
}
