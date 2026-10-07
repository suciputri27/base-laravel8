<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SkmService
{
    protected string $baseUrl = 'https://rangkiang.agamkab.go.id/api/ikm';

    /**
     * Kode instansi Disdukcapil Kabupaten Agam.
     * Kalau ternyata ajaxDataIKM butuh format kode beda (angka, bukan
     * "OPD-X"), ganti nilainya khusus di method getDataIKM() di bawah.
     */
    protected string $kodeInstansi = 'OPD-9';

    /**
     * Daftar pertanyaan + pilihan jawaban untuk form survey.
     */
    public function getSurvei(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ajaxGetSurvei");
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error('SkmService::getSurvei gagal: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Submit jawaban survey.
     * $nilai = string angka dipisah koma, urutannya HARUS sama persis
     * dengan urutan pertanyaan dari getSurvei(), contoh: "4,3,4,2,3,4,4,3,4"
     */
    public function insertPenilaian(array $data): bool
    {
        try {
            $payload = array_merge($data, [
                'kode_instansi' => $this->kodeInstansi,
            ]);

            // Diasumsikan format form-urlencoded (umum dipakai endpoint
            // ajax CodeIgniter). Kalau API-nya ternyata minta JSON,
            // ganti ->asForm() jadi hapus baris itu (default Http::post
            // kirim JSON).
            $response = Http::timeout(10)->asForm()->post(
                "{$this->baseUrl}/ajaxInsertPenilaian",
                $payload
            );

            return $response->body();
        } catch (\Throwable $e) {
            Log::error('SkmService::insertPenilaian gagal: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rekap nilai per unsur (buat grafik) + skor total IKM.
     */
    public function getGrafikPenilaian(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ajaxGrafikPenilaian", [
                'kode_instansi' => $this->kodeInstansi,
            ]);
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error('SkmService::getGrafikPenilaian gagal: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Jumlah responden per unsur per nilai (1-4).
     */
    public function getCountDetailUnsur(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ajaxCountDetailUnsur", [
                'kode_instansi' => $this->kodeInstansi,
            ]);
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error('SkmService::getCountDetailUnsur gagal: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Statistik demografi responden (jenis kelamin, pendidikan, pekerjaan, usia).
     */
    public function getCountSurveyor(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ajaxCountSurveyor", [
                'kode_instansi' => $this->kodeInstansi,
            ]);
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error('SkmService::getCountSurveyor gagal: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Data IKM mentah per responden + nilai tertimbang per unsur.
     */
    public function getDataIKM(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ajaxDataIKM", [
                'kode_instansi' => $this->kodeInstansi,
            ]);
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error('SkmService::getDataIKM gagal: ' . $e->getMessage());
            return [];
        }
    }
}
