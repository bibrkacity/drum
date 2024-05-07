<?php

namespace App\Http\Controllers\Api\V1;

/**
 * @OA\Info(title="Tasks API", version="1")
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   name="Bearer authorization",
 *   scheme="bearer",
 *   in="header"
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 * ),
 */
abstract class ApiController
{
    //
}
