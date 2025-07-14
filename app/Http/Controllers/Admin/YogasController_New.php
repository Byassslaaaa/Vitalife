<?php

namespace App\Http\Controllers\Admin;

use App\Models\Yoga;
use App\Models\YogaDetailConfig;
use App\Models\YogaService;
use App\Models\YogaBooking;
use App\Traits\HandlesCrudOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class YogasController extends BaseAdminController
{
    use HandlesCrudOperations;

    protected $model = Yoga::class;
    protected $viewPath = 'admin.yogas';
    protected $routePrefix = 'admin.yogas';
    protected $modelName = 'yoga';

    /**
     * Get validation rules for store operation
     */
    protected function getStoreValidationRules(): array
    {
        return array_merge(
            $this->getBasicValidationRules(),
            $this->getServiceValidationRules(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'harga' => 'required|numeric|min:0',
                'class_type' => 'required|string|max:255',
            ]
        );
    }

    /**
     * Get validation rules for update operation
     */
    protected function getUpdateValidationRules(): array
    {
        return array_merge(
            $this->getBasicValidationRules(),
            $this->getServiceValidationRules(),
            [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'harga' => 'required|numeric|min:0',
                'class_type' => 'required|string|max:255',
            ]
        );
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query, Request $request)
    {
        $query = parent::applyFilters($query, $request);

        if ($request->filled('location')) {
            $query->where('alamat', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('class_type')) {
            $query->where('class_type', $request->class_type);
        }

        if ($request->filled('service')) {
            $query->whereHas('yogaServices', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->service . '%');
            });
        }

        return $query;
    }

    /**
     * Get additional data for index view
     */
    protected function getIndexData($items)
    {
        $yogas = $items->load(['detailConfig', 'yogaServices']);
        $statistics = $this->getStatistics($yogas, 'is_active');

        // Add yoga-specific statistics
        $statistics['average_price'] = $yogas->avg('harga');
        $statistics['class_types'] = $yogas->groupBy('class_type')->count();

        return compact('yogas', 'statistics');
    }

    /**
     * Process data before storing
     */
    protected function processStoreData(array $validatedData, Request $request): array
    {
        $validatedData = $this->processCommonFormData($validatedData);

        // Handle is_active instead of is_open for yoga
        $validatedData['is_active'] = isset($validatedData['is_open']) ? (bool)$validatedData['is_open'] : false;
        unset($validatedData['is_open']);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->handleFileUpload(
                $request,
                'image',
                'mainvita/public/images',
                'yoga'
            );
        }

        // Handle services data
        if ($request->has('services')) {
            $validatedData['services'] = $this->processServicesData($request, 'mainvita/public/images/services');
        }

        return $validatedData;
    }

    /**
     * Process data before updating
     */
    protected function processUpdateData(array $validatedData, Request $request, $item): array
    {
        $validatedData = $this->processCommonFormData($validatedData);

        // Handle is_active instead of is_open for yoga
        $validatedData['is_active'] = isset($validatedData['is_open']) ? (bool)$validatedData['is_open'] : false;
        unset($validatedData['is_open']);

        // Handle image update
        $newImagePath = $this->handleImageUpdate($request, $item, 'image', 'mainvita/public/images');
        if ($newImagePath) {
            $validatedData['image'] = $newImagePath;
        }

        // Handle services data
        if ($request->has('services')) {
            $validatedData['services'] = $this->processServicesData($request, 'mainvita/public/images/services');
        }

        return $validatedData;
    }

    /**
     * Get statistics using is_active instead of is_open
     */
    protected function getStatistics($items, $statusField = 'is_active')
    {
        return parent::getStatistics($items, $statusField);
    }
}
