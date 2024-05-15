<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\JsonResponse;

it('logs the user if the credentials are correct', function() {
    $auth = Mockery::mock(AuthController::class);
    $auth->shouldReceive('login')->andReturn(new JsonResponse([
        'access_token' => 'token',
        'token_type' => 'bearer',
        'expires_in' => 3600
    ]));

    $response = $auth->login();
    $checkAuth = json_decode($response->getContent(), true);

    expect($checkAuth['access_token'])->toBe('token')
        ->and($checkAuth['token_type'])->toBe('bearer')
        ->and($checkAuth['expires_in'])->toBe(3600);
});

it('logs the user out', function() {
    $auth = Mockery::mock(AuthController::class);
    $auth->shouldReceive('logout')->andReturn(new JsonResponse(['message' => 'Successfully logged out']));

    $response = $auth->logout();
    $checkLogout = json_decode($response->getContent(), true);

    expect($checkLogout['message'])->toBe('Successfully logged out');
});

it('refreshes the token', function() {
    $auth = Mockery::mock(AuthController::class);
    $auth->shouldReceive('refresh')->andReturn(new JsonResponse([
        'access_token' => 'token',
        'token_type' => 'bearer',
        'expires_in' => 3600
    ]));

    $response = $auth->refresh();
    $checkRefreshToken = json_decode($response->getContent(), true);
    expect($checkRefreshToken['access_token'])->toBe('token')
        ->and($checkRefreshToken['token_type'])->toBe('bearer')
        ->and($checkRefreshToken['expires_in'])->toBe(3600);
});

it('print error if credentials are wrong', function() {
    $auth = Mockery::mock(AuthController::class);
    $auth->shouldReceive('login')->andReturn(new JsonResponse(['error' => 'Unauthorized'], 401));

    $response = $auth->login();
    $checkAuth = json_decode($response->getContent(), true);

    expect($checkAuth['error'])->toBe('Unauthorized');
});




