Alpine.data('app', () => ({
    panel: null,
    panelComp: [],
    panelHist: [],
    loadingPanel: false,
    data: data,
    graficoAtendimento: '<br><br><br><i class="fa fa-spinner fa-spin" style="font-size:5em; "></i><br>Carregando...',
    graficoHeader: ' <i style="color: #B0B0B0;" class="fa fa-spinner fa-spin"></i> ',
    headerAtendimento: null,
    headerExame: null,
    headerPendente: null,
    headerLaudado: null,
    exameLaudado: [],
    examePendente: [],
    loadListaExame: false,
    dt: null,
    dt_extenso: null,
 
    classLabelSituacao: {
        livre: 'label-success',
        agendado: 'label-primary',
        confirmado: 'label-info',
        atendido: 'label-warning',
        bloqueado: 'label-danger',
        cancelado: 'label-danger',
        aguardando: 'label-aguardando',
        atendimento: 'label-aguardando'
    },
    
    chart1: null,
    chart2: null,
    chart3: null,

    loadingChart1: false,

    optionsChart: {
        legend: { display: false },
        title: {
            display: true,
            text: "World Wine Production 2018"
        }
    },

    init() {
        console.log(this.data);
        this.getCompromisso();
        this.getDataPanel();

    },

    getCompromisso() {
        this.loadingPanel=true;
        axios.get('/rpclinica/json/panel-dashboard-compromisso')
            .then((res) => {
                console.log(res.data);
                this.panelComp = res.data.retorno;
                this.panelHist = res.data.historico;


            })
            .catch((err) => toastr['error']('Houve um erro ao obter os dados do painel'))
            .finally(() => this.loadingPanel = false);
    },

    getDataPanel() {
        
 
       document.getElementById('flot1').innerHTML =this.graficoAtendimento;
       document.getElementById('flot3').innerHTML =this.graficoAtendimento;
       this.headerAtendimento= this.graficoHeader;
       this.headerExame= this.graficoHeader;
       this.headerPendente= this.graficoHeader;
       this.headerLaudado= this.graficoHeader;
       this.loadingPanel = true;
       this.loadListaExame = true;

        axios.get(`/rpclinica/json/panel-dashboard/${this.data}`)
            .then((res) => { 
                this.panel = res.data;
                this.dt=res.data.request.dt;
                this.dt_extenso=res.data.request.dt_extenso;
                console.log(res.data);
                this.headerAtendimento= res.data.header.atendimento;
                this.headerExame= res.data.header.exame;
                this.headerPendente= res.data.header.pendente;
                this.headerLaudado= res.data.header.laudado;
                this.exameLaudado= res.data.exameLaudado;
                this.examePendente= res.data.examePendente;
                this.getChartAtendimentos(res.data.grafico);
                this.getChartExames(res.data.header); 
   
            })
            .catch((err) => toastr['error']('Houve um erro ao obter os dados do painel'))
            .finally(() => { 
                this.loadingPanel = false
                this.loadListaExame = false;
            });
    },

    getDataChart1() {
        this.loadingChart1 = true;

        setTimeout(() => {
            this.chart1.data.labels = [55, 49, 44, 24, 15];
            this.chart1.data.datasets = [{
                backgroundColor: '#22BAA0',
                borderWidth: 1,
                data: [55, 49, 44, 24, 15]
            }];

            this.chart1.update();
            this.loadingChart1 = false;
        }, 5000);
    },

    getChartAtendimentos(dados){
        document.getElementById('flot1').innerHTML = null;
 
        var data = dados.atend;
        var dataset = [{
            data: data,
            color: "#22BAA0"
        }];
         
        var ticks = dados.dt_atend;

        var options = {
            series: {
                bars: {
                    show: true
                }
            },
            bars: {
                align: "center",
                barWidth: 0.5
            },
            xaxis: {
                ticks: ticks
            },
            legend: {
                show: false
            },
            grid: {
                color: "#AFAFAF",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: '#FFF'
            },
            tooltip: true,
            tooltipOpts: {
                content: "Dia: <b> %x</b>, <br> Atendimento(s): <b> %y</b>",
                defaultTheme: false
            }
        };
        $.plot($("#flot1"), dataset, options);
    },
    
    getChartExames(dados){
 
            document.getElementById('flot3').innerHTML = null; 
            var data = [{
                label: "Pendentes",
                data: dados.pendente,
                color: "#f25656",
            }, {
                label: "Realizados",
                data: dados.laudado,
                color: "#22baa0",
            }];
            var options = {
                series: {
                    pie: {
                        show: true
                    }
                },
                legend: {
                    labelFormatter: function(label, series) {
                        return '<span class="pie-chart-legend">' + label + '</span>';
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%p.0%, %s",
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                }
            };
            $.plot($("#flot3"), data, options);
     

    }
}));
