<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesCrudOperations
{
    /**
     * Common validation rules for basic fields
     */
    protected function getBasicValidationRules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'waktuBuka' => 'nullable|array',
            'waktuBuka.*' => 'nullable|string',
            'maps' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'nullable|boolean',
        ];
    }

    /**
     * Service validation rules
     */
    protected function getServiceValidationRules(): array
    {
        return [
            'services' => 'nullable|array',
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'required_with:services|string',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.duration' => 'nullable|string',
            'services.*.category' => 'nullable|string',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Handle services data processing
     */
    protected function processServicesData(Request $request, string $uploadPath = 'images/services'): array
    {
        $services = [];

        if ($request->has('services')) {
            foreach ($request->services as $index => $service) {
                $serviceData = [
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'price' => $service['price'] ?? null,
                    'duration' => $service['duration'] ?? null,
                    'category' => $service['category'] ?? null,
                ];

                // Handle service image upload
                if ($request->hasFile("services.{$index}.image")) {
                    $serviceImagePath = $this->handleFileUpload(
                        $request,
                        "services.{$index}.image",
                        $uploadPath,
                        "service_{$index}"
                    );
                    $serviceData['image'] = $serviceImagePath;
                }

                $services[] = $serviceData;
            }
        }

        return $services;
    }

    /**
     * Get statistics data for index view
     */
    protected function getStatistics($items, $statusField = 'is_open')
    {
        $total = $items->count();
        $active = $items->where($statusField, true)->count();
        $inactive = $items->where($statusField, false)->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'percentage_active' => $total > 0 ? round(($active / $total) * 100, 1) : 0
        ];
    }

    /**
     * Format waktu buka data
     */
    protected function formatWaktuBuka($waktuBuka): array
    {
        if (is_string($waktuBuka)) {
            // If it's a JSON string, decode it
            $decoded = json_decode($waktuBuka, true);
            return is_array($decoded) ? $decoded : [$waktuBuka];
        }

        if (is_array($waktuBuka)) {
            return $waktuBuka;
        }

        return [];
    }

    /**
     * Process common form data
     */
    protected function processCommonFormData(array $validatedData): array
    {
        // Ensure is_open is boolean
        $validatedData['is_open'] = isset($validatedData['is_open']) ? (bool)$validatedData['is_open'] : false;

        // Format waktu buka
        if (isset($validatedData['waktuBuka'])) {
            $validatedData['waktuBuka'] = $this->formatWaktuBuka($validatedData['waktuBuka']);
        }

        return $validatedData;
    }

    /**
     * Handle image update (delete old, upload new)
     */
    protected function handleImageUpdate(Request $request, $item, string $fieldName = 'image', string $uploadPath = 'images'): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        // Delete old image if exists
        if (isset($item->{$fieldName})) {
            $this->deleteFile($item->{$fieldName});
        }

        // Upload new image
        return $this->handleFileUpload($request, $fieldName, $uploadPath);
    }

    /**
     * Generate breadcrumbs for views
     */
    protected function getBreadcrumbs(string $current, array $additional = []): array
    {
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['name' => ucfirst($this->modelName), 'url' => route("{$this->routePrefix}.index")],
        ];

        foreach ($additional as $crumb) {
            $breadcrumbs[] = $crumb;
        }

        $breadcrumbs[] = ['name' => $current, 'url' => null];

        return $breadcrumbs;
    }
}
