import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    qr_code: null, 
    div_qr_code: false, 
    div_info: false, 
    loading_modal: false,
    loading_group: false,
    grupos: [],
    
    init() {  
    
    },  
    desconectar(){ 
        this.qr_code = null;
        this.div_qr_code = false;
        this.div_info = false;
        this.loading_modal= true;
        axios.post('/rpclinica/comunicacao-desc')
        .then((res) => {
            console.log(res);
            this.getQrCode();
        })
    },
    getQrCode(){
        this.getQrCode2();
        setInterval(() => this.getQrCode2(), 40000);
    },
    getQrCode2() {  
            this.qr_code = null;
            this.div_qr_code = false;
            this.div_info = false;
            this.loading_modal= true;
            axios.post('/rpclinica/comunicacao-qr-code')
            .then((res) => {
                console.log(res);
                if(res.data.qrcode){
                    this.div_qr_code = true;
                    this.qr_code = res.data.qrcode;  
                }else{
                    if(res.data.status==200){
                        this.div_info = true;
                    }
                } 
            })
            .catch((err) => console.log(err) )
            .finally(() => this.loading_modal = false); 
    },
    getGroup(){
        this.loading_group= true;
        axios.post('/rpclinica/comunicacao-group')
        .then((res) => {
            console.log(res.data.data);
            this.grupos = res.data.data;
             
        })
        .catch((err) => console.log(err) )
        .finally(() => this.loading_group = false); 

    },
    

    getHorarios() {
        this.loading = true;

        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/horarios', form)
            .then((res) => {
                this.horarios = res.data.dados;  
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading = false);
    },
    clickHorario(horario) {
        if (horario.situacao == 'cancelado' || horario.situacao == 'bloqueado' || horario.situacao == 'livre') return;

        this.modalData.horario = horario; 
        $('#cadastro-consulta').modal('toggle');
    },
    openAgendamentoManual() { 
        /*
        if(!this.CodAgenda){
            toastr['error']("Erro! Codigo da agenda não informada.");
            return false;
        } 
        this.NomeAgenda = '...';
        axios.get(`/rpclinica/json/dados-agenda?cd_agenda=${this.CodAgenda}`)
        .then((res) => {
            console.log(res.data); 
            console.log(res.data.agendamento.nm_agenda);
            $('#NomeAgenda').val(res.data.agendamento.nm_agenda);
            this.NomeAgenda = res.data.agendamento.nm_agenda;
            this.AgendaLocal = res.data.locais;
            this.AgendaProf = res.data.profissionais;
            this.AgendaEspec = res.data.especialidades;
            this.AgendaConv = res.data.convenios;
            this.AgendaProc = res.data.procedimentos;
        })
        .catch((err) => { 
            toastr['error'](err.response.data.message);
        })
        .finally(() => $('#loading-dados-mes').hide());
        */
        $('#agendamento-manual input[type=date]').val($('#data-input').val());
        $('#data-de-nasc-manual[type=date]').val(null);
        alert($('#data-input').val());
        $('#agendamento-manual').modal('show');
    }
}));
 

