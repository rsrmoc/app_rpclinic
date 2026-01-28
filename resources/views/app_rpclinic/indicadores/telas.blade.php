@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Indicadores</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appIndicadores" style="padding-bottom: 80px; padding-top: 0px !important; margin-top: -30px !important;">
        
        <!-- Header da Página -->
        <div class="d-flex align-items-center text-slate-500 justify-content-between mb-4 px-2">
            <div>
                <h5 class="mb-0 text-slate-800 font-extrabold" style="font-size: 1.5rem;"><i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($Data)->format('m/Y') }}</h5>
                <small class="text-teal-600 font-medium">Performance e Resultados</small>
            </div>
            <div class="bg-slate-100 rounded-xl p-2 border border-slate-200 text-slate-500 cursor-pointer hover:bg-slate-200 transition-all" 
                 data-bs-toggle="modal" data-bs-target="#modalFiltroData">
                <i class="bi bi-filter text-xl"></i>
            </div>
        </div>
  
        <!-- Section: Gráfico de Pizza (Localidades) -->
        <div class="card glass-card mb-4 overflow-hidden border-0 ">
            <div class="card-header bg-transparent border-bottom border-slate-100 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-slate-800 font-bold">
                    <i class="bi bi-pie-chart-fill text-teal-600 me-2"></i>Localidades
                </h6>
                 
            </div>
            <div class="card-body p-3 position-relative">
                @if($hasDataPizza && $pizzaLabels->count() > 0)
                    <div id="chart-pizza" style="min-height: 300px;"></div>
                @else
                    <div class="p-5 text-center text-slate-500" style="min-height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="bi bi-pie-chart text-slate-300 mb-0" style="font-size: 4rem;"></i> 
                        <p class="mb-0 text-sm text-slate-500 mt-0">Não há informações para o período selecionado</p>
                    </div>
                @endif
            </div>
        </div>
    
        <!-- Section: Lista Rápida (Exemplo) -->
        <div class="card glass-card border-0 mb-4">
             <div class="card-header bg-transparent border-bottom border-slate-100 p-3">
                <h6 class="mb-0 text-slate-800 font-bold">
                    <i class="bi bi-people-fill text-teal-600 me-2"></i>Top Localidades (Pacientes)
                </h6>
            </div>
            <div class="card-body p-0">
                @if($hasDataPizzaPessoa && $pizzaLabelsPessoa->count() > 0)
                    <ul class="list-group list-group-flush bg-transparent">
                        @php
                            $colors = [
                                ['icon' => 'text-teal-500', 'badge' => 'bg-teal-50 text-teal-700 border-teal-100'],
                                ['icon' => 'text-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                ['icon' => 'text-cyan-500', 'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-100'],
                                ['icon' => 'text-blue-500', 'badge' => 'bg-blue-50 text-blue-700 border-blue-100'],
                                ['icon' => 'text-indigo-500', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                ['icon' => 'text-purple-500', 'badge' => 'bg-purple-50 text-purple-700 border-purple-100'],
                                ['icon' => 'text-pink-500', 'badge' => 'bg-pink-50 text-pink-700 border-pink-100'],
                                ['icon' => 'text-rose-500', 'badge' => 'bg-rose-50 text-rose-700 border-rose-100'],
                                ['icon' => 'text-orange-500', 'badge' => 'bg-orange-50 text-orange-700 border-orange-100'],
                                ['icon' => 'text-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-100'],
                            ];
                        @endphp
                        
                        @foreach($pizzaLabelsPessoa as $index => $label)
                            @php
                                $color = $colors[$index % count($colors)];
                                $isLast = $index === $pizzaLabelsPessoa->count() - 1;
                            @endphp
                            <li class="list-group-item bg-transparent {{ $isLast ? 'border-transparent' : 'border-slate-100' }} px-3 py-3 d-flex justify-content-between align-items-center text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                                <span class="flex items-center">
                                    <i class="bi bi-circle-fill {{ $color['icon'] }} text-[0.6rem] me-3"></i>
                                    {{ $label }}
                                </span>
                                <span class="badge {{ $color['badge'] }} rounded-pill font-bold border">
                                    {{ $pizzaSeriesPessoa[$index] ?? 0 }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-4 text-center text-slate-500">
                        <i class="bi bi-inbox text-4xl mb-0 d-block"></i>
                        <p class="mb-0 font-medium">Não há informações  para o período selecionado</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Section: Gráfico de Pizza Situação -->
        <div class="card glass-card mb-4 overflow-hidden border-0">
            <div class="card-header bg-transparent border-bottom border-slate-100 p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-slate-800 font-bold">
                    <i class="bi bi-pie-chart-fill text-teal-600 me-2"></i>Situação por Escalas
                </h6>
            </div>
            <div class="card-body p-3 position-relative">
                @if($hasDataPizzaSituacao && $pizzaSituacaoLabels->count() > 0)
                    <div id="chart-pizza-situacao" style="min-height: 300px;"></div>
                @else
                    <div class="p-5 text-center text-slate-500" style="min-height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="bi bi-pie-chart text-slate-300 mb-0" style="font-size: 4rem;"></i> 
                        <p class="mb-0 text-sm text-slate-500 mt-0">Não há informações para o período selecionado</p>
                    </div>
                @endif
            </div>
        </div>



    </div>
    <!--end to page content-->

    <!-- Modal Filtro de Período -->
    <div class="modal fade" id="modalFiltroData" tabindex="-1" aria-labelledby="modalFiltroDataLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
              
                <div class="modal-body p-4">
                    <form id="formFiltroData">
                        <div class="row g-4">
                            <!-- Mês -->
                            <div class="col-md-6">
                                <label for="filtroMes" class="form-label text-slate-700 font-semibold d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar-month me-2 text-teal-600"></i>Mês
                                </label>
                                <select class="form-select border-slate-200 rounded-xl shadow-sm py-2" id="filtroMes" name="mes" required style="font-size: 0.95rem;">
                                    <option value="">Selecione o mês...</option>
                                    <option value="01" {{ date('m') == '01' ? 'selected' : '' }}>Janeiro</option>
                                    <option value="02" {{ date('m') == '02' ? 'selected' : '' }}>Fevereiro</option>
                                    <option value="03" {{ date('m') == '03' ? 'selected' : '' }}>Março</option>
                                    <option value="04" {{ date('m') == '04' ? 'selected' : '' }}>Abril</option>
                                    <option value="05" {{ date('m') == '05' ? 'selected' : '' }}>Maio</option>
                                    <option value="06" {{ date('m') == '06' ? 'selected' : '' }}>Junho</option>
                                    <option value="07" {{ date('m') == '07' ? 'selected' : '' }}>Julho</option>
                                    <option value="08" {{ date('m') == '08' ? 'selected' : '' }}>Agosto</option>
                                    <option value="09" {{ date('m') == '09' ? 'selected' : '' }}>Setembro</option>
                                    <option value="10" {{ date('m') == '10' ? 'selected' : '' }}>Outubro</option>
                                    <option value="11" {{ date('m') == '11' ? 'selected' : '' }}>Novembro</option>
                                    <option value="12" {{ date('m') == '12' ? 'selected' : '' }}>Dezembro</option>
                                </select>
                            </div>
                            
                            <!-- Ano -->
                            <div class="col-md-6">
                                <label for="filtroAno" class="form-label text-slate-700 font-semibold d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar3 me-2 text-teal-600"></i>Ano
                                </label>
                                <select class="form-select border-slate-200 rounded-xl shadow-sm py-2" id="filtroAno" name="ano" required style="font-size: 0.95rem;">
                                    <option value="">Selecione o ano...</option>
                                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <!-- Preview do período selecionado -->
                        <div class="mt-4 p-3 bg-teal-50 rounded-xl border border-teal-100" id="periodoPreview" style="display: none;">
                            <p class="mb-0 text-teal-800 font-medium small">
                                <i class="bi bi-info-circle me-1"></i>
                                Período selecionado: <strong id="periodoTexto"></strong>
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top border-slate-100 p-4 bg-slate-50">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2 shadow-sm border border-slate-300" data-bs-dismiss="modal" style="color: #475569; background-color: #f8fafc;">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary rounded-xl px-4 py-2 shadow-sm" onclick="aplicarFiltro()" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); border: none;">
                        <i class="bi bi-check2-circle me-2"></i>Aplicar Filtro
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Fix para modal z-index */
        .modal-backdrop {
            display: none !important;
        }
        
        .modal {
            z-index: 1055 !important;
            background: rgba(0, 0, 0, 0.5) !important;
        }
        
        .modal-dialog {
            z-index: 1056 !important;
            position: relative;
        }
        
        .modal-content {
            z-index: 1057 !important;
            position: relative;
        }
        
        /* Estilos do Modal */
        .modal-content {
            overflow: visible !important;
        }
        
        .form-select,
        .form-control {
            position: relative;
            z-index: 1;
        }
        
        .form-select:focus,
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.15);
            z-index: 2;
        }
        
        .form-select option {
            padding: 10px;
        }
   
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
                    height: 350,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false }
                },
                labels: pizzaLabels,
                colors: ['#0d9488', '#14b8a6', '#2dd4bf', '#5eead4', '#99f6e4', '#a78bfa', '#fb7185', '#60a5fa'], 
                stroke: {
                    show: true,
                    colors: ['#ffffff'],
                    width: 2
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Open Sans, sans-serif',
                        fontWeight: '700',
                        colors: ['#fff']
                    },
                    background: {
                        enabled: false
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        opacity: 0.5
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '55%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: '600',
                                    color: '#64748b',
                                    offsetY: -5
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontWeight: 'bold',
                                    color: '#0f172a',
                                    offsetY: 8,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    fontSize: '13px',
                                    fontWeight: '600',
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                    }
                                }
                            }
                        },
                        expandOnClick: true
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '13px',
                    fontWeight: '600',
                    labels: {
                        colors: '#475569',
                        useSeriesColors: false
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 10,
                        offsetX: -5
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 6
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ": " + opts.w.globals.series[opts.seriesIndex]
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 320
                        },
                        legend: {
                            fontSize: '11px',
                            itemMargin: {
                                horizontal: 5,
                                vertical: 4
                            }
                        },
                        dataLabels: {
                            style: {
                                fontSize: '11px'
                            }
                        }
                    }
                }],
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(val) {
                            return val + " atendimentos"
                        }
                    },
                    style: {
                        fontSize: '13px'
                    }
                }
            };

            var chartPizza = new ApexCharts(document.querySelector("#chart-pizza"), optionsPizza);
            chartPizza.render();


            // =========================================================
            // GRÁFICO DE PIZZA SITUAÇÃO
            // =========================================================
            var pizzaSituacaoSeries = @json($pizzaSituacaoSeries); 
            var pizzaSituacaoLabels = @json($pizzaSituacaoLabels);

            var optionsPizzaSituacao = {
                series: pizzaSituacaoSeries,
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Open Sans, sans-serif',
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                labels: pizzaSituacaoLabels,
                colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1', '#8b5cf6', '#ec4899'], 
                stroke: {
                    show: true,
                    colors: ['#ffffff'],
                    width: 2
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Open Sans, sans-serif',
                        fontWeight: '800',
                        colors: ['#fff']
                    },
                    background: {
                        enabled: false
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.6
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '55%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '15px',
                                    fontWeight: '700',
                                    color: '#64748b',
                                    offsetY: -8
                                },
                                value: {
                                    show: true,
                                    fontSize: '32px',
                                    fontWeight: 'bold',
                                    color: '#0f172a',
                                    offsetY: 10,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    fontSize: '14px',
                                    fontWeight: '700',
                                    color: '#0d9488',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                    }
                                }
                            }
                        },
                        expandOnClick: true
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '13px',
                    fontWeight: '600',
                    labels: {
                        colors: '#475569',
                        useSeriesColors: false
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 12,
                        offsetX: -5
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 7
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ": " + opts.w.globals.series[opts.seriesIndex]
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 320
                        },
                        legend: {
                            fontSize: '12px',
                            itemMargin: {
                                horizontal: 6,
                                vertical: 5
                            },
                            markers: {
                                width: 10,
                                height: 10
                            }
                        },
                        dataLabels: {
                            style: {
                                fontSize: '12px'
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        value: {
                                            fontSize: '26px'
                                        },
                                        total: {
                                            fontSize: '13px'
                                        }
                                    }
                                }
                            }
                        }
                    }
                }],
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(val, opts) {
                            const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            const percent = ((val / total) * 100).toFixed(1);
                            return val + " (" + percent + "%)"
                        }
                    },
                    style: {
                        fontSize: '13px'
                    }
                }
            };

            var chartPizzaSituacao = new ApexCharts(document.querySelector("#chart-pizza-situacao"), optionsPizzaSituacao);
            chartPizzaSituacao.render();

 
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

        // Função para aplicar o filtro de data
        function aplicarFiltro() {
            const mes = document.getElementById('filtroMes').value;
            const ano = document.getElementById('filtroAno').value;

            if (!mes || !ano) {
                alert('Por favor, selecione o mês e o ano.');
                return;
            }

            // Redireciona para a mesma página com os parâmetros de filtro
            const url = new URL(window.location.href);
            url.searchParams.set('mes', mes);
            url.searchParams.set('ano', ano);
            window.location.href = url.toString();
        }

        // Preview do período selecionado
        document.getElementById('filtroMes')?.addEventListener('change', atualizarPreview);
        document.getElementById('filtroAno')?.addEventListener('change', atualizarPreview);

        function atualizarPreview() {
            const mes = document.getElementById('filtroMes').value;
            const ano = document.getElementById('filtroAno').value;
            const preview = document.getElementById('periodoPreview');
            const texto = document.getElementById('periodoTexto');
            
            if (mes && ano) {
                const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                              'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                texto.textContent = meses[parseInt(mes) - 1] + '/' + ano;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
