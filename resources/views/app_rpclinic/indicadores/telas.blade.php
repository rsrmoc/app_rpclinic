@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo" style="width: auto;">
        <a href="javascript:;" class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                 alt="Logo" 
                 style="height: 60px; width: auto;" 
                 class="">
        </a>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appIndicadores" style="padding-bottom: 80px;">
        
        <!-- Header da Página -->
        <div class="d-flex align-items-center justify-content-between mb-4 px-2">
            <div>
                <h5 class="mb-0 text-slate-800 font-extrabold" style="font-size: 1.5rem;">Indicadores</h5>
                <small class="text-teal-600 font-medium">Performance e Resultados</small>
            </div>
            <div class="bg-slate-100 rounded-xl p-2 border border-slate-200 text-slate-500 cursor-pointer hover:bg-slate-200 transition-all">
                <i class="bi bi-filter text-xl"></i>
            </div>
        </div>

        <!-- Section: Resumo Rápido -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card glass-card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-graph-up-arrow text-emerald-600 p-1.5 bg-emerald-50 rounded-lg"></i>
                            <span class="text-slate-500 text-xs font-bold uppercase">Total Hoje</span>
                        </div>
                        <h3 class="mb-0 text-slate-800 font-extrabold">{{ $totalHoje }}</h3>
                        <small class="text-emerald-600 text-xs font-bold bg-emerald-50 px-1 rounded">+12% vs ontem</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card glass-card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-wallet2 text-purple-600 p-1.5 bg-purple-50 rounded-lg"></i>
                            <span class="text-slate-500 text-xs font-bold uppercase">Faturamento</span>
                        </div>
                        <h3 class="mb-0 text-slate-800 font-extrabold">R$ 4.2k</h3>
                        <small class="text-purple-600 text-xs font-bold bg-purple-50 px-1 rounded">Meta: R$ 5k</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Gráfico de Pizza (Comparativo) -->
        <div class="card glass-card mb-4 overflow-hidden border-0">
            <div class="card-header bg-transparent border-bottom border-slate-100 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-slate-800 font-bold">Distribuição por Convênio</h6>
                <button class="btn btn-sm btn-icon text-slate-400 hover:text-teal-600"><i class="bi bi-three-dots-vertical"></i></button>
            </div>
            <div class="card-body p-3 position-relative">
                <div id="chart-pizza" style="min-height: 300px;"></div>
            </div>
        </div>

        <!-- Section: Gráfico de Barras (Evolução) -->
        <div class="card glass-card mb-4 overflow-hidden border-0">
            <div class="card-header bg-transparent border-bottom border-slate-100 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-slate-800 font-bold">Performance Mensal</h6>
                <select class="form-select form-select-sm bg-slate-50 border-slate-200 text-slate-600 text-xs w-auto font-bold">
                    <option>Últimos 6 meses</option>
                    <option>Este Ano</option>
                </select>
            </div>
            <div class="card-body p-3">
                <div id="chart-barras" style="min-height: 300px;"></div>
            </div>
        </div>

        <!-- Section: Lista Rápida (Exemplo) -->
        <div class="card glass-card border-0">
             <div class="card-header bg-transparent border-bottom border-slate-100 p-3">
                <h6 class="mb-0 text-slate-800 font-bold">Top Procedimentos</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent border-slate-100 px-3 py-3 d-flex justify-content-between align-items-center text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                        <span class="flex items-center"><i class="bi bi-circle-fill text-teal-400 text-[0.6rem] me-3"></i>Consulta Clínica</span>
                        <span class="badge bg-teal-50 text-teal-700 rounded-pill font-bold border border-teal-100">145</span>
                    </li>
                    <li class="list-group-item bg-transparent border-slate-100 px-3 py-3 d-flex justify-content-between align-items-center text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                        <span class="flex items-center"><i class="bi bi-circle-fill text-purple-400 text-[0.6rem] me-3"></i>Exames Laboratoriais</span>
                        <span class="badge bg-purple-50 text-purple-700 rounded-pill font-bold border border-purple-100">98</span>
                    </li>
                    <li class="list-group-item bg-transparent border-transparent px-3 py-3 d-flex justify-content-between align-items-center text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                        <span class="flex items-center"><i class="bi bi-circle-fill text-rose-400 text-[0.6rem] me-3"></i>Retornos</span>
                        <span class="badge bg-rose-50 text-rose-700 rounded-pill font-bold border border-rose-100">65</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <!--end to page content-->
