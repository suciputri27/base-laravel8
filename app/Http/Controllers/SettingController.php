<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): View
    {
        $setting = $this->settingService->getSetting();

        return view('setting.index', compact('setting'));
    }

    public function update(SettingRequest $request): JsonResponse
    {
        $this->settingService->save($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan website berhasil disimpan.',
        ]);
    }
}
