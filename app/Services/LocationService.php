<?php

namespace App\Services;

class LocationService
{
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    public function getAddressFromCoordinates($lat, $lng)
    {
        // Reverse geocoding using Nominatim (free, no API key)
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BookEase/1.0');
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            return $data['display_name'] ?? null;
        }
        
        return null;
    }
    
    public function getCurrentLocationFromIP()
    {
        // Free IP to location (no API key)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '8.8.8.8';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ip}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] == 'success') {
                return [
                    'latitude' => $data['lat'],
                    'longitude' => $data['lon'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                ];
            }
        }
        
        return null;
    }
}