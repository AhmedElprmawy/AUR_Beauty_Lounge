<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $accessToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    /**
     * إرسال رسالة WhatsApp
     */
    public function sendMessage($to, $message, $isTest = false)
    {
        try {
            // ✅ للاختبار: لا ترسل رسائل حقيقية
            if ($isTest) {
                // Test mode: do not log message body or PII, only indicate the intended recipient
                Log::info('🧪 [TEST] WhatsApp message would be sent to: ' . $to);
                return ['success' => true, 'test' => true];
            }

            // ✅ إرسال الرسالة عبر API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/' . $this->phoneNumberId . '/messages', [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $this->formatPhoneNumber($to),
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

            if ($response->successful()) {
                // Avoid logging message content / PII. Log recipient and status only.
                Log::info('✅ WhatsApp message sent successfully to: ' . $to, ['status' => $response->status()]);
                return ['success' => true, 'response' => $response->json()];
            } else {
                // Log status and a short truncated response for debugging but avoid full message bodies/PII.
                $respBody = $response->body();
                $truncated = is_string($respBody) ? substr($respBody, 0, 1000) : null;
                Log::error('❌ WhatsApp API error', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response_snippet' => $truncated,
                ]);
                return ['success' => false, 'error' => $truncated ?: 'WhatsApp API error'];
            }

        } catch (\Exception $e) {
            Log::error('❌ WhatsApp send exception', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * تنسيق رقم الهاتف
     */
    protected function formatPhoneNumber($phone)
    {
        // Trim and keep only digits and leading + if present
        $phone = trim($phone);

        // If contains a leading +, remove it and keep digits
        if (strpos($phone, '+') === 0) {
            $normalized = preg_replace('/[^0-9]/', '', $phone);
            return $normalized;
        }

        // Otherwise, strip non-digit characters
        $digits = preg_replace('/\D+/', '', $phone);

        // Common Egyptian local format: 0XXXXXXXXX (10 digits) -> convert to 20XXXXXXXXX
        if (strlen($digits) === 10 && strpos($digits, '0') === 0) {
            return '20' . substr($digits, 1);
        }

        // If already appears to be in international format (11+ digits) just return digits
        return $digits;
    }

    /**
     * التحقق من إعدادات WhatsApp
     */
    public function isConfigured()
    {
        return !empty($this->apiUrl) && !empty($this->accessToken) && !empty($this->phoneNumberId);
    }
}