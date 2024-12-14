<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Http\Requests\UpdateDonationRequest;
use App\Services\MercadoPagoService;
use App\Repositories\DonationRepository;
use App\Http\Requests\DonationRequest;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;

class DonationController extends Controller
{
    protected $mercadoPagoService;
    protected $donationRepository;
    protected $projectRepository;

    public function __construct(
        MercadoPagoService $mercadoPagoService,
        DonationRepository $donationRepository,
        ProjectRepository $projectRepository
    ) {
        $this->mercadoPagoService = $mercadoPagoService;
        $this->donationRepository = $donationRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca as doações recentes aprovadas
        $recentDonations = $this->donationRepository->getRecentApprovedDonations();

        // Formata os dados das doações
        $recentDonations = $recentDonations->map(function ($donation) {
            $value = is_numeric($donation['value']) ? $donation['value'] : 0;
            
            return [
                'id' => $donation['id'],
                'nickname' => $donation['nickname'],
                'value' => $value,
                'formatted_value' => 'R$ ' . number_format($value, 2, ',', '.'),
                'formatted_date' => Carbon::parse($donation['created_at'])->format('d/m/y'),
                'formatted_message' => $this->formatDonationMessage($donation),
                'avatar' => asset('assets/images/user-placeholder.png'),
                'project_id' => $donation['project_id']
            ];
        });

        // Gera o ranking das doações
        $rankingDonations = $this->donationRepository->getRankingDonations($recentDonations);

        // Busca todos os projetos para popular o select
        $projects = $this->projectRepository->getAllProjects();

        // Calcula totais por projeto
        $projectTotals = [];
        foreach ($projects as $project) {
            $projectDonations = $recentDonations->where('project_id', $project->id);
            $totalAmount = $projectDonations->sum('value');
            $totalDonors = $projectDonations->unique('nickname')->count();
            $goal = $project->goal ?? 80000.00; // Valor padrão caso não tenha meta definida
            $progress = ($totalAmount / $goal) * 100;

            $projectTotals[$project->id] = [
                'name' => $project->name,
                'total_amount' => $totalAmount,
                'total_donors' => $totalDonors,
                'goal' => $goal,
                'progress' => $progress
            ];
        }

        return view('donations.index', compact('recentDonations', 'rankingDonations', 'projects', 'projectTotals'));
    }

    /**
     * Format donation message based on visibility settings
     */
    private function formatDonationMessage($donation)
    {
        if (!$donation['message']) {
            return null;
        }

        if ($donation['message_hidden']) {
            return [
                'text' => __('messages.removed_message'),
                'class' => 'text-sm text-gray-500 dark:text-gray-400 italic'
            ];
        }

        return [
            'text' => $donation['message'],
            'class' => 'text-sm text-gray-700 dark:text-gray-300'
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Debug do valor recebido
            \Log::info('Valor recebido:', [
                'raw_value' => $request->input('value'),
                'validated_value' => $validated['value']
            ]);

            // Converter valor para formato correto
            $value = (float) $validated['value'];
            
            if ($value <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'O valor da doação deve ser maior que zero'
                ], 422);
            }

            $externalReference = bin2hex(random_bytes(16));
            
            // Processar o arquivo de comprovante se for pagamento manual
            $proofFile = null;
            if ($validated['payment_method'] === 'manual' && $request->hasFile('proof_file')) {
                try {
                    $proofFile = $request->file('proof_file')->store('proof_files', 'public');
                } catch (\Exception $e) {
                    \Log::error('Erro ao salvar arquivo:', ['error' => $e->getMessage()]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Erro ao salvar o comprovante. Por favor, tente novamente.'
                    ], 500);
                }
            }

            // Criar a doação
            $donationData = [
                'project_id' => $validated['project_id'],
                'nickname' => htmlspecialchars($validated['nickname']),
                'email' => $validated['email'],
                'message' => isset($validated['message']) ? htmlspecialchars($validated['message']) : null,
                'value' => $value,
                'external_reference' => $externalReference,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'proof_file' => $proofFile,
            ];

            $donation = $this->donationRepository->create($donationData);

            if (!$donation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao salvar doação'
                ], 500);
            }

            // Se for pagamento manual, retornar sucesso
            if ($validated['payment_method'] === 'manual') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Doação registrada com sucesso! Aguardando aprovação.',
                    'payment_method' => 'manual'
                ]);
            }

            // Se for Mercado Pago, continuar com o fluxo existente
            try {
                $paymentData = $this->mercadoPagoService->createPayment(
                    $validated['nickname'],
                    $validated['email'],
                    $value, // Valor já está como float
                    $externalReference
                );

                return response()->json([
                    'status' => 'success',
                    'code' => $paymentData['qr_code'],
                    'qr_code_base64' => $paymentData['qr_code_base64'],
                    'ticket_url' => $paymentData['ticket_url'],
                    'external_reference' => $externalReference,
                    'payment_method' => 'mercadopago'
                ]);
            } catch (\Exception $e) {
                \Log::error('Erro Mercado Pago:', [
                    'error' => $e->getMessage(),
                    'value_type' => gettype($value),
                    'value' => $value
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao criar pagamento: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao criar doação:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar doação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        //
    }
}
