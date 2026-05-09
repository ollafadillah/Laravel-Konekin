<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Services\MlRecommendationService;
use App\Support\CreativeRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MlRecommendationController extends Controller
{
    public function index(MlRecommendationService $service)
    {
        $user = auth()->user();

        if (! $user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat mengakses rekomendasi kreator AI.');
        }

        $projects = Project::where('client_id', $user->id)
            ->whereIn('status', ['open', 'hired'])
            ->get();

        return view('recommendations.creative-workers', [
            'user' => $user,
            'serviceStatus' => $this->safeServiceStatus($service),
            'formOptions' => $this->formOptions(),
            'formValues' => $this->defaultFormValues(),
            'recommendationResult' => session('recommendation_result'),
            'projects' => $projects,
        ]);
    }

    public function hire(Request $request)
    {
        $request->validate([
            'project_id' => 'required|string',
            'creative_id' => 'required|string',
        ]);

        $project = Project::where('client_id', auth()->id())->findOrFail($request->project_id);
        $creative = User::where('type', 'creative_worker')->findOrFail($request->creative_id);

        $project->update([
            'selected_creative_id' => $creative->id,
            'selected_creative_name' => $creative->name,
            'selected_creative_avatar' => $creative->profile_photo,
            'status' => 'waiting_confirmation',
            'escrow_status' => 'pending',
        ]);

        return redirect()->route('dashboard.umkm')
            ->with('success', 'Undangan kerja sama telah dikirim ke ' . $creative->name . '. Silakan tunggu konfirmasi dari Kreator sebelum melanjutkan ke pembayaran.');
    }

    public function store(Request $request, MlRecommendationService $service)
    {
        $user = auth()->user();

        if (! $user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat mengakses rekomendasi kreator AI.');
        }

        $options = $this->formOptions();

        $validated = $request->validate([
            'omset' => ['required', 'string', 'max:30'],
            'laba' => ['required', 'string', 'max:30'],
            'aset' => ['required', 'string', 'max:30'],
            'jenis_usaha' => ['required', 'string', Rule::in($options['jenis_usaha'])],
            'marketplace' => ['required', 'string', Rule::in($options['marketplace'])],
            'status_legalitas' => ['required', 'string', Rule::in($options['status_legalitas'])],
            'tenaga_kerja_perempuan' => ['required', 'string', 'max:10'],
            'tenaga_kerja_laki_laki' => ['required', 'string', 'max:10'],
            'tahun_berdiri' => ['required', 'string', 'max:10'],
            'experience_level' => ['nullable', 'string', Rule::in($options['experience_levels'])],
            'min_budget' => ['nullable', 'string', 'max:30'],
            'top_n' => ['required', 'string', Rule::in($options['top_n'])],
        ], [
            'omset.required' => 'Omset wajib diisi.',
            'laba.required' => 'Laba wajib diisi.',
            'aset.required' => 'Aset wajib diisi.',
            'jenis_usaha.required' => 'Jenis usaha wajib dipilih.',
            'marketplace.required' => 'Marketplace wajib dipilih.',
            'status_legalitas.required' => 'Status legalitas wajib dipilih.',
            'tenaga_kerja_perempuan.required' => 'Jumlah tenaga kerja perempuan wajib diisi.',
            'tenaga_kerja_laki_laki.required' => 'Jumlah tenaga kerja laki-laki wajib diisi.',
            'tahun_berdiri.required' => 'Tahun berdiri wajib diisi.',
            'top_n.required' => 'Jumlah hasil rekomendasi wajib dipilih.',
        ]);

        try {
            $payload = $this->normalizePayload($validated);
            $response = $service->recommend($payload);
            $result = $this->buildResult($response, $payload);

            return redirect()
                ->route('rekomendasi.kreator')
                ->withInput($request->all())
                ->with('success', 'Rekomendasi kreator berhasil dibuat.')
                ->with('recommendation_result', $result);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('[MlRecommendationController] Recommendation failed: ' . $exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }
    }

    private function safeServiceStatus(MlRecommendationService $service): array
    {
        try {
            $status = $service->status();

            return [
                'available' => true,
                'status' => data_get($status, 'status', 'ok'),
                'models_loaded' => (bool) data_get($status, 'models_loaded', false),
                'artifacts_ready' => (bool) data_get($status, 'artifacts_ready', false),
                'message' => 'Flask ML service terhubung.',
            ];
        } catch (\Throwable $exception) {
            return [
                'available' => false,
                'status' => 'offline',
                'models_loaded' => false,
                'artifacts_ready' => false,
                'message' => 'Flask ML service belum aktif atau tidak bisa dijangkau.',
            ];
        }
    }

    private function formOptions(): array
    {
        return [
            'jenis_usaha' => [
                'Jasa',
                'Perdagangan',
                'Kesehatan',
                'Pendidikan',
                'Makanan & Minuman',
                'Fashion',
                'Perusahaan',
                'unknown',
            ],
            'marketplace' => [
                'Tokopedia',
                'Shopee',
                'Bukalapak',
                'Lazada',
                'Website Sendiri',
                'Tidak Ada',
                'unknown',
            ],
            'status_legalitas' => [
                'Terdaftar',
                'Belum Terdaftar',
                'unknown',
            ],
            'experience_levels' => [
                'Beginner',
                'Intermediate',
                'Expert',
            ],
            'top_n' => [3, 5, 10, 15],
        ];
    }

    private function defaultFormValues(): array
    {
        return [
            'omset' => null,
            'laba' => null,
            'aset' => null,
            'jenis_usaha' => 'Jasa',
            'marketplace' => 'Shopee',
            'status_legalitas' => 'Terdaftar',
            'tenaga_kerja_perempuan' => 0,
            'tenaga_kerja_laki_laki' => 0,
            'tahun_berdiri' => (int) now()->year,
            'experience_level' => null,
            'min_budget' => null,
            'top_n' => 5,
        ];
    }

    private function normalizePayload(array $validated): array
    {
        $payload = [
            'omset' => $this->parseNumber($validated['omset'], 'omset'),
            'laba' => $this->parseNumber($validated['laba'], 'laba'),
            'aset' => $this->parseNumber($validated['aset'], 'aset'),
            'jenis_usaha' => trim((string) $validated['jenis_usaha']),
            'marketplace' => trim((string) $validated['marketplace']),
            'status_legalitas' => trim((string) $validated['status_legalitas']),
            'tenaga_kerja_perempuan' => $this->parseNumber($validated['tenaga_kerja_perempuan'], 'tenaga_kerja_perempuan'),
            'tenaga_kerja_laki_laki' => $this->parseNumber($validated['tenaga_kerja_laki_laki'], 'tenaga_kerja_laki_laki'),
            'tahun_berdiri' => $this->parseNumber($validated['tahun_berdiri'], 'tahun_berdiri'),
            'experience_level' => blank($validated['experience_level'] ?? null) ? null : trim((string) $validated['experience_level']),
            'min_budget' => blank($validated['min_budget'] ?? null) ? null : $this->parseNumber($validated['min_budget'], 'min_budget'),
            'top_n' => $this->parseNumber($validated['top_n'], 'top_n'),
        ];

        if ($payload['tahun_berdiri'] < 1900 || $payload['tahun_berdiri'] > (int) now()->year) {
            throw ValidationException::withMessages([
                'tahun_berdiri' => 'Tahun berdiri harus berada di rentang yang masuk akal.',
            ]);
        }

        return $payload;
    }

    private function parseNumber(mixed $value, string $field): int
    {
        $clean = preg_replace('/\D+/', '', (string) $value);

        if ($clean === '') {
            throw ValidationException::withMessages([
                $field => 'Field ini harus diisi dengan angka.',
            ]);
        }

        return (int) $clean;
    }

    private function buildResult(array $response, array $payload): array
    {
        $recommendations = collect($response['recommendations'] ?? [])
            ->map(function ($item) {
                return $this->enrichRecommendation((array) $item);
            })
            ->values()
            ->all();

        return [
            'cluster' => data_get($response, 'cluster'),
            'cluster_label' => data_get($response, 'cluster_label', 'Cluster Rekomendasi'),
            'total_found' => (int) data_get($response, 'total_found', count($recommendations)),
            'input_summary' => $payload,
            'recommendations' => $recommendations,
        ];
    }

    private function enrichRecommendation(array $item): array
    {
        $matchedUser = $this->resolveCreativeWorker($item);
        $id = $matchedUser ? (string) $matchedUser->id : (string) data_get($item, 'id', '');
        $name = data_get($matchedUser, 'name', data_get($item, 'full_name', 'Creative Worker'));
        $role = $matchedUser
            ? CreativeRoles::display($matchedUser->creative_category)
            : data_get($item, 'job_category', 'Creative Worker');
        $skills = $this->normalizeSkills(data_get($matchedUser, 'skills', data_get($item, 'skills', '')));
        $similarity = (float) data_get($item, 'similarity_score', 0);
        $verified = (int) data_get($item, 'profile_verified', 0) === 1;

        return [
            'id' => $id,
            'full_name' => $name,
            'email' => data_get($matchedUser, 'email', data_get($item, 'email')),
            'profile_photo' => data_get($matchedUser, 'profile_photo') ?: $this->avatarUrl($name),
            'profile_url' => $matchedUser ? route('kreator.show', $matchedUser->id) : null,
            'city' => data_get($matchedUser, 'city', data_get($item, 'location')),
            'bio' => data_get($matchedUser, 'bio', ''),
            'display_role' => $role,
            'specific_role' => data_get($item, 'specific_role', $role),
            'skills' => $skills,
            'experience_level' => data_get($item, 'experience_level', data_get($matchedUser, 'creative_category')),
            'experience_years' => (float) data_get($item, 'experience_years', 0),
            'success_rate_job' => (float) data_get($item, 'success_rate_job', 0),
            'client_rating' => $matchedUser ? (float) $matchedUser->average_rating : (float) data_get($item, 'client_rating', 0),
            'rehire_rate' => (float) data_get($item, 'rehire_rate', 0),
            'jobs_completed' => $matchedUser ? (int) $matchedUser->completed_projects_count : (int) data_get($item, 'jobs_completed', 0),
            'min_budget_idr' => (float) data_get($item, 'min_budget_idr', 0),
            'hourly_rate_usd' => (float) data_get($item, 'hourly_rate_usd', 0),
            'profile_verified' => $verified,
            'verified_label' => $verified ? 'Terverifikasi' : 'Belum terverifikasi',
            'similarity_score' => round($similarity, 4),
            'match_percentage' => round($similarity * 100, 1),
        ];
    }

    private function resolveCreativeWorker(array $item): ?User
    {
        $id = data_get($item, 'id');

        if ($id !== null && $id !== '') {
            $matchedById = User::where('type', 'creative_worker')->find($id);

            if ($matchedById) {
                return $matchedById;
            }
        }

        $email = data_get($item, 'email');

        if (filled($email)) {
            return User::where('type', 'creative_worker')
                ->where('email', $email)
                ->first();
        }

        return null;
    }

    private function normalizeSkills(mixed $skills): array
    {
        if (is_array($skills)) {
            return array_values(array_filter(array_map('trim', $skills)));
        }

        $skills = trim((string) $skills);

        if ($skills === '') {
            return [];
        }

        $parts = preg_split('/[,;|]/', $skills) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    private function avatarUrl(string $name): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=2563EB&color=fff';
    }
}
