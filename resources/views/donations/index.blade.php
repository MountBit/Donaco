<!DOCTYPE html>
<html lang="pt-br" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seja um doador(a)! - {{ config('app.name') }}</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/donations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="icon" href="{{ config('app.logo_icon_url') }}" type="image/png">
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <style>
        /* Estilos para o spinner de loading */
        .spinner-border {
            width: 1.2rem;
            height: 1.2rem;
            border-width: 0.15em;
        }

        /* Manter arredondamento do modal */
        .modal-content {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .modal-header.rounded-top {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        /* Transições suaves */
        .modal-header,
        .modal-body {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <!-- Fixed Header -->
    <header class="fixed-header">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-logo">
                    <img src="{{ config('app.logo_image_header_url') }}" alt="{{ config('app.name') }}" height="40">
                </div>
                @if (!empty($projectTotals) && count($projectTotals) > 0)
                    @if (count($projectTotals) > 1)
                        <div class="project-stats-carousel">
                            @foreach ($projectTotals as $projectId => $project)
                                <div class="project-stats header-stats">
                                    <div class="project-info">
                                        <div class="project-name" title="{{ $project['name'] }}">
                                            {{ $project['name'] }}</div>
                                        <div class="project-supporters">
                                            <div class="stat-label">Apoiadores</div>
                                            <div class="stat-value">{{ $project['total_donors'] }}</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Arrecadado</div>
                                        <div class="stat-value success">R$
                                            {{ number_format($project['total_amount'], 2, ',', '.') }}</div>
                                    </div>
                                    <div class="stat-item meta-stat">
                                        <div class="stat-label">Meta</div>
                                        <div class="stat-value">R$ {{ number_format($project['goal'], 2, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="stat-item progress-stat">
                                        <div class="stat-label">Progresso</div>
                                        <div
                                            class="stat-value {{ $project['progress'] >= 80 ? 'progress-success' : ($project['progress'] >= 50 ? 'progress-warning' : 'progress-danger') }}">
                                            {{ number_format($project['progress'], 2, ',', '.') }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="header-stats">
                            @foreach ($projectTotals as $project)
                                <div class="project-info">
                                    <div class="project-name" title="{{ $project['name'] }}">
                                        {{ $project['name'] }}
                                    </div>
                                    <div class="project-supporters">
                                        <div class="stat-label">Apoiadores</div>
                                        <div class="stat-value">{{ $project['total_donors'] }}</div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Arrecadado</div>
                                    <div class="stat-value success">R$
                                        {{ number_format($project['total_amount'], 2, ',', '.') }}</div>
                                </div>
                                <div class="stat-item meta-stat">
                                    <div class="stat-label">Meta</div>
                                    <div class="stat-value">R$ {{ number_format($project['goal'], 2, ',', '.') }}</div>
                                </div>
                                <div class="stat-item progress-stat">
                                    <div class="stat-label">Progresso</div>
                                    <div
                                        class="stat-value {{ $project['progress'] >= 80 ? 'progress-success' : ($project['progress'] >= 50 ? 'progress-warning' : 'progress-danger') }}">
                                        {{ number_format($project['progress'], 2, ',', '.') }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="header-stats">
                        <div class="project-info">
                            <div class="project-name" title="Sem Projeto Ativo">
                                Sem Projeto Ativo
                            </div>
                            <div class="project-supporters">
                                <div class="stat-label">Apoiadores</div>
                                <div class="stat-value">0</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Arrecadado</div>
                            <div class="stat-value success">R$ {{ number_format(0, 2, ',', '.') }}</div>
                        </div>
                        <div class="stat-item meta-stat">
                            <div class="stat-label">Meta</div>
                            <div class="stat-value">R$ {{ number_format(0, 2, ',', '.') }}</div>
                        </div>
                        <div class="stat-item progress-stat">
                            <div class="stat-label">Progresso</div>
                            <div class="stat-value progress-danger">
                                {{ number_format(0, 2, ',', '.') }}%
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-fluid px-4 py-5">
        <div class="row">
            <!-- Hero -->
            <div class="col-lg-9 mb-4">
                <div class="text-center">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="bg-gray-100 text-gray-800 text-center p-6 font-sans">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">🌟 Transforme sua gratidão em impacto real! 🌟
                        </h1>
                        <p class="text-lg leading-relaxed mb-6">
                            O mundo do open-source é construído por milhares de colaboradores apaixonados,
                            que dedicam tempo e talento para criar ferramentas que todos usamos — muitas vezes, sem
                            custo algum.
                            Mas sabe o que mantém esses projetos vivos?<br />
                            <strong class="font-semibold">O apoio de pessoas incríveis como você!</strong> 💡
                        </p>
                        <p class="text-lg leading-relaxed mb-6">
                            Sua doação, por menor que seja, ajuda a pagar servidores, café para os devs (importante!),
                            e a garantir que o projeto continue evoluindo. Escolha o projeto que já fez a diferença na
                            sua vida
                            e seja parte da mudança. 🌍✨
                        </p>
                        <p class="text-sm text-gray-500 mt-4">Cada contribuição é um agradecimento que fortalece toda a
                            comunidade. ❤️</p>
                    </div>

                    <div class="mt-4">
                        <h1 class="display-5 fw-bold text-body-emphasis font-circular-medium">Apoie-nos</h1>
                        <div class="col-lg-8 mx-auto">
                            <p class="lead mb-4">Sua doação nos mantem ativo.</p>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                <button type="button" class="btn btn-warning btn-lg px-4 rounded-4"
                                    onclick="openDonationModal()">Doar ❤️</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking -->
            <div class="col-lg-3 pe-4">
                @if ($rankingDonations)
                    <div class="card shadow-sm sticky-ranking" id="ranking-donations"
                        style="border-radius: 1rem; overflow: hidden; width: calc(25vw - 2rem);">
                        <div class="card-header bg-warning text-white text-center py-3" style="border: none;">
                            <h4 class="mb-0">Maiores Doadores 🏆</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($rankingDonations as $ranking)
                                <li class="list-group-item py-3 d-flex justify-content-between align-items-center"
                                    style="border-left: none; border-right: none;">
                                    <span class="fw-medium">{{ $ranking['nickname'] }}</span>
                                    <span class="badge bg-warning text-white px-3 py-2" style="border-radius: 2rem;">
                                        R$ {{ number_format($ranking['value'], 2, ',', ' ') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Doações Recentes -->
    @if (!empty($recentDonations))
        <div class="bg-soft-yellow py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h3 class="display-6 fw-bold mb-2">Doações Recentes</h3>
                    <div class="title-underline"></div>
                </div>

                <div class="row">
                    @foreach ($recentDonations as $donation)
                        <div class="col-md-4 mb-4">
                            <div class="card donation-card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $donation['avatar'] }}" alt="Avatar"
                                                class="rounded-circle" width="48" height="48">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-0 fw-bold">{{ $donation['nickname'] }}</h5>
                                            <p class="card-text text-muted small mb-0">
                                                {{ $donation['formatted_date'] }}</p>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-warning">{{ $donation['formatted_value'] }}</span>
                                        </div>
                                    </div>
                                    @if (!empty($donation['formatted_message']))
                                        <p class="message-text mt-3 {{ $donation['formatted_message']['class'] }}">
                                            {{ $donation['formatted_message']['text'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Doação -->
    <div class="modal fade" id="donationModal" tabindex="-1" role="dialog" aria-labelledby="donationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="donationModalLabel">
                        <i class="fas fa-hand-holding-heart"></i> Faça sua Doação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="donationForm" action="/donations" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Selecione um projeto</label>
                            <select class="form-select" id="project_id" name="project_id" required>
                                <option value="">Selecione um projeto</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" id="selected_payment_method" name="payment_method" value="">

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Método de Pagamento</label>
                            <div class="payment-methods">
                                @if (config('app.manual_payment_mode'))
                                    <div class="payment-method" data-method="manual"
                                        onclick="selectPaymentMethod('manual')">
                                        <i class="fas fa-qrcode"></i>
                                        <span>PIX Manual</span>
                                    </div>
                                @endif
                                <div class="payment-method" data-method="mercadopago"
                                    onclick="selectPaymentMethod('mercadopago')">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Mercado Pago</span>
                                </div>
                            </div>
                        </div>

                        <div id="manual-content" class="payment-content" style="display: none;">
                        </div>

                        <div id="mercadopago-content" class="payment-content" style="display: none;">
                        </div>

                        <div class="common-fields">
                            <!-- Campos comuns -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-4" style="background: #1E2129;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <h5 class="text-white mb-3">EMPRESA</h5>
                    <p class="text-white-50 mb-0">
                        Apoie o desenvolvimento do projeto Map-OS.<br>
                        Sua contribuição é fundamental para mantermos o projeto ativo e em constante evolução.
                    </p>
                </div>

                <div class="col-lg-3">
                    <h5 class="text-white mb-3">LINKS ÚTEIS</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="https://github.com/map-os/mapos"
                                class="text-white-50 text-decoration-none">Projeto Map-OS</a></li>
                        <li class="mb-2"><a href="https://github.com/RamonSilva20/mapos"
                                class="text-white-50 text-decoration-none">Projeto Map-OS Legado</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h5 class="text-white mb-3">REDES SOCIAIS</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>

                <div class="col-lg-3">
                    <h5 class="text-white mb-3">CONTATO</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 text-white-50"><i class="fas fa-envelope me-2"></i>suporte@mapos.com.br</li>
                        <li class="text-white-50"><i class="fab fa-github me-2"></i>github.com/map-os</li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-secondary mt-4 pt-4">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-white-50 mb-0">&copy; {{ date('Y') }} {{ config('app.empresa_name') }}. Todos os
                            direitos reservados.</p>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                            Baseado em
                            <a href="https://github.com/hnqca/QRCode-PIX-MercadoPago-php"
                                class="text-white-50 text-decoration-none github-tooltip" target="_blank">
                                <span class="tooltip-text">QRCode Pix MercadoPago - PHP</span>
                                <i class="fab fa-github fa-lg"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- Variáveis globais -->
    <script>
        const donationStatusRoute = "{{ secure_url(str_replace(url('/'), '', route('donations.status', ':externalReference'))) }}";
        const paymentCheckConfig = {
            interval: parseInt("{{ config('app.payment_check_interval', 15000) }}", 10),
            maxTime: parseInt("{{ config('app.payment_check_max_time', 600000) }}", 10),
        };
        const manualPaymentEnabled = Boolean("{{ config('app.manual_payment_mode') }}");

        const PIX_KEY = "{{ config('pix.manual_key') }}";
        const PIX_KEY_QR_CODE = "{{ DonationHelper::getPixKeyQrCode() }}";
        const PIX_KEY_PAYMENT_CODE = "{{ DonationHelper::getPixKeyPaymentCode() }}";
        const PIX_BANK = "{{ config('pix.manual_type') }}";
        const PIX_BENEFICIARY = "{{ config('pix.manual_name') }}";

        // Inicializar carrossel
        document.addEventListener('DOMContentLoaded', function() {
            const projectStats = document.querySelectorAll('.project-stats');

            // Só inicializa o carrossel se houver projetos
            if (projectStats && projectStats.length > 0) {
                let currentIndex = 0;

                function showNextStat() {
                    projectStats.forEach(stat => stat.classList.remove('active'));
                    projectStats[currentIndex].classList.add('active');
                    currentIndex = (currentIndex + 1) % projectStats.length;
                }

                showNextStat(); // Mostrar primeiro item
                setInterval(showNextStat, 5000); // Trocar a cada 5 segundos
            }
        }, { once: true });
    </script>
    <script src="{{ asset('assets/js/donations.js') }}"></script>
</body>

</html>