@endsection

@push('styles')
    <style>
        /* Estilos Clean Card */
        .glass-card {
            background: #ffffff;
            border: 1px solid #e2e8f0; /* slate-200 */
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: #0d9488; /* teal brand color */
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }

        /* Ajustes no ApexCharts para Light Mode */
        .apexcharts-tooltip {
            background: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
        .apexcharts-tooltip-title {
            background: #f1f5f9 !important; /* Slate 100 */
            border-bottom: 1px solid #cbd5e1 !important;
            font-family: inherit !important;
            color: #0f172a !important;
        }
        .apexcharts-text {
            fill: #64748b !important; /* Slate 500 */
            font-weight: 600 !important;
        }
        
        .apexcharts-legend-text {
             color: #475569 !important; /* Slate 600 */
             font-weight: 600 !important;
        }
        
        .apexcharts-menu {
             background: #fff !important;
             border: 1px solid #e2e8f0 !important;
             color: #334155 !important;
        }
        .apexcharts-menu-item:hover {
            background: #f1f5f9 !important;
        }

    </style>
@endpush

@push('scripts')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const cdProfissional = {{ auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
        // indicadores.js removido pois não é usado aqui, lógica inline abaixo
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            var pizzaSeries = @json($pizzaSeries); 
            var pizzaLabels = @json($pizzaLabels);

            var optionsPizza = {
                series: pizzaSeries,
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                labels: pizzaLabels,
                colors: ['#2dd4bf', '#a78bfa', '#fb7185', '#60a5fa'], 
                stroke: {
                    show: true,
                    colors: ['#ffffff'], // Borda branca para separar
                    width: 3
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Open Sans, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#fff']
                    },
                    dropShadow: { enabled: false }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    color: '#64748b', // Slate 500
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    color: '#0f172a', // Slate 900 (Dark)
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    offsetY: 5
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#475569', // Slate 600
                        useSeriesColors: false
                    },
                    markers: { radius: 12 },
                    itemMargin: { horizontal: 10, vertical: 5 }
                }
            };

            var chartPizza = new ApexCharts(document.querySelector("#chart-pizza"), optionsPizza);
            chartPizza.render();


            // Barras
            var barData = @json($barSeries);
            var barCategories = @json($barLabels);

            var optionsBar = {
                series: [{
                    name: 'Atendimentos',
                    data: barData
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                colors: ['#2dd4bf'], 
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '50%',
                        distributed: true,
                        dataLabels: {
                            position: 'top', 
                        },
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light', // Light shade para background branco
                        type: "vertical",
                        shadeIntensity: 0.5,
                        gradientToColors: ['#60a5fa'],
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#334155"], // Slate 700
                        fontWeight: '600'
                    },
                    background: { enabled: false } // Reset background label
                },
                legend: { show: false }, 
                xaxis: {
                    categories: barCategories,
                    labels: {
                        style: {
                            colors: '#64748b', // Slate 500
                            fontSize: '12px',
                            fontWeight: '600'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b', // Slate 500
                            fontSize: '12px',
                            fontWeight: '600'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9', // Slate 100
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                }
            };

            var chartBar = new ApexCharts(document.querySelector("#chart-barras"), optionsBar);
            chartBar.render();

        });
    </script>
@endpush

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appIndicadores" style="padding-bottom: 80px;">
        
        <!-- Header da Página -->
        <div class="d-flex align-items-center justify-content-between mb-4 px-2">
            <div>
                <h5 class="mb-0 text-white font-bold" style="font-size: 1.5rem;">Indicadores</h5>
                <small class="text-teal-400 font-medium">Performance e Resultados</small>
            </div>
            <div class="bg-white/5 rounded-xl p-2 border border-white/10 text-white cursor-pointer hover:bg-white/10 transition-all">
                <i class="bi bi-filter text-xl"></i>
            </div>
        </div>

        <!-- Section: Resumo Rápido -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card glass-card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-graph-up-arrow text-emerald-400"></i>
                            <span class="text-slate-400 text-xs font-semibold uppercase">Total Hoje</span>
                        </div>
                        <h3 class="mb-0 text-white font-bold">{{ $totalHoje }}</h3>
                        <small class="text-emerald-400 text-xs">+12% vs ontem</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card glass-card h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-wallet2 text-purple-400"></i>
                            <span class="text-slate-400 text-xs font-semibold uppercase">Faturamento</span>
                        </div>
                        <h3 class="mb-0 text-white font-bold">R$ 4.2k</h3>
                        <small class="text-purple-400 text-xs">Previsto: R$ 5k</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Gráfico de Pizza (Comparativo) -->
        <!-- 
             [DOCUMENTAÇÃO] 
             Este card contém o gráfico de Pizza/Donut.
             Container ID: #chart-pizza
             Dados: Representam a distribuição de atendimentos por categoria (ex: Convênios).
             Cores: Definidas no script JS (Palette Neon).
        -->
        <div class="card glass-card mb-4 overflow-hidden border-0">
            <div class="card-header bg-transparent border-bottom border-white/5 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white font-bold">Distribuição por Convênio</h6>
                <button class="btn btn-sm btn-icon text-slate-400 hover:text-white"><i class="bi bi-three-dots-vertical"></i></button>
            </div>
            <div class="card-body p-3 position-relative">
                <div id="chart-pizza" style="min-height: 300px;"></div>
                
                <!-- Legenda Customizada (Opcional, pois o ApexCharts gera) -->
                <!-- <div class="d-flex justify-content-center gap-3 mt-2"> ... </div> -->
            </div>
        </div>

        <!-- Section: Gráfico de Barras (Evolução) -->
        <!-- 
             [DOCUMENTAÇÃO] 
             Este card contém o gráfico de Barras.
             Container ID: #chart-barras
             Dados: Representam a quantidade de atendimentos ou faturamento por período (ex: Mês).
             Funcionalidade: As labels estão habilitadas em cima das barras para facilitar a leitura.
        -->
        <div class="card glass-card mb-4 overflow-hidden border-0">
            <div class="card-header bg-transparent border-bottom border-white/5 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white font-bold">Performance Mensal</h6>
                <select class="form-select form-select-sm bg-black/20 border-white/10 text-white text-xs w-auto">
                    <option>Últimos 6 meses</option>
                    <option>Este Ano</option>
                </select>
            </div>
            <div class="card-body p-3">
                <div id="chart-barras" style="min-height: 300px;"></div>
            </div>
        </div>

        <!-- Section: Lista Rápida (Exemplo) -->
        <div class="card glass-card border-0">
             <div class="card-header bg-transparent border-bottom border-white/5 p-3">
                <h6 class="mb-0 text-white font-bold">Top Procedimentos</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent border-white/5 px-3 py-3 d-flex justify-content-between align-items-center text-slate-300">
                        <span><i class="bi bi-circle-fill text-teal-400 text-[0.5rem] me-2"></i>Consulta Clínica</span>
                        <span class="badge bg-teal-500/10 text-teal-400 rounded-pill">145</span>
                    </li>
                    <li class="list-group-item bg-transparent border-white/5 px-3 py-3 d-flex justify-content-between align-items-center text-slate-300">
                        <span><i class="bi bi-circle-fill text-purple-400 text-[0.5rem] me-2"></i>Exames Laboratoriais</span>
                        <span class="badge bg-purple-500/10 text-purple-400 rounded-pill">98</span>
                    </li>
                    <li class="list-group-item bg-transparent border-transparent px-3 py-3 d-flex justify-content-between align-items-center text-slate-300">
                        <span><i class="bi bi-circle-fill text-rose-400 text-[0.5rem] me-2"></i>Retornos</span>
                        <span class="badge bg-rose-500/10 text-rose-400 rounded-pill">65</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <!--end to page content-->
@endsection

@push('styles')
    <style>
        /* Estilos Glassmorphism Premium para os Cards */
        .glass-card {
            background: rgba(15, 23, 42, 0.6); /* Slate 900 com transparência */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(15, 23, 42, 0.75);
            transform: translateY(-2px);
        }

        /* Ajustes no ApexCharts para Dark Mode */
        .apexcharts-tooltip {
            background: #0f172a !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5) !important;
        }
        .apexcharts-tooltip-title {
            background: #1e293b !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            font-family: inherit !important;
        }
        .apexcharts-text {
            fill: #94a3b8 !important; /* Slate 400 */
        }
        
        .apexcharts-legend-text {
             color: #cbd5e1 !important; /* Slate 300 */
        }
        
        .apexcharts-menu {
             background: #0f172a !important;
             border: 1px solid rgba(255,255,255,0.1) !important;
             color: #fff !important;
        }
        .apexcharts-menu-item:hover {
            background: #1e293b !important;
        }

    </style>
@endpush

@push('scripts')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // =========================================================
            // CONFIGURAÇÃO DO GRÁFICO DE PIZZA (DONUT)
            // =========================================================
            /* 
             * DADOS REAIS: 
             * Substitua a variável `pizzaSeries` pelos valores numéricos vindos do backend (ex: contagem por convênio).
             * Substitua a variável `pizzaLabels` pelos nomes das categorias.
             */
            var pizzaSeries = @json($pizzaSeries); 
            var pizzaLabels = @json($pizzaLabels);

            var optionsPizza = {
                series: pizzaSeries,
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent', // Fundo transparente
                    toolbar: { show: false }
                },
                labels: pizzaLabels,
                colors: ['#2dd4bf', '#a78bfa', '#fb7185', '#60a5fa'], // Teal, Purple, Rose, Blue (Cores Vibrantes)
                stroke: {
                    show: true,
                    colors: ['rgba(15, 23, 42, 0.9)'], // Cor da borda igual ao fundo para separar fatias
                    width: 3
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Open Sans, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#fff']
                    },
                    dropShadow: { enabled: false }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    color: '#94a3b8',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    color: '#fff',
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    offsetY: 5
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#94a3b8',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#cbd5e1',
                        useSeriesColors: false
                    },
                    markers: { radius: 12 },
                    itemMargin: { horizontal: 10, vertical: 5 }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return val + " atendimentos"
                        }
                    }
                }
            };

            // Inicializa o Gráfico de Pizza
            var chartPizza = new ApexCharts(document.querySelector("#chart-pizza"), optionsPizza);
            chartPizza.render();


            // =========================================================
            // CONFIGURAÇÃO DO GRÁFICO DE BARRAS
            // =========================================================
            /* 
             * DADOS REAIS: 
             * Substitua `barSeries.data` pelos valores do backend.
             * Substitua `barCategories` pelos meses/dias correspondentes.
             */
            var barData = @json($barSeries);
            var barCategories = @json($barLabels);

            var optionsBar = {
                series: [{
                    name: 'Atendimentos',
                    data: barData
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                colors: ['#2dd4bf'], // Teal Principal
                plotOptions: {
                    bar: {
                        borderRadius: 6, // Cantos arredondados nas barras
                        columnWidth: '50%',
                        distributed: true, // Cores diferentes por barra (opcional, aqui deixamos override nas cores se quiser)
                        dataLabels: {
                            position: 'top', // Show data labels on top of bars
                        },
                    }
                },
                // Gradiente vibrante nas barras!
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: "vertical",
                        shadeIntensity: 0.5,
                        gradientToColors: ['#60a5fa'], // Degrade para Azul
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20, // Move o label para cima da barra
                    style: {
                        fontSize: '12px',
                        colors: ["#fff"], // Texto branco
                        fontWeight: '600'
                    },
                    background: {
                        enabled: true,
                        foreColor: '#fff',
                        borderRadius: 2,
                        padding: 4,
                        opacity: 0.1, // Fundo sutil no label
                        borderWidth: 0,
                        borderColor: '#fff'
                    }
                },
                legend: { show: false }, // Esconde a legenda pois é uma série só
                xaxis: {
                    categories: barCategories,
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    crosshairs: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px'
                        }
                    }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.05)',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: 'dark',
                }
            };

            // Inicializa o Gráfico de BArras
            var chartBar = new ApexCharts(document.querySelector("#chart-barras"), optionsBar);
            chartBar.render();

        });
    </script>
@endpush
