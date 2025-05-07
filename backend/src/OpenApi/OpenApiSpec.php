<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Stock Center API",
    version: "1.0.0",
    description: "API для управления товарами на складе",
    contact: new OA\Contact(name: "Stock Center Team")
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Локальный сервер разработки"
)]
class OpenApiSpec
{
}
