Alpine.data('app', () => ({
    ooo: null,
    agendaEspecialidades: [],
    agendaProcedimentos: [],
    agendaProfissionais: [],
    agendaLocais: [],
    agendaConvenios: [],
    loadingEspecialidade: false,
    loadingDelEspecialidade: null,
    loadingProcedimento: false,
    loadingDelProcedimento: null,
    loadingProfissional: false,
    loadingDelProfissional: null,
    loadingLocal: false,
    loadingDelLocal: null,
    loadingConvenio: false,
    loadingDelConvenio: null,

    loadingGetHorarios: false,
    agendaHorarios: null,
    inputsGeracao: {
        cd_agenda: null,
        cd_escala:null,
        horarios_bloqueados: [],
        feriados: []
    },
    loadingGeracao: false,
    loadingExclusao: false,
    dadosExclusao: [],
    datasParaExluir: [],
    loadingExclusaoToggle: false,
    param:{
        agenda: null,
        escala: null
    },
    textIcoSalvar: '<i class="fa fa-check"></i> Salvar',
    listaEscalas: null,
    modalEdicao: null,
    showDivEdicao: true,
    codEscalaEdicao: null,
    msgEdicao: null,

    agendaEncaixe: false,
    listaAgendaExaixe: [],
    dadosEscala: null,
   
    init() {
        if (typeof agenda != 'undefined') {
            this.agendaEspecialidades = agenda.especialidades;
            this.agendaEspecialidades.forEach((espec) => {
                espec.nm_especialidade = especialidades.find((especialidade) => especialidade.cd_especialidade == espec.cd_especialidade)?.nm_especialidade
            });

            this.agendaProcedimentos = agenda.procedimentos;
            this.agendaProcedimentos.forEach((proc) => {
                proc.nm_proc = procedimentos.find((procedimento) => procedimento.cd_proc == proc.cd_proc)?.nm_proc
            });

            this.agendaProfissionais = agenda.profissionais;
            this.agendaProfissionais.forEach((prof) => {
                prof.nm_profissional = profissionais.find((profissional) => profissional.cd_profissional == prof.cd_profissional)?.nm_profissional
            });

            this.agendaLocais = agenda.locais;
            this.agendaLocais.forEach((localI) => {
                localI.nm_local = locais.find((local) => local.cd_local == localI.cd_local)?.nm_local
            });

            this.agendaConvenios = agenda.convenios;
            this.agendaConvenios.forEach((conv) => {
                conv.nm_convenio = convenios.find((convenio) => convenio.cd_convenio == conv.cd_convenio)?.nm_convenio
            });

        }
        if (typeof escala != 'undefined') {
            this.listaEscalas = escala;
        }
    },

    submitAgenda() { 
        $('#formAgenda input[type=submit]').click();
    },

    getAgendamentoEncaixe(dados){
        this.dadosEscala = dados;
        this.agendaEncaixe=true; 
        console.log(dados);
        axios.get(`/rpclinica/json/agendamento-encaixe/${dados.cd_escala_agenda}`)
        .then((res) => {  
            this.listaAgendaExaixe=res.data.retorno; 
            if(!this.listaAgendaExaixe){ 
                this.agendaEncaixe=false; 
            }
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        })

    },

    storeAgendamentoEncaixe(atend){ 

        var horario = $('#enc_horario'+atend).val();

        axios.post(`/rpclinica/json/agenda-escala-encaixe/${atend}/${horario}`)
        .then((res) => { 
            
            $('#enc_horario'+atend).val(null).trigger('change');  
            this.listaAgendaExaixe=res.data.retorno;
            if(!this.listaAgendaExaixe){ 
                this.agendaEncaixe=false; 
            }
            toastr['success'](res.data.message);
        })
        .catch((err) => toastr['error'](err.response.data.message));
    },

    openModalGeracao(cdAgenda,cdEscala) {
        this.agendaHorarios = null;
        this.loadingGetHorarios = true;
        this.inputsGeracao.cd_agenda = cdAgenda;
        this.inputsGeracao.cd_escala = cdEscala;
        this.inputsGeracao.horarios_bloqueados = [];
        this.inputsGeracao.feriados = [];
        this.dadosExclusao = [];
        this.datasParaExluir = [];

        this.getHorarios(cdAgenda,cdEscala);

        $('#cadastro-geracao').modal('toggle');
    },

    getHorarios(cdAgenda,cdEscala) {
        this.param.agenda=cdAgenda;
        this.param.escala=cdEscala;

        axios.post('/rpclinica/json/agenda/horarios',  this.param )
            .then((res) => {
                this.agendaHorarios = res.data;

                this.inputsGeracao.feriados = this.agendaHorarios.feriados.map((feriado) => feriado.dt_feriado);

                if (this.agendaHorarios.escalas.bloqueios_gerados) {
                    this.inputsGeracao.horarios_bloqueados = this.agendaHorarios.escalas.bloqueios_gerados.lista_horarios.split(',');
                }

                if (this.agendaHorarios.escalas.feriados_gerados) {
                    this.inputsGeracao.feriados = this.agendaHorarios.escalas.feriados_gerados.lista_datas.split(',');
                }
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingGetHorarios = false);
    },

    timeCut(time) {
        return time?.substr(0, 5);
    },

    calcIntervalo(minutes) {
        return `${parseInt(minutes / 60).toString().padStart(2, 0)}:${parseInt(minutes % 60).toString().padStart(2, 0)}`;
    },
 
    limparEscala(){

        this.msgEdicao='';
        this.showDivEdicao=true;
        this.codEscalaEdicao= null;
        $('#check-particular span').removeClass('checked');
        $('#check-particular input').prop('checked', false);
       
        $('#check-convenio span').removeClass('checked');
        $('#check-convenio input').prop('checked', false);
        
        $('#check-sus span').removeClass('checked');
        $('#check-sus input').prop('checked', false);
         
        $('#check-segunda span').removeClass('checked');
        $('#check-segunda input').prop('checked', false);
         
        $('#check-terca span').removeClass('checked');
        $('#check-terca input').prop('checked', false);
        
        $('#check-quarta span').removeClass('checked');
        $('#check-quarta input').prop('checked', false);
         
        $('#check-quinta span').removeClass('checked');
        $('#check-quinta input').prop('checked', false);
        
        $('#check-sexta span').removeClass('checked');
        $('#check-sexta input').prop('checked', false);
         
        $('#check-sabado span').removeClass('checked');
        $('#check-sabado input').prop('checked', false);
         
        $('#check-domingo span').removeClass('checked');
        $('#check-domingo input').prop('checked', false);
         
        $('#check-sessao span').removeClass('checked');
        $('#check-sessao input').prop('checked', false);
         
        $('#hora_inicial').val(null);
        $('#hora_final').val(null);

        $('#qtde_encaixe').val(null).trigger('change');  
        $('#qtde_sessao').val(null).trigger('change');   
        $('#intervalo').val(null).trigger('change'); 
        


    },

    editarEscala(dados){
        this.msgEdicao='<br><code><b>Atenção!!!</b> Você esta editando a escala [ ' + dados.cd_escala_agenda + ' ], Alterando os campos de Hora Inicial, Hora Final ou Intervalo, os agendamentos serão desvinculado da escala atual. </code>';
        this.showDivEdicao=false;
        this.codEscalaEdicao=dados.cd_escala_agenda;
        $('#check-particular span').removeClass('checked');
        $('#check-particular input').prop('checked', false);
        if(dados.sn_particular=='1'){
            $('#check-particular span').addClass('checked');
            $('#check-particular input').prop('checked', true);
        }

        $('#check-convenio span').removeClass('checked');
        $('#check-convenio input').prop('checked', false);
        if(dados.sn_convenio=='1'){
            $('#check-convenio span').addClass('checked');
            $('#check-convenio input').prop('checked', true);
        }

        $('#check-sus span').removeClass('checked');
        $('#check-sus input').prop('checked', false);
        if(dados.sn_sus=='1'){
            $('#check-sus span').addClass('checked');
            $('#check-sus input').prop('checked', true);
        }

        $('#check-segunda span').removeClass('checked');
        $('#check-segunda input').prop('checked', false);
        if(dados.cd_dia=='segunda'){
            $('#check-segunda span').addClass('checked');
            $('#check-segunda input').prop('checked', true);
        }

        $('#check-terca span').removeClass('checked');
        $('#check-terca input').prop('checked', false);
        if(dados.cd_dia=='terca'){
            $('#check-terca span').addClass('checked');
            $('#check-terca input').prop('checked', true);
        }

        $('#check-quarta span').removeClass('checked');
        $('#check-quarta input').prop('checked', false);
        if(dados.cd_dia=='quarta'){
            $('#check-quarta span').addClass('checked');
            $('#check-quarta input').prop('checked', true);
        }

        $('#check-quinta span').removeClass('checked');
        $('#check-quinta input').prop('checked', false);
        if(dados.cd_dia=='quinta'){
            $('#check-quinta span').addClass('checked');
            $('#check-quinta input').prop('checked', true);
        }

        $('#check-sexta span').removeClass('checked');
        $('#check-sexta input').prop('checked', false);
        if(dados.cd_dia=='sexta'){
            $('#check-sexta span').addClass('checked');
            $('#check-sexta input').prop('checked', true);
        }

        $('#check-sabado span').removeClass('checked');
        $('#check-sabado input').prop('checked', false);
        if(dados.cd_dia=='sabado'){
            $('#check-sabado span').addClass('checked');
            $('#check-sabado input').prop('checked', true);
        }

        $('#check-domingo span').removeClass('checked');
        $('#check-domingo input').prop('checked', false);
        if(dados.cd_dia=='domingo'){
            $('#check-domingo span').addClass('checked');
            $('#check-domingo input').prop('checked', true);
        }

        $('#check-sessao span').removeClass('checked');
        $('#check-sessao input').prop('checked', false);
        if(dados.sn_sessao=='1'){
            $('#check-sessao span').addClass('checked');
            $('#check-sessao input').prop('checked', true);
        }

        $('#data_inicial').val(dados.dt_inicial);
        $('#data_final').val(dados.dt_fim);
        $('#hora_inicial').val(dados.hr_inicial);
        $('#hora_final').val(dados.hr_final);

        $('#qtde_encaixe').val(null).trigger('change');
        $('#qtde_encaixe').val(dados.qtde_encaixe).trigger('change');

        $('#qtde_sessao').val(null).trigger('change');
        $('#qtde_sessao').val(dados.qtde_sessao).trigger('change');

        $('#tipo_agenda').val(null).trigger('change');
        $('#tipo_agenda').val(dados.tp_agenda).trigger('change');

        $('#intervalo').val(null).trigger('change');
        $('#intervalo').val(dados.intervalo).trigger('change');
        $('#cd_escala').val(dados.cd_escala_agenda);

        /*
        var arraytipo=[];
        for (var i in dados.escala_tipo_atend) {
            arraytipo[i] = dados.escala_tipo_atend[i].cd_tipo_atendimento
        }
        if(arraytipo){
            $('#agenda_tipos_atend').val(arraytipo).select2();
        }

        var arrayprof=[];
        for (var i in dados.escala_prof) {
            arrayprof[i] = dados.escala_prof[i].cd_profissional
        }
        if(arrayEspec){
            $('#agenda_profissional').val(arrayprof).select2();
        }

        var arrayEspec=[];
        for (var i in dados.escala_espec) {
            arrayEspec[i] = dados.escala_espec[i].cd_especialidade
        }
        if(arrayEspec){
            $('#agenda_especialidade').val(arrayEspec).select2();
        }

        var arrayLocal=[];
        for (var i in dados.escala_local) {
            arrayLocal[i] = dados.escala_local[i].cd_local
        }
        if(arrayLocal){
            $('#agenda_local').val(arrayLocal).select2();
        }

        var arrayConv=[];
        for (var i in dados.escala_conv) {
            arrayConv[i] = dados.escala_conv[i].cd_convenio
        }
        if(arrayConv){
            $('#agenda_convenio').val(arrayConv).select2();
        }
        */


    },

    saveEscala(){
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja confirma essa Alteração?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
  
                if (result.isConfirmed) {
                    this.textIcoSalvar='<i class="fa fa-spinner fa-spin" ></i> Salvando';
                    document.getElementById('formAgendaEscala').submit();
    
                }
    
        });

        
    },

    execluirEscala(Cod){


        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa Escala?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {


            if (result.isConfirmed) {
 
                axios.delete(`/rpclinica/json/delete-escala/${Cod}`)
                .then((res) => { 
                    toastr['success'](res.data.message);
                    this.listaEscalas = res.data.escalas; 
                })
                .catch((err) => toastr['error'](err.response.data.message))

            }

        });
    },

    LetraMaiuscula(text) {
        var words = text.toLowerCase().split(" ");
        for (var a = 0; a < words.length; a++) {
            var w = words[a];
            words[a] = w[0].toUpperCase() + w.slice(1);
        }
        return words.join(" ");
    },

    gerarAgendamentos() {
        let escala = this.agendaHorarios.escalas;

        if (!escala.cd_dia)
        {
            toastr['error']('Não há dias da semana marcados na genda!');
            return;
        }

        this.loadingGeracao = true;

        axios.post('/rpclinica/json/agenda/horarios/gerar-agendamentos', this.inputsGeracao)
            .then((res) => {
                toastr['success'](res.data.message);
                this.getHorarios(this.inputsGeracao.cd_agenda,this.inputsGeracao.cd_escala);
                //console.log(res.data.escalas);
                this.listaEscalas = res.data.escalas;
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingGeracao = false);
    },

    pesquisaExclusao() {
        this.loadingExclusao = true;

        let form = new FormData(document.querySelector('#form-exclusao'));
        form.append('cd_agenda', this.agendaHorarios.agenda.cd_agenda);
        form.append('cd_escala', this.agendaHorarios.escalas.cd_escala_agenda);

        axios.post('/rpclinica/json/agenda/horarios/pesquisa-exclusao', form)
            .then((res) => this.dadosExclusao = res.data)
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingExclusao = false);
    },

    excluirDatas() {
        this.loadingExclusaoToggle = true;

        axios.post('/rpclinica/json/agenda/horarios/excluir-datas', {
            cd_agenda: this.inputsGeracao.cd_agenda,
            cd_escala: this.inputsGeracao.cd_escala,
            datas: this.datasParaExluir
        })
            .then((res) => {
                toastr['success'](res.data.message);

                this.datasParaExluir.forEach((data) => {
                    delete this.dadosExclusao[data];
                });

                this.datasParaExluir = [];
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingExclusaoToggle = false);
    },
 

    addEspecialidade() {
        if ($('#agenda-especialidade').val().trim() == '' ||
            this.agendaEspecialidades.find((especialidade) => especialidade.cd_especialidade == $('#agenda-especialidade').val())) return;

        if (typeof agenda != 'undefined') {
            this.loadingEspecialidade = true;

            axios.post(`/rpclinica/json/agenda-especialidade`, {
                cd_agenda: agenda.cd_agenda,
                cd_especialidade: $('#agenda-especialidade').val()
            })
                .then((res) => {
                    this.agendaEspecialidades.push({
                        ...res.data,
                        nm_especialidade: especialidades.find((especialidade) => especialidade.cd_especialidade == $('#agenda-especialidade').val())?.nm_especialidade
                    });

                    $('#agenda-especialidade').val(null).trigger('change');
                })
                .finally(() => this.loadingEspecialidade = false);
            return;
        }

        this.agendaEspecialidades.push({
            cd_especialidade: $('#agenda-especialidade').val(),
            nm_especialidade: especialidades.find((especialidade) => especialidade.cd_especialidade == $('#agenda-especialidade').val())?.nm_especialidade
        });

        $('#agenda-especialidade').val(null).trigger('change');
    },

    deleteEspecialidade(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse cadastro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof agenda != 'undefined') {
                    this.loadingDelEspecialidade = indice;

                    axios.delete(`/rpclinica/json/agenda-especialidade/${this.agendaEspecialidades[indice].cd_agenda_espec}`)
                        .then((res) => this.agendaEspecialidades.splice(indice, 1))
                        .finally(() => this.loadingDelEspecialidade = null);
                    return;
                }

                this.agendaEspecialidades.splice(indice, 1);
            }
        });
    },

    addProcedimento() {
        if ($('#agenda-procedimento').val().trim() == '' ||
            this.agendaProcedimentos.find((procedimento) => procedimento.cd_proc == $('#agenda-procedimento').val())) return;

        if (typeof agenda != 'undefined') {
            this.loadingProcedimento = true;

            axios.post(`/rpclinica/json/agenda-procedimento`, {
                cd_agenda: agenda.cd_agenda,
                cd_proc: $('#agenda-procedimento').val()
            })
                .then((res) => {
                    this.agendaProcedimentos.push({
                        ...res.data,
                        nm_proc: procedimentos.find((procedimento) => procedimento.cd_proc == $('#agenda-procedimento').val())?.nm_proc
                    });

                    $('#agenda-procedimento').val(null).trigger('change');
                })
                .finally(() => this.loadingProcedimento = false);
            return;
        }

        this.agendaProcedimentos.push({
            cd_proc: $('#agenda-procedimento').val(),
            nm_proc: procedimentos.find((procedimento) => procedimento.cd_proc == $('#agenda-procedimento').val())?.nm_proc
        });

        $('#agenda-procedimento').val(null).trigger('change');
    },

    deleteProcedimento(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse cadastro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof agenda != 'undefined') {
                    this.loadingDelProcedimento = indice;

                    axios.delete(`/rpclinica/json/agenda-procedimento/${this.agendaProcedimentos[indice].cd_agenda_proc}`)
                        .then((res) => this.agendaProcedimentos.splice(indice, 1))
                        .finally(() => this.loadingDelProcedimento = null);
                    return;
                }

                this.agendaProcedimentos.splice(indice, 1);
            }
        });
    },

    addProfissional() {
        if ($('#agenda-profissional').val().trim() == '' ||
            this.agendaProfissionais.find((profissional) => profissional.cd_profissional == $('#agenda-profissional').val())) return;

        if (typeof agenda != 'undefined') {
            this.loadingProfissional = true;

            axios.post(`/rpclinica/json/agenda-profissional`, {
                cd_agenda: agenda.cd_agenda,
                cd_profissional: $('#agenda-profissional').val()
            })
                .then((res) => {
                    this.agendaProfissionais.push({
                        ...res.data,
                        nm_profissional: profissionais.find((profissional) => profissional.cd_profissional == $('#agenda-profissional').val())?.nm_profissional
                    });

                    $('#agenda-profissional').val(null).trigger('change');
                })
                .finally(() => this.loadingProfissional = false);
            return;
        }

        this.agendaProfissionais.push({
            cd_profissional: $('#agenda-profissional').val(),
            nm_profissional: profissionais.find((profissional) => profissional.cd_profissional == $('#agenda-profissional').val())?.nm_profissional
        });

        $('#agenda-profissional').val(null).trigger('change');
    },

    deleteProfissional(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse cadastro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof agenda != 'undefined') {
                    this.loadingDelProfissional = indice;

                    axios.delete(`/rpclinica/json/agenda-profissional/${this.agendaProfissionais[indice].cd_agenda_prof}`)
                        .then((res) => this.agendaProfissionais.splice(indice, 1))
                        .finally(() => this.loadingDelProfissional = null);
                    return;
                }

                this.agendaProfissionais.splice(indice, 1);
            }
        });
    },

    addLocal() {
        if ($('#agenda-local').val().trim() == '' ||
            this.agendaLocais.find((local) => local.cd_local == $('#agenda-local').val())) return;

        if (typeof agenda != 'undefined') {
            this.loadingLocal = true;

            axios.post(`/rpclinica/json/agenda-local`, {
                cd_agenda: agenda.cd_agenda,
                cd_local: $('#agenda-local').val()
            })
                .then((res) => {
                    this.agendaLocais.push({
                        ...res.data,
                        nm_local: locais.find((local) => local.cd_local == $('#agenda-local').val())?.nm_local
                    });

                    $('#agenda-local').val(null).trigger('change');
                })
                .finally(() => this.loadingLocal = false);
            return;
        }

        this.agendaLocais.push({
            cd_local: $('#agenda-local').val(),
            nm_local: locais.find((local) => local.cd_local == $('#agenda-local').val())?.nm_local
        });

        $('#agenda-local').val(null).trigger('change');
    },

    deleteLocal(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse cadastro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof agenda != 'undefined') {
                    this.loadingDelLocal = indice;

                    axios.delete(`/rpclinica/json/agenda-local/${this.agendaLocais[indice].cd_agenda_local}`)
                        .then((res) => this.agendaLocais.splice(indice, 1))
                        .finally(() => this.loadingDelLocal = null);
                    return;
                }

                this.agendaLocais.splice(indice, 1);
            }
        });
    },

    addConvenio() {
        if ($('#agenda-convenio').val().trim() == '' ||
            this.agendaConvenios.find((convenio) => convenio.cd_convenio == $('#agenda-convenio').val())) return;

        if (typeof agenda != 'undefined') {
            this.loadingConvenio = true;

            axios.post(`/rpclinica/json/agenda-convenio`, {
                cd_agenda: agenda.cd_agenda,
                cd_convenio: $('#agenda-convenio').val()
            })
                .then((res) => {
                    this.agendaConvenios.push({
                        ...res.data,
                        nm_convenio: convenios.find((convenio) => convenio.cd_convenio == $('#agenda-convenio').val())?.nm_convenio
                    });

                    $('#agenda-convenio').val(null).trigger('change');
                })
                .finally(() => this.loadingConvenio = false);
            return;
        }

        this.agendaConvenios.push({
            cd_convenio: $('#agenda-convenio').val(),
            nm_convenio: convenios.find((convenio) => convenio.cd_convenio == $('#agenda-convenio').val())?.nm_convenio
        });

        $('#agenda-convenio').val(null).trigger('change');
    },

    deleteConvenio(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse cadastro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof agenda != 'undefined') {
                    this.loadingDelConvenio = indice;

                    axios.delete(`/rpclinica/json/agenda-convenio/${this.agendaConvenios[indice].cd_agenda_conv}`)
                        .then((res) => this.agendaConvenios.splice(indice, 1))
                        .finally(() => this.loadingDelConvenio = null);
                    return;
                }

                this.agendaConvenios.splice(indice, 1);
            }
        });
    }

}));


$(document).ready(function () {

    $('#agenda-procedimento').select2({
        ajax: {
            url: '/rpclinica/json/search-procedimento',
            dataType: 'json',
            processResults: (data) => {
                let search = $('#agenda-procedimento').data('select2').results.lastParams?.term;

         

                return {
                    results: data
                };
            }
        }
    });
  
});
