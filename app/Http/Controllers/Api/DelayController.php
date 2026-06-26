<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class DelayController extends Controller
{
    #[OA\Get(
        path: '/api/v1/delays',
        summary: 'Get all delays',
        tags: ['Delays'],
        security: [['ApiKeyAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Success')
        ]
    )]
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Delay list retrieved successfully',
            'data' => Delay::all(),
            'meta' => [
                'service_name' => 'Notification-Delay-Service',
                'api_version' => 'v1'
            ]
        ]);
    }

    #[OA\Get(
        path: '/api/v1/delays/{id}',
        summary: 'Get delay by ID',
        tags: ['Delays'],
        security: [['ApiKeyAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function show($id)
    {
        $delay = Delay::find($id);

        if (!$delay) {
            return response()->json([
                'status' => 'error',
                'message' => 'Delay not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Delay retrieved successfully',
            'data' => $delay,
            'meta' => [
                'service_name' => 'Notification-Delay-Service',
                'api_version' => 'v1'
            ]
        ]);
    }

    #[OA\Post(
        path: '/api/v1/delays',
        summary: 'Create a new delay',
        tags: ['Delays'],
        security: [['ApiKeyAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['schedule_code', 'reason', 'delay_minutes'],
                properties: [
                    new OA\Property(property: 'schedule_code', type: 'string', example: 'SCH001'),
                    new OA\Property(property: 'reason', type: 'string', example: 'Traffic jam'),
                    new OA\Property(property: 'delay_minutes', type: 'integer', example: 30)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 422, description: 'Validation Error')
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_code' => 'required|string',
            'reason' => 'required|string',
            'delay_minutes' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $delay = Delay::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Delay created successfully',
            'data' => $delay,
            'meta' => [
                'service_name' => 'Notification-Delay-Service',
                'api_version' => 'v1'
            ]
        ], 201);
    }

    #[OA\Post(
        path: '/api/v1/delays/notifications',
        summary: 'Send delay notification',
        tags: ['Delays'],
        security: [['ApiKeyAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'schedule_code', type: 'string', example: 'SCH001')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Success')
        ]
    )]
    public function sendNotification(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Delay notification sent successfully',
            'data' => [
                'schedule_code' => $request->schedule_code,
                'notification' => 'Your trip has been delayed'
            ],
            'meta' => [
                'service_name' => 'Notification-Delay-Service',
                'api_version' => 'v1'
            ]
        ]);
    }
}