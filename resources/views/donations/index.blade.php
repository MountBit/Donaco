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
    <link rel="icon" href="{{ getenv('LOGO_ICON_URL') }}" type="image/png">
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
</head>

<body>
    <!-- Fixed Header -->
    <header class="fixed-header">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-logo">
                    <img src="{{ getenv('LOGO_IMAGE_HEADER_URL') }}" alt="{{ config('app.name') }}" height="40">
                </div>
                @if(count($projectTotals) > 1)
                <div class="project-stats-carousel">
                    @foreach($projectTotals as $projectId => $project)
                    <div class="project-stats header-stats">
                        <div class="project-info">
                            <div class="project-name" title="{{ $project['name'] }}">{{ $project['name'] }}</div>
                            <div class="project-supporters">
                                <div class="stat-label">Apoiadores</div>
                                <div class="stat-value">{{ $project['total_donors'] }}</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Arrecadado</div>
                            <div class="stat-value success">R$ {{ number_format($project['total_amount'], 2, ',', '.') }}</div>
                        </div>
                        <div class="stat-item meta-stat">
                            <div class="stat-label">Meta</div>
                            <div class="stat-value">R$ {{ number_format($project['goal'], 2, ',', '.') }}</div>
                        </div>
                        <div class="stat-item progress-stat">
                            <div class="stat-label">Progresso</div>
                            <div class="stat-value {{ $project['progress'] >= 80 ? 'progress-success' : ($project['progress'] >= 50 ? 'progress-warning' : 'progress-danger') }}">
                                {{ number_format($project['progress'], 2, ',', '.') }}%
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="header-stats">
                    <div class="project-info">
                        <div class="project-name" title="{{ $projectTotals[key($projectTotals)]['name'] ?? '' }}">
                            {{ $projectTotals[key($projectTotals)]['name'] ?? '' }}
                        </div>
                        <div class="project-supporters">
                            <div class="stat-label">Apoiadores</div>
                            <div class="stat-value">{{ $projectTotals[key($projectTotals)]['total_donors'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Arrecadado</div>
                        <div class="stat-value success">R$ {{ number_format($projectTotals[key($projectTotals)]['total_amount'] ?? 0, 2, ',', '.') }}</div>
                    </div>
                    <div class="stat-item meta-stat">
                        <div class="stat-label">Meta</div>
                        <div class="stat-value">R$ {{ number_format($projectTotals[key($projectTotals)]['goal'] ?? 80000, 2, ',', '.') }}</div>
                    </div>
                    <div class="stat-item progress-stat">
                        <div class="stat-label">Progresso</div>
                        <div class="stat-value {{ 
                                $projectTotals[key($projectTotals)]['progress'] >= 80 ? 'progress-success' : 
                                ($projectTotals[key($projectTotals)]['progress'] >= 50 ? 'progress-warning' : 'progress-danger') 
                            }}">
                            {{ number_format($projectTotals[key($projectTotals)]['progress'] ?? 0, 2, ',', '.') }}%
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
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">🌟 Transforme sua gratidão em impacto real! 🌟</h1>
                        <p class="text-lg leading-relaxed mb-6">
                            O mundo do open-source é construído por milhares de colaboradores apaixonados,
                            que dedicam tempo e talento para criar ferramentas que todos usamos — muitas vezes, sem custo algum.
                            Mas sabe o que mantém esses projetos vivos?<br />
                            <strong class="font-semibold">O apoio de pessoas incríveis como você!</strong> 💡
                        </p>
                        <p class="text-lg leading-relaxed mb-6">
                            Sua doação, por menor que seja, ajuda a pagar servidores, café para os devs (importante!),
                            e a garantir que o projeto continue evoluindo. Escolha o projeto que já fez a diferença na sua vida
                            e seja parte da mudança. 🌍✨
                        </p>
                        <p class="text-sm text-gray-500 mt-4">Cada contribuição é um agradecimento que fortalece toda a comunidade. ❤️</p>
                    </div>

                    <div class="mt-4">
                        <h1 class="display-5 fw-bold text-body-emphasis font-circular-medium">Apoie-nos</h1>
                        <div class="col-lg-8 mx-auto">
                            <p class="lead mb-4">Sua doação nos mantem ativo.</p>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                <button data-bs-toggle="modal" data-bs-target="#modal-donation"
                                    class="btn btn-warning btn-lg px-4 rounded-4">Doar ❤️</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking -->
            <div class="col-lg-3 pe-4">
                @if ($rankingDonations)
                <div class="card shadow-sm sticky-ranking" id="ranking-donations" style="border-radius: 1rem; overflow: hidden; width: calc(25vw - 2rem);">
                    <div class="card-header bg-warning text-white text-center py-3" style="border: none;">
                        <h4 class="mb-0">Maiores Doadores 🏆</h4>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($rankingDonations as $ranking)
                        <li class="list-group-item py-3 d-flex justify-content-between align-items-center" style="border-left: none; border-right: none;">
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

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($recentDonations as $donation)
                <div class="col">
                    <div class="card donation-card h-100 border-0 shadow-hover">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-wrapper me-3">
                                    <img class="avatar-img" src="{{ $donation['avatar'] }}" alt="Avatar" />
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $donation['nickname'] }}</h6>
                                    <small class="text-muted">{{ $donation['formatted_date'] }}</small>
                                </div>
                                <div class="donation-amount ms-3">
                                    <span class="badge bg-warning-soft text-warning">
                                        {{ $donation['formatted_value'] }}
                                    </span>
                                </div>
                            </div>

                            @if($donation['formatted_message'])
                            <p class="{{ $donation['formatted_message']['class'] }} message-text">
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

    <!-- Modal - Doação  -->
    <div class="modal fade" id="modal-donation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content border-0 shadow-lg donation-modal">
                <div class="modal-header border-0 bg-warning text-white py-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="modal-logo me-3">
                            <img src="{{ getenv('LOGO_IMAGE_URL') }}" alt="{{ config('app.name') }}" class="rounded-circle bg-white p-1" width="40" height="40">
                        </div>
                        <h5 class="modal-title flex-grow-1">Faça sua Doação</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Existing modal body content -->
                <div id="modal-body-payer" class="modal-body p-4">
                    <div class="payment-method-selector mb-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="payment-option" data-method="mercadopago">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="mercadopago" 
                                           value="mercadopago" 
                                           checked 
                                           required>
                                    <label for="mercadopago" class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('assets/images/mp-logo.png') }}" alt="Mercado Pago" height="40">
                                        <span class="mt-2">Mercado Pago</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-option" data-method="manual">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="manual" 
                                           value="manual" 
                                           required>
                                    <label for="manual" class="d-flex flex-column align-items-center">
                                        <i class="fas fa-qrcode fa-2x"></i>
                                        <span class="mt-2">PIX Manual</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="donationForm" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div id="alert-donation" class="alert alert-danger text-center d-none" role="alert"></div>

                        <div class="compact-form">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Apelido" required>
                                <label for="nickname">Apelido</label>
                            </div>

                            <div class="form-floating mb-2">
                                <select name="project_id" id="project_id" class="form-select" required>
                                    <option value="">Selecione um projeto</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <label for="project_id">Projeto</label>
                                <div class="invalid-feedback">
                                    Por favor, selecione um projeto.
                                </div>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="email" class="form-control" id="email" name="email" placeholder="nome@exemplo.com" required>
                                <label for="email">Email</label>
                                <div class="form-text text-muted small">
                                    <i class="fas fa-lock me-1"></i> Seu email não será compartilhado.
                                </div>
                            </div>

                            <div class="form-floating mb-2">
                                <textarea class="form-control" id="message" name="message" placeholder="Mensagem" style="height: 60px"></textarea>
                                <label for="message">Mensagem (opcional)</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold mb-1">Valor da doação</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">R$</span>
                                    <input type="text"
                                        class="form-control money"
                                        id="value"
                                        name="value"
                                        placeholder="0,00"
                                        required
                                        autocomplete="off"
                                        inputmode="numeric">
                                </div>
                            </div>

                            <div id="manual-payment-info" class="d-none mb-3">
                                <div class="pix-info-box p-3 bg-light rounded mb-3">
                                    <h6 class="mb-2">Dados para transferência PIX</h6>
                                    <p class="mb-1"><strong>Chave PIX:</strong> {{ env('PIX_KEY') }}</p>
                                    <p class="mb-1"><strong>Banco:</strong> {{ env('PIX_BANK') }}</p>
                                    <p class="mb-0"><strong>Beneficiário:</strong> {{ env('PIX_BENEFICIARY') }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Comprovante de Pagamento</label>
                                    <input type="file" 
                                        class="form-control" 
                                        name="proof_file" 
                                        id="proof_file" 
                                        accept=".pdf,.png,.jpg,.jpeg"
                                        data-required-if-manual="true">
                                    <div class="invalid-feedback">
                                        Por favor, envie um comprovante de pagamento.
                                    </div>
                                    <div class="form-text">Aceitos: PDF, PNG, JPG (máx. 2MB)</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning text-white fw-bold py-2">
                                    Continuar
                                </button>
                                <div class="text-center payment-method-info">
                                    <div class="mercadopago-info">
                                        <div class="d-flex align-items-center justify-content-center small text-muted">
                                            <img src="{{ asset('assets/images/mp-logo.png') }}" width="20" alt="Mercado Pago" class="me-1" />
                                            Pagamento via PIX com Mercado Pago
                                        </div>
                                    </div>
                                    <div class="manual-info d-none">
                                        <div class="d-flex align-items-center justify-content-center small text-muted">
                                            <i class="fas fa-qrcode me-1"></i>
                                            Pagamento via PIX Manual
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- No modal-body-payment -->
                <div id="modal-body-payment" class="modal-body p-4 d-none">
                    <div class="text-center mb-4">
                        <p class="text-muted mb-2">Escaneie o QR Code abaixo ou copie o código PIX</p>
                        
                        <div class="qr-code-container mb-2">
                            <img id="image-qrcode-pix" src="" alt="QR Code PIX" style="display: none; max-width: 200px; margin: 0 auto;">
                        </div>
                        
                        <div class="pix-code-container">
                            <div class="form-group">
                                <label for="code-pix" class="form-label">Copia e Cola PIX</label>
                                <div class="input-group">
                                    <textarea id="code-pix" class="form-control" rows="3" readonly></textarea>
                                    <button class="btn btn-outline-secondary position-relative" type="button" id="copyButton" title="Copiar código PIX">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de aprovação -->
                <div id="modal-body-approved" class="modal-body p-4 d-none">
                    <!-- Será preenchido dinamicamente -->
                </div>
            </div>
        </div>
    </div>
    <!--// Modal - Doação  -->

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
                        <li class="mb-2"><a href="https://github.com/map-os/mapos" class="text-white-50 text-decoration-none">Projeto Map-OS</a></li>
                        <li class="mb-2"><a href="https://github.com/RamonSilva20/mapos" class="text-white-50 text-decoration-none">Projeto Map-OS Legado</a></li>
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
                        <p class="text-white-50 mb-0">&copy; {{ date('Y') }} {{ env('EMPRESA_NAME') }}. Todos os direitos reservados.</p>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                            Baseado em
                            <a href="https://github.com/hnqca/QRCode-PIX-MercadoPago-php"
                                class="text-white-50 text-decoration-none github-tooltip"
                                target="_blank">
                                <span class="tooltip-text">QRCode Pix MercadoPago - PHP</span>
                                <i class="fab fa-github fa-lg"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const donationStoreUrl = "{{ route('donations.stored') }}";
        const donationStatusRoute = "{{ route('donations.status', ':externalReference') }}";
        const paymentCheckConfig = {
            interval: {{ config('app.payment_check_interval', 5000) }},
            maxTime: {{ config('app.payment_check_max_time', 300000) }}
        };
    </script>
    <script src="{{ asset('assets/js/donations.js') }}"></script>
</body>

</html>
