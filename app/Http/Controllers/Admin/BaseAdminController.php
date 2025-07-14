<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

abstract class BaseAdminController extends Controller
{
    // Abstract properties that must be defined in child classes
    protected $model;
    protected $viewPath;
    protected $routePrefix;
    protected $modelName;
    protected $primaryKey = 'id';

    /**
     * Get validation rules for store operation
     */
    abstract protected function getStoreValidationRules(): array;

    /**
     * Get validation rules for update operation
     */
    abstract protected function getUpdateValidationRules(): array;

    /**
     * Get additional data for index view
     */
    protected function getIndexData($items)
    {
        return compact('items');
    }

    /**
     * Get additional data for create view
     */
    protected function getCreateData()
    {
        return [];
    }

    /**
     * Get additional data for edit view
     */
    protected function getEditData($item)
    {
        return compact('item');
    }

    /**
     * Process data before storing
     */
    protected function processStoreData(array $validatedData, Request $request): array
    {
        return $validatedData;
    }

    /**
     * Process data before updating
     */
    protected function processUpdateData(array $validatedData, Request $request, $item): array
    {
        return $validatedData;
    }

    /**
     * Handle file upload
     */
    protected function handleFileUpload(Request $request, string $fieldName, string $path = 'images', string $prefix = ''): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);
        $filename = time() . '_' . $prefix . '_' . $file->getClientOriginalName();

        // Create directory if it doesn't exist
        $uploadPath = public_path($path);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $filename);
        return $path . '/' . $filename;
    }

    /**
     * Delete file
     */
    protected function deleteFile(?string $filePath): void
    {
        if ($filePath && file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query, Request $request)
    {
        // Default filters - can be overridden in child classes
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->model::query();
        $query = $this->applyFilters($query, $request);

        $items = $query->orderBy('created_at', 'desc')->get();
        $data = $this->getIndexData($items);

        return view("{$this->viewPath}.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->getCreateData();
        return view("{$this->viewPath}.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->getStoreValidationRules());

        DB::beginTransaction();

        try {
            $processedData = $this->processStoreData($validatedData, $request);
            $item = $this->model::create($processedData);

            DB::commit();

            return redirect()->route("{$this->routePrefix}.index")
                           ->with('success', ucfirst($this->modelName) . ' berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menyimpan ' . $this->modelName . '. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = $this->model::findOrFail($id);
        return view("{$this->viewPath}.show", compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $data = $this->getEditData($item);
        return view("{$this->viewPath}.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $validatedData = $request->validate($this->getUpdateValidationRules());

        DB::beginTransaction();

        try {
            $processedData = $this->processUpdateData($validatedData, $request, $item);
            $item->update($processedData);

            DB::commit();

            return redirect()->route("{$this->routePrefix}.index")
                           ->with('success', ucfirst($this->modelName) . ' berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui ' . $this->modelName . '. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $item = $this->model::findOrFail($id);

            // Delete associated files if image field exists
            if (isset($item->image)) {
                $this->deleteFile($item->image);
            }

            $item->delete();

            return redirect()->route("{$this->routePrefix}.index")
                           ->with('success', ucfirst($this->modelName) . ' berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus ' . $this->modelName . '. Silakan coba lagi.');
        }
    }
}
