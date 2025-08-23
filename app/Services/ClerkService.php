<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClerkService
{
    private $apiUrl;
    private $secretKey;

    public function __construct()
    {
        $this->apiUrl = config('services.clerk.api_url');
        $this->secretKey = config('services.clerk.secret_key');
    }

    public function createUser($userData)
    {
        try {
            // Validar que tenemos la clave secreta
            if (!$this->secretKey) {
                throw new \Exception('CLERK_SECRET_KEY no está configurada en el .env');
            }

            $clerkData = [
                'email_address' => [$userData['email']],
                'username' => $userData['username'] ?? $userData['first_name'] ?? 'user' . time(),
                'password' => $userData['password'],
            ];

            if (!empty($userData['first_name'])) {
                $clerkData['first_name'] = $userData['first_name'];
            }
            
            if (!empty($userData['last_name'])) {
                $clerkData['last_name'] = $userData['last_name'];
            }

            Log::info('Datos enviados a Clerk:', $clerkData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/users", $clerkData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            // Mejor manejo de errores de Clerk
            $errorBody = $response->json();
            $errorMessage = 'Error desconocido de Clerk';
            
            if (isset($errorBody['errors']) && count($errorBody['errors']) > 0) {
                $error = $errorBody['errors'][0];
                $errorMessage = $error['message'] ?? $errorMessage;
                
                switch ($error['code'] ?? '') {
                    case 'form_password_pwned':
                        $errorMessage = 'Esta contraseña ha sido comprometida en filtraciones de datos. Por seguridad, usa una contraseña diferente.';
                        break;
                    case 'form_data_missing':
                        $errorMessage = 'Faltan datos requeridos: ' . implode(', ', $error['meta']['param_names'] ?? []);
                        break;
                    case 'form_identifier_exists':
                        $errorMessage = 'Este email ya está registrado.';
                        break;
                    case 'form_password_validation':
                        $errorMessage = 'La contraseña no cumple con los requisitos de seguridad.';
                        break;
                }
            }

            Log::error('Error de Clerk API:', [
                'status' => $response->status(),
                'error' => $errorBody,
                'sent_data' => $clerkData
            ]);

            return [
                'success' => false,
                'error' => $errorMessage
            ];

        } catch (\Exception $e) {
            Log::error('Excepción en ClerkService:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Error de conexión con Clerk: ' . $e->getMessage()
            ];
        }
    }

    public function deleteUser($clerkId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->delete("{$this->apiUrl}/users/{$clerkId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error eliminando usuario de Clerk:', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("{$this->apiUrl}/users", [
                'limit' => 1
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Conexión exitosa' : 'Error de conexión'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}