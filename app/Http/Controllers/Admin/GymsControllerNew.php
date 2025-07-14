<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gym;
use App\Models\GymDetail;
use App\Models\GymService;
use App\Models\GymBooking;
use App\Traits\HandlesCrudOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GymsController extends BaseAdminController
{
    use HandlesCrudOperations;

    protected $model = Gym::class;
    protected $viewPath = 'admin.gyms';
    protected $routePrefix = 'admin.gyms';
    protected $modelName = 'gym';

    /**
     * Get validation rules for store operation
     */
    protected function getStoreValidationRules(): array
    {
        return array_merge(
            $this->getBasicValidationRules(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'services' => 'required|array|min:3|max:3', // Gym requires exactly 3 services
                'services.*.name' => 'required|string|max:255',
                'services.*.description' => 'required|string',
                'services.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'services' => 'required|array|min:3|max:3', // Gym requires exactly 3 services
                'services.*.name' => 'required|string|max:255',
                'services.*.description' => 'required|string',
                'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        return $query;
    }

    /**
     * Get additional data for index view
     */
    protected function getIndexData($items)
    {
        $gyms = $items->load(['gymDetail']);
        $statistics = $this->getStatistics($gyms);

        // Add gym-specific statistics
        $statistics['with_detail'] = $gyms->whereNotNull('gymDetail')->count();

        return compact('gyms', 'statistics');
    }

    /**
     * Process data before storing
     */
    protected function processStoreData(array $validatedData, Request $request): array
    {
        $validatedData = $this->processCommonFormData($validatedData);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->handleFileUpload(
                $request,
                'image',
                'images',
                'gym'
            );
        }

        // Handle services data for gym (stores as JSON in services column)
        if ($request->has('services')) {
            $services = [];
            foreach ($request->services as $index => $service) {
                $serviceData = [
                    'name' => $service['name'],
                    'description' => $service['description']
                ];

                if ($request->hasFile("services.{$index}.image")) {
                    $serviceImagePath = $this->handleFileUpload(
                        $request,
                        "services.{$index}.image",
                        'images/services',
                        "service_{$index}"
                    );
                    $serviceData['image'] = $serviceImagePath;
                }

                $services[] = $serviceData;
            }
            $validatedData['services'] = $services;
        }

        return $validatedData;
    }

    /**
     * Process data before updating
     */
    protected function processUpdateData(array $validatedData, Request $request, $item): array
    {
        $validatedData = $this->processCommonFormData($validatedData);

        // Handle image update
        $newImagePath = $this->handleImageUpdate($request, $item, 'image', 'images');
        if ($newImagePath) {
            $validatedData['image'] = $newImagePath;
        }

        // Handle services data for gym
        if ($request->has('services')) {
            // Delete old service images first
            if ($item->services && is_array($item->services)) {
                foreach ($item->services as $service) {
                    if (isset($service['image'])) {
                        $this->deleteFile($service['image']);
                    }
                }
            }

            $services = [];
            foreach ($request->services as $index => $service) {
                $serviceData = [
                    'name' => $service['name'],
                    'description' => $service['description']
                ];

                if ($request->hasFile("services.{$index}.image")) {
                    $serviceImagePath = $this->handleFileUpload(
                        $request,
                        "services.{$index}.image",
                        'images/services',
                        "service_{$index}"
                    );
                    $serviceData['image'] = $serviceImagePath;
                }

                $services[] = $serviceData;
            }
            $validatedData['services'] = $services;
        }

        return $validatedData;
    }

    /**
     * Delete gym with services cleanup
     */
    public function destroy($id)
    {
        try {
            $gym = $this->model::findOrFail($id);

            // Delete associated files
            if (isset($gym->image)) {
                $this->deleteFile($gym->image);
            }

            // Delete services images if stored in services JSON
            if ($gym->services && is_array($gym->services)) {
                foreach ($gym->services as $service) {
                    if (isset($service['image'])) {
                        $this->deleteFile($service['image']);
                    }
                }
            }

            $gym->delete();

            return redirect()->route("{$this->routePrefix}.index")
                           ->with('success', ucfirst($this->modelName) . ' berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus ' . $this->modelName . '. Silakan coba lagi.');
        }
    }
}
