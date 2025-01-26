<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Current Month Donations Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 overflow-hidden shadow-lg rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @if($donationGrowth > 0)
                            <span class="px-3 py-1 text-sm text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900 rounded-full">
                                +{{ $donationGrowth }}%
                            </span>
                        @elseif($donationGrowth < 0)
                            <span class="px-3 py-1 text-sm text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900 rounded-full">
                                {{ $donationGrowth }}%
                            </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mb-1">{{ $currentMonthDonations }}</h3>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Doações Aprovadas</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            {{ now()->format('F/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Year Total Amount Card -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 overflow-hidden shadow-lg rounded-xl p-6 border border-emerald-200 dark:border-emerald-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-emerald-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-emerald-900 dark:text-emerald-100 mb-1">
                            R$ {{ number_format($yearTotalAmount, 2, ',', '.') }}
                        </h3>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Valor Total {{ now()->year }}</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            Todas as doações aprovadas
                        </p>
                    </div>
                </div>

                <!-- Active Projects Card -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 overflow-hidden shadow-lg rounded-xl p-6 border border-amber-200 dark:border-amber-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-amber-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-amber-900 dark:text-amber-100 mb-1">{{ $activeProjects }}</h3>
                        <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Projetos Ativos</p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            Em andamento
                        </p>
                    </div>
                </div>

                <!-- Total Donors Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 overflow-hidden shadow-lg rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-purple-900 dark:text-purple-100 mb-1">{{ $totalDonors }}</h3>
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Total de Doadores</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            Doadores únicos
                        </p>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Approved Donations Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 overflow-hidden shadow-lg rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-indigo-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-indigo-900 dark:text-indigo-100 mb-1">{{ $totalApprovedDonations }}</h3>
                        <p class="text-sm font-medium text-indigo-700 dark:text-indigo-300">Total de Doações Aprovadas</p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">Desde o início</p>
                    </div>
                </div>

                <!-- Current Month Total Value Card -->
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900 dark:to-teal-800 overflow-hidden shadow-lg rounded-xl p-6 border border-teal-200 dark:border-teal-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-teal-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-teal-900 dark:text-teal-100 mb-1">R$ {{ number_format($currentMonthTotalValue, 2, ',', '.') }}</h3>
                        <p class="text-sm font-medium text-teal-700 dark:text-teal-300">Valor Aprovado do Mês</p>
                        <p class="text-xs text-teal-600 dark:text-teal-400 mt-1">{{ now()->format('F/Y') }}</p>
                    </div>
                </div>

                <!-- Total Pending Donations Card -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 overflow-hidden shadow-lg rounded-xl p-6 border border-orange-200 dark:border-orange-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-orange-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-orange-900 dark:text-orange-100 mb-1">{{ $totalPendingDonations }}</h3>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-300">Total de Doações Pendentes</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">Desde o início</p>
                    </div>
                </div>

                <!-- Current Month Pending Value Card -->
                <div class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900 dark:to-rose-800 overflow-hidden shadow-lg rounded-xl p-6 border border-rose-200 dark:border-rose-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-rose-500 bg-opacity-10">
                            <svg class="w-8 h-8 text-rose-600 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-rose-900 dark:text-rose-100 mb-1">R$ {{ number_format($currentMonthPendingValue, 2, ',', '.') }}</h3>
                        <p class="text-sm font-medium text-rose-700 dark:text-rose-300">Valor Pendente do Mês</p>
                        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ now()->format('F/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Valor Total por Projeto
                        </h3>
                    </div>
                    @if(count($chartData['labels']) > 0)
                        <div class="relative" style="height: 300px; margin-top: 1.5rem;">
                            <canvas id="projectsChart"></canvas>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            Nenhuma doação aprovada para exibir no gráfico
                        </p>
                    @endif
                </div>

                <!-- Recent Donations -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Doações Recentes</h3>
                        <a href="{{ route('admin.donations.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Ver todas</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentDonations as $donation)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-300 text-sm font-medium">
                                            {{ strtoupper(substr($donation->nickname ?? 'A', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $donation->nickname }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->project->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600 dark:text-green-400">R$ {{ number_format($donation->value, 2, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $donation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhuma doação recente</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/chart.js') }}"></script>
        <script>
            window.chartData = {
                labels: @json($chartData['labels']),
                data: @json($chartData['data'])
            };
        </script>
        <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @endpush
</x-app-layout>
