<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Notification Delay Service API",
    description: "API untuk Service Notifikasi Delay"
)]
#[OA\Server(
    url: "http://localhost:8001",
    description: "Local Server"
)]
class OpenApi
{
}