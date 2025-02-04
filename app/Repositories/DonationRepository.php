<?php

namespace App\Repositories;

use App\Models\Donation;
use App\Models\Project;
use Carbon\Carbon;

class DonationRepository
{
    public function getRecentApprovedDonations($limit = 5)
    {
        return Donation::with('project')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($donation) {
                return [
                    'nickname' => $donation->nickname,
                    'value' => $donation->value,
                    'formatted_value' => 'R$ ' . number_format($donation->value, 2, ',', '.'),
                    'formatted_date' => Carbon::parse($donation->created_at)->format('d/m/y'),
                    'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($donation->email))) . '?d=mp',
                    'formatted_message' => $donation->message ? [
                        'text' => $donation->message_hidden ? 'Esta mensagem foi removida por violar as regras da comunidade.' : $donation->message,
                        'class' => $donation->message_hidden ? 'text-muted fst-italic' : ''
                    ] : null
                ];
            });
    }

    public function getRankingDonations($limit = 5)
    {
        return Donation::with('project')
            ->where('status', 'approved')
            ->orderByDesc('value')
            ->take($limit)
            ->get()
            ->map(function ($donation) {
                return [
                    'nickname' => $donation->nickname,
                    'value' => $donation->value,
                    'formatted_value' => 'R$ ' . number_format($donation->value, 2, ',', '.'),
                    'formatted_date' => Carbon::parse($donation->created_at)->format('d/m/y'),
                    'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($donation->email))) . '?d=mp'
                ];
            });
    }

    public function getAllApprovedDonations()
    {
        return Donation::where('status', 'approved')->get();
    }

    public function create(array $data)
    {
        return Donation::create($data);
    }

    public function update(Donation $donation, array $data)
    {
        $donation->update($data);
        return $donation;
    }

    public function delete(Donation $donation)
    {
        return $donation->delete();
    }

    /**
     * Gera um avatar usando Gravatar ou fallback para o email do doador
     *
     * @param string $nickname
     * @return string
     */
    protected function generateAvatar($email)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?d=mp';
    }

    public function getRecentDonations($limit = 6)
    {
        $donations = Donation::where('status', 'approved')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($donation) {
                $formattedMessage = $donation->message ? [
                    'text' => $donation->message_hidden ?
                        'Esta mensagem foi removida por violar as regras da comunidade.' :
                        $donation->message,
                    'class' => $donation->message_hidden ? 'text-muted fst-italic' : ''
                ] : null;

                return [
                    'nickname' => $donation->nickname,
                    'value' => $donation->value,
                    'formatted_value' => 'R$ ' . number_format($donation->value, 2, ',', '.'),
                    'formatted_date' => $donation->created_at->format('d/m/Y H:i'),
                    'avatar' => $this->generateAvatar($donation->email),
                    'message' => $donation->message,
                    'message_hidden' => $donation->message_hidden,
                    'message_hidden_reason' => $donation->message_hidden_reason,
                    'formatted_message' => $formattedMessage
                ];
            });

        return $donations;
    }

    public function getProjectTotals()
    {
        // Primeiro, obtém todos os projetos ativos
        $projects = Project::where('is_active', true)->get();

        // Obtém as doações aprovadas
        $donations = Donation::with('project')
            ->where('status', 'approved')
            ->selectRaw('project_id, SUM(value) as total_value, COUNT(DISTINCT nickname) as total_donors')
            ->groupBy('project_id')
            ->get();

        // Cria um array com todos os projetos, mesmo os que não têm doações
        return $projects->mapWithKeys(function ($project) use ($donations) {
            $donation = $donations->firstWhere('project_id', $project->id);
            $totalValue = $donation ? $donation->total_value : 0;
            $totalDonors = $donation ? $donation->total_donors : 0;
            $progress = $project->goal > 0 ? ($totalValue / $project->goal) * 100 : 0;

            return [
                $project->id => [
                    'name' => $project->name,
                    'total_amount' => $totalValue,
                    'total_donors' => $totalDonors,
                    'goal' => $project->goal,
                    'progress' => $progress,
                    'formatted_total' => 'R$ ' . number_format($totalValue, 2, ',', '.'),
                    'formatted_goal' => 'R$ ' . number_format($project->goal, 2, ',', '.')
                ]
            ];
        });
    }

    /* public function getRankingDonations($limit = 5)
     {
         $donations = Donation::where('status', 'approved')
             ->selectRaw('nickname, SUM(value) as total_value')
             ->groupBy('nickname')
             ->orderByDesc('total_value')
             ->take($limit)
             ->get();

         return $donations->map(function ($donation) {
             return [
                 'nickname' => $donation->nickname,
                 'value' => $donation->total_value
             ];
         });
     }*/
}
